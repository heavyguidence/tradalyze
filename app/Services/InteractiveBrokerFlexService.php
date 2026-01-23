<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class InteractiveBrokerFlexService
{
    private const BASE_URL = 'https://ndcdyn.interactivebrokers.com/AccountManagement/FlexWebService';
    private const MAX_RETRY_ATTEMPTS = 60; // 5 minutes with 5-second intervals
    private const RETRY_DELAY_SECONDS = 5;

    /**
     * Request a flex report from Interactive Brokers
     *
     * @param string $flexToken
     * @param string $queryId
     * @return array ['success' => bool, 'referenceCode' => string|null, 'error' => string|null]
     */
    public function requestReport(string $flexToken, string $queryId): array
    {
        try {
            $url = self::BASE_URL . "/SendRequest?t={$flexToken}&q={$queryId}&v=3";
            
            Log::info('IB Flex: Requesting report', [
                'url' => $url,
                'query_id' => $queryId
            ]);

            $response = Http::timeout(30)->get($url);

            if (!$response->successful()) {
                Log::error('IB Flex: HTTP request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'referenceCode' => null,
                    'error' => 'HTTP request failed with status: ' . $response->status()
                ];
            }

            $xmlContent = $response->body();
            $result = $this->parseXmlResponse($xmlContent);

            Log::info('IB Flex: Report requested', $result);

            return $result;

        } catch (Exception $e) {
            Log::error('IB Flex: Exception during report request', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'referenceCode' => null,
                'error' => 'Failed to request report: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Retrieve the generated report using the reference code
     *
     * @param string $flexToken
     * @param string $referenceCode
     * @param int $maxAttempts Maximum number of retry attempts
     * @return array ['success' => bool, 'csvData' => string|null, 'error' => string|null]
     */
    public function retrieveReport(string $flexToken, string $referenceCode, int $maxAttempts = self::MAX_RETRY_ATTEMPTS): array
    {
        $attempt = 0;
        
        while ($attempt < $maxAttempts) {
            $attempt++;
            
            try {
                $url = self::BASE_URL . "/GetStatement?t={$flexToken}&q={$referenceCode}&v=3";
                
                Log::info('IB Flex: Retrieving report', [
                    'attempt' => $attempt,
                    'max_attempts' => $maxAttempts,
                    'reference_code' => $referenceCode
                ]);

                $response = Http::timeout(30)->get($url);

                if (!$response->successful()) {
                    Log::warning('IB Flex: Retrieval attempt failed', [
                        'attempt' => $attempt,
                        'status' => $response->status()
                    ]);
                    
                    if ($attempt >= $maxAttempts) {
                        return [
                            'success' => false,
                            'csvData' => null,
                            'error' => 'Max retry attempts reached. Report may not be ready.'
                        ];
                    }
                    
                    sleep(self::RETRY_DELAY_SECONDS);
                    continue;
                }

                $content = $response->body();
                
                // Check if it's an error XML response
                if (str_starts_with(trim($content), '<?xml') || str_starts_with(trim($content), '<FlexStatement')) {
                    $xmlResult = $this->parseXmlResponse($content);
                    
                    // If it's a failure response, check the error code
                    if (!$xmlResult['success']) {
                        // Error code 1019 means report is not ready yet
                        if (isset($xmlResult['errorCode']) && $xmlResult['errorCode'] === '1019') {
                            Log::info('IB Flex: Report not ready yet', [
                                'attempt' => $attempt,
                                'error' => $xmlResult['error']
                            ]);
                            
                            if ($attempt >= $maxAttempts) {
                                return [
                                    'success' => false,
                                    'csvData' => null,
                                    'error' => 'Report generation timeout. The report is taking longer than expected to generate.'
                                ];
                            }
                            
                            sleep(self::RETRY_DELAY_SECONDS);
                            continue;
                        }
                        
                        // Other errors should be returned immediately
                        return [
                            'success' => false,
                            'csvData' => null,
                            'error' => $xmlResult['error']
                        ];
                    }
                }
                
                // Check if the content appears to be CSV (contains common CSV indicators)
                if ($this->isLikelyCsv($content)) {
                    Log::info('IB Flex: Report retrieved successfully', [
                        'attempt' => $attempt,
                        'content_length' => strlen($content)
                    ]);
                    
                    return [
                        'success' => true,
                        'csvData' => $content,
                        'error' => null
                    ];
                }
                
                // If we get here, the response format is unexpected
                Log::warning('IB Flex: Unexpected response format', [
                    'attempt' => $attempt,
                    'content_preview' => substr($content, 0, 200)
                ]);
                
                if ($attempt >= $maxAttempts) {
                    return [
                        'success' => false,
                        'csvData' => null,
                        'error' => 'Unexpected response format from IB API.'
                    ];
                }
                
                sleep(self::RETRY_DELAY_SECONDS);
                
            } catch (Exception $e) {
                Log::error('IB Flex: Exception during report retrieval', [
                    'attempt' => $attempt,
                    'message' => $e->getMessage()
                ]);
                
                if ($attempt >= $maxAttempts) {
                    return [
                        'success' => false,
                        'csvData' => null,
                        'error' => 'Failed to retrieve report: ' . $e->getMessage()
                    ];
                }
                
                sleep(self::RETRY_DELAY_SECONDS);
            }
        }
        
        return [
            'success' => false,
            'csvData' => null,
            'error' => 'Max retry attempts reached.'
        ];
    }

    /**
     * Parse XML response from IB API
     *
     * @param string $xmlContent
     * @return array
     */
    private function parseXmlResponse(string $xmlContent): array
    {
        try {
            // Suppress XML parsing warnings
            libxml_use_internal_errors(true);
            
            $xml = simplexml_load_string($xmlContent);
            
            if ($xml === false) {
                $errors = libxml_get_errors();
                libxml_clear_errors();
                
                return [
                    'success' => false,
                    'referenceCode' => null,
                    'error' => 'Invalid XML response',
                    'errorCode' => null
                ];
            }
            
            $status = (string)$xml->Status;
            
            if (strtolower($status) === 'success') {
                $referenceCode = (string)$xml->ReferenceCode;
                
                return [
                    'success' => true,
                    'referenceCode' => $referenceCode,
                    'error' => null,
                    'errorCode' => null
                ];
            } else {
                $errorCode = (string)($xml->ErrorCode ?? '');
                $errorMessage = (string)($xml->ErrorMessage ?? 'Unknown error');
                
                return [
                    'success' => false,
                    'referenceCode' => null,
                    'error' => $errorMessage,
                    'errorCode' => $errorCode
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'referenceCode' => null,
                'error' => 'Failed to parse XML: ' . $e->getMessage(),
                'errorCode' => null
            ];
        }
    }

    /**
     * Check if content is likely CSV format
     *
     * @param string $content
     * @return bool
     */
    private function isLikelyCsv(string $content): bool
    {
        // Check if content starts with common CSV headers from IB
        $csvIndicators = [
            'TradeID',
            'Symbol',
            'DateTime',
            'Quantity',
            'TradePrice',
            'AssetClass',
            'Buy/Sell'
        ];
        
        $firstLine = strtok($content, "\n");
        
        foreach ($csvIndicators as $indicator) {
            if (str_contains($firstLine, $indicator)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Full import workflow: request report, wait for it, and retrieve CSV
     *
     * @param string $flexToken
     * @param string $queryId
     * @return array ['success' => bool, 'csvData' => string|null, 'error' => string|null, 'message' => string|null]
     */
    public function importReport(string $flexToken, string $queryId): array
    {
        // Step 1: Request the report
        $requestResult = $this->requestReport($flexToken, $queryId);
        
        if (!$requestResult['success']) {
            return [
                'success' => false,
                'csvData' => null,
                'error' => $requestResult['error'],
                'message' => 'Failed to request report from Interactive Brokers: ' . $requestResult['error']
            ];
        }
        
        $referenceCode = $requestResult['referenceCode'];
        
        // Step 2: Retrieve the report (with retries)
        $retrieveResult = $this->retrieveReport($flexToken, $referenceCode);
        
        if (!$retrieveResult['success']) {
            return [
                'success' => false,
                'csvData' => null,
                'error' => $retrieveResult['error'],
                'message' => 'Failed to retrieve report from Interactive Brokers: ' . $retrieveResult['error']
            ];
        }
        
        return [
            'success' => true,
            'csvData' => $retrieveResult['csvData'],
            'error' => null,
            'message' => 'Report retrieved successfully'
        ];
    }
}
