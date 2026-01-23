@extends('layouts.app')

@section('title', 'Trading Diary')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Trading Diary</h2>
        <p class="text-gray-600">Document your trading insights, strategies, and reflections</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <!-- Rich Text Editor Container -->
        <form id="diaryForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Write Your Trading Notes
                </label>
                
                <!-- Toolbar -->
                <div class="border border-gray-300 rounded-t-lg bg-gray-50 p-2 flex flex-wrap gap-1">
                    <!-- Text Formatting -->
                    <button type="button" onclick="formatText('bold')" class="p-2 hover:bg-gray-200 rounded transition" title="Bold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 5H7v10h4c2.21 0 4-1.79 4-4s-1.79-4-4-4zm-2 8v-2h2c1.1 0 2 .9 2 2s-.9 2-2 2H9zm0-4V7h2c1.1 0 2 .9 2 2s-.9 2-2 2H9z"/>
                        </svg>
                    </button>
                    
                    <button type="button" onclick="formatText('italic')" class="p-2 hover:bg-gray-200 rounded transition" title="Italic">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 4H8l-2 12h2l2-12zm2 0h2l-2 12h-2l2-12z"/>
                        </svg>
                    </button>
                    
                    <button type="button" onclick="formatText('underline')" class="p-2 hover:bg-gray-200 rounded transition" title="Underline">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 16c-2.76 0-5-2.24-5-5V4h2v7c0 1.66 1.34 3 3 3s3-1.34 3-3V4h2v7c0 2.76-2.24 5-5 5zm-6 2h12v2H4v-2z"/>
                        </svg>
                    </button>
                    
                    <div class="w-px bg-gray-300 mx-1"></div>
                    
                    <!-- Headings -->
                    <button type="button" onclick="formatText('formatBlock', 'h1')" class="px-3 py-2 hover:bg-gray-200 rounded transition font-bold" title="Heading 1">
                        H1
                    </button>
                    
                    <button type="button" onclick="formatText('formatBlock', 'h2')" class="px-3 py-2 hover:bg-gray-200 rounded transition font-semibold" title="Heading 2">
                        H2
                    </button>
                    
                    <button type="button" onclick="formatText('formatBlock', 'h3')" class="px-3 py-2 hover:bg-gray-200 rounded transition font-medium" title="Heading 3">
                        H3
                    </button>
                    
                    <div class="w-px bg-gray-300 mx-1"></div>
                    
                    <!-- Lists -->
                    <button type="button" onclick="formatText('insertUnorderedList')" class="p-2 hover:bg-gray-200 rounded transition" title="Bullet List">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 7h2V5H3v2zm0 4h2V9H3v2zm0 4h2v-2H3v2zm4-8v2h10V7H7zm0 4h10V9H7v2zm0 4h10v-2H7v2z"/>
                        </svg>
                    </button>
                    
                    <button type="button" onclick="formatText('insertOrderedList')" class="p-2 hover:bg-gray-200 rounded transition" title="Numbered List">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 15H3v-2h2v2zm0-4H3V9h2v2zm0-4H3V5h2v2zm4 8h10v-2H9v2zm0-4h10V9H9v2zm0-4h10V5H9v2z"/>
                        </svg>
                    </button>
                    
                    <div class="w-px bg-gray-300 mx-1"></div>
                    
                    <!-- Alignment -->
                    <button type="button" onclick="formatText('justifyLeft')" class="p-2 hover:bg-gray-200 rounded transition" title="Align Left">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 5h14v2H3V5zm0 4h10v2H3V9zm0 4h14v2H3v-2zm0 4h10v2H3v-2z"/>
                        </svg>
                    </button>
                    
                    <button type="button" onclick="formatText('justifyCenter')" class="p-2 hover:bg-gray-200 rounded transition" title="Align Center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 5h14v2H3V5zm2 4h10v2H5V9zm-2 4h14v2H3v-2zm2 4h10v2H5v-2z"/>
                        </svg>
                    </button>
                    
                    <button type="button" onclick="formatText('justifyRight')" class="p-2 hover:bg-gray-200 rounded transition" title="Align Right">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 5h14v2H3V5zm4 4h10v2H7V9zm-4 4h14v2H3v-2zm4 4h10v2H7v-2z"/>
                        </svg>
                    </button>
                    
                    <div class="w-px bg-gray-300 mx-1"></div>
                    
                    <!-- Clear Formatting -->
                    <button type="button" onclick="formatText('removeFormat')" class="p-2 hover:bg-gray-200 rounded transition" title="Clear Formatting">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18.59 7L12 13.59 5.41 7 4 8.41l8 8 8-8L18.59 7z"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Editable Content Area -->
                <div 
                    id="editor" 
                    contenteditable="true" 
                    class="border border-gray-300 border-t-0 rounded-b-lg p-6 min-h-[400px] max-h-[600px] overflow-y-auto focus:outline-none focus:ring-2 focus:ring-orange-500 bg-white"
                    placeholder="Start writing your trading notes here..."
                ></div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end items-center mt-6 pt-6 border-t border-gray-200">
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="clearEditor()"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                    >
                        Clear
                    </button>
                    
                    <button 
                        type="submit"
                        class="px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium shadow-lg"
                    >
                        Save Entry
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Entries List -->
    <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Recent Entries</h3>
            
            <!-- Per Page Selector -->
            <div class="flex items-center gap-2">
                <label for="perPage" class="text-sm text-gray-600">Show:</label>
                <select 
                    id="perPage" 
                    onchange="changePerPage(this.value)"
                    class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                >
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                </select>
                <span class="text-sm text-gray-600">entries</span>
            </div>
        </div>
        
        <div id="entriesList">
            @forelse($entries as $entry)
                <div style="margin-bottom: 12px;">
                    <a href="{{ route('diary.show', $entry->id) }}" class="block border border-gray-200 rounded-lg p-5 hover:border-orange-400 hover:shadow-md transition-all" data-entry-id="{{ $entry->id }}">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm text-gray-500 mb-2">
                                {{ $entry->created_at->format('F j, Y \a\t g:i A') }}
                                @if($entry->created_at != $entry->updated_at)
                                    <span class="text-xs text-gray-400">(edited)</span>
                                @endif
                            </div>
                            <div class="text-gray-700 entry-preview">
                                {{ Str::limit(strip_tags($entry->content), 200) }}
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No diary entries yet. Start writing your first entry above!</p>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($entries->hasPages())
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Showing {{ $entries->firstItem() }} to {{ $entries->lastItem() }} of {{ $entries->total() }} entries
                </div>
                
                <div class="flex gap-2">
                    {{-- Previous Page Link --}}
                    @if ($entries->onFirstPage())
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <a href="{{ $entries->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Previous
                        </a>
                    @endif
                    
                    {{-- Page Numbers --}}
                    @foreach ($entries->getUrlRange(1, $entries->lastPage()) as $page => $url)
                        @if ($page == $entries->currentPage())
                            <span class="px-4 py-2 text-white bg-orange-600 rounded-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                    
                    {{-- Next Page Link --}}
                    @if ($entries->hasMorePages())
                        <a href="{{ $entries->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Next
                        </a>
                    @else
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    #editor:empty:before {
        content: attr(placeholder);
        color: #9CA3AF;
        pointer-events: none;
        display: block;
    }
    
    #editor h1 {
        font-size: 2em;
        font-weight: bold;
        margin: 0.67em 0;
    }
    
    #editor h2 {
        font-size: 1.5em;
        font-weight: bold;
        margin: 0.75em 0;
    }
    
    #editor h3 {
        font-size: 1.17em;
        font-weight: bold;
        margin: 0.83em 0;
    }
    
    #editor ul, #editor ol {
        margin: 1em 0;
        padding-left: 40px;
    }
    
    #editor ul {
        list-style-type: disc;
    }
    
    #editor ol {
        list-style-type: decimal;
    }
    
    #editor p {
        margin: 0.5em 0;
    }

    .entry-content h1 {
        font-size: 2em;
        font-weight: bold;
        margin: 0.67em 0;
    }
    
    .entry-content h2 {
        font-size: 1.5em;
        font-weight: bold;
        margin: 0.75em 0;
    }
    
    .entry-content h3 {
        font-size: 1.17em;
        font-weight: bold;
        margin: 0.83em 0;
    }
    
    .entry-content ul, .entry-content ol {
        margin: 1em 0;
        padding-left: 40px;
    }
    
    .entry-content ul {
        list-style-type: disc;
    }
    
    .entry-content ol {
        list-style-type: decimal;
    }
    
    .entry-content p {
        margin: 0.5em 0;
    }
    
    /* Entry preview styling */
    .entry-preview {
        line-height: 1.6;
        color: #4B5563;
    }
</style>

<script>
    // Format text using execCommand
    function formatText(command, value = null) {
        document.execCommand(command, false, value);
        document.getElementById('editor').focus();
    }
    
    // Change per page
    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page'); // Reset to first page
        window.location.href = url.toString();
    }
    
    // Clear editor content
    function clearEditor() {
        if (confirm('Are you sure you want to clear all content?')) {
            document.getElementById('editor').innerHTML = '';
        }
    }

    // Handle form submission for new entries
    document.getElementById('diaryForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const content = document.getElementById('editor').innerHTML;

        if (!content.trim()) {
            showNotification('Please write something before saving!', 'error');
            return;
        }

        try {
            const response = await fetch('/diary', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    content: content
                })
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                
                // Reload the page to show new entry
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Failed to save entry. Please try again.', 'error');
        }
    });
    
    // Show notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>
@endsection
