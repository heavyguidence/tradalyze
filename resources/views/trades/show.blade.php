@extends('layouts.app')

@section('title', 'Trade Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('trades') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Trades
            </a>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Trade Details</h1>
    </div>

    <!-- Position Summary Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Position Summary</h2>
        </div>
        <div class="px-6 py-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Symbol -->
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Symbol</label>
                    <div class="mt-2">
                        <div class="text-lg font-semibold text-gray-900">{{ $position->instrument->symbol }}</div>
                        @if($position->instrument->underlying_symbol)
                            <div class="text-sm text-gray-500">{{ $position->instrument->underlying_symbol }}</div>
                        @endif
                    </div>
                </div>

                <!-- Asset Type -->
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Type</label>
                    <div class="mt-2">
                        @if($position->instrument->isOption())
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $position->instrument->put_call }} Option
                            </div>
                            <div class="text-sm text-gray-500">
                                Strike: ${{ number_format($position->instrument->strike, 2) }}
                            </div>
                            @if($position->instrument->expiry)
                                <div class="text-sm text-gray-500">
                                    Expiry: {{ $position->instrument->expiry->format('M d, Y') }}
                                </div>
                            @endif
                            <div class="text-sm text-gray-500">
                                Multiplier: {{ $position->instrument->multiplier }}
                            </div>
                        @else
                            <div class="text-lg font-semibold text-gray-900">Stock</div>
                        @endif
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</label>
                    <div class="mt-2 text-lg font-semibold text-gray-900">
                        {{ number_format($position->quantity, 2) }} 
                        @if($position->instrument->isOption())
                            contracts
                        @else
                            shares
                        @endif
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</label>
                    <div class="mt-2">
                        @if($position->isClosed())
                            @if($position->isProfitable())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Win
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Loss
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Open
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Open Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <h3 class="text-md font-semibold text-green-900">Position Opened</h3>
            </div>
            <div class="px-6 py-5">
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Date & Time</label>
                        <div class="mt-1 text-sm text-gray-900">
                            {{ $position->open_datetime->format('F d, Y') }}
                            <span class="text-gray-500">at {{ $position->open_datetime->format('h:i:s A') }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 uppercase">Cost Basis</label>
                        <div class="mt-1 text-lg font-semibold text-gray-900">
                            ${{ number_format($position->cost_basis, 2) }}
                        </div>
                    </div>
                    @if($position->instrument->isOption())
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Premium per Contract</label>
                            <div class="mt-1 text-sm text-gray-900">
                                ${{ number_format($position->cost_basis / ($position->quantity * $position->instrument->multiplier), 4) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Close Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 {{ $position->isClosed() ? 'bg-red-50' : 'bg-gray-50' }}">
                <h3 class="text-md font-semibold {{ $position->isClosed() ? 'text-red-900' : 'text-gray-700' }}">
                    Position {{ $position->isClosed() ? 'Closed' : 'Still Open' }}
                </h3>
            </div>
            <div class="px-6 py-5">
                @if($position->isClosed())
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Date & Time</label>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ $position->close_datetime->format('F d, Y') }}
                                <span class="text-gray-500">at {{ $position->close_datetime->format('h:i:s A') }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Realized P&L</label>
                            <div class="mt-1 text-2xl font-bold {{ $position->isProfitable() ? 'text-green-600' : 'text-red-600' }}">
                                @if($position->isProfitable())
                                    +${{ number_format($position->realized_pnl, 2) }}
                                @else
                                    -${{ number_format(abs($position->realized_pnl), 2) }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase">Duration</label>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ $position->open_datetime->diff($position->close_datetime)->format('%d days, %h hours') }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Position is still open</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Fills History -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Execution History</h2>
            <p class="text-sm text-gray-500 mt-1">All fills related to this instrument</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Side</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fees</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exec ID</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalFees = 0;
                    @endphp
                    @foreach($position->instrument->fills as $fill)
                        @php
                            $total = ($fill->price * $fill->quantity * $position->instrument->multiplier);
                            if ($fill->side === 'SELL') {
                                $total = -$total;
                            }
                            $totalFees += $fill->fees;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $fill->datetime->format('M d, Y') }}
                                <span class="block text-xs text-gray-500">{{ $fill->datetime->format('H:i:s') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($fill->side === 'BUY')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        BUY
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        SELL
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($fill->quantity, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($fill->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($fill->fees, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $fill->side === 'BUY' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $fill->side === 'BUY' ? '-' : '+' }}${{ number_format(abs($total), 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">
                                {{ $fill->exec_id }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-700">
                            Total Fees:
                        </td>
                        <td colspan="3" class="px-6 py-3 text-sm font-semibold text-gray-900">
                            ${{ number_format($totalFees, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Tags Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Trade Tags</h2>
            <p class="text-sm text-gray-500 mt-1">Categorize this trade by setup type</p>
        </div>
        <div class="px-6 py-5">
            <!-- Current Tags -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Tags</label>
                <div id="current-tags" class="flex flex-wrap gap-2">
                    @forelse($position->tags as $tag)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-white" style="background-color: {{ $tag->color }}">
                            {{ $tag->name }}
                            <button 
                                type="button" 
                                onclick="removeTag({{ $tag->id }}, '{{ $tag->name }}')"
                                class="ml-2 text-white hover:text-gray-200"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @empty
                        <p class="text-sm text-gray-500">No tags assigned</p>
                    @endforelse
                </div>
            </div>

            <!-- Available Tags -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Add Tags</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($availableTags as $tag)
                        @if(!$position->tags->contains($tag->id))
                            <button 
                                type="button"
                                onclick="addTag({{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->color }}')"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium border-2 transition-colors hover:opacity-80"
                                style="border-color: {{ $tag->color }}; color: {{ $tag->color }}"
                                id="available-tag-{{ $tag->id }}"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ $tag->name }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Trade Notes</h2>
            <p class="text-sm text-gray-500 mt-1">Add notes about this trade for future reference</p>
        </div>
        <div class="px-6 py-5">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('trades.update', $position) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <textarea 
                        name="notes" 
                        rows="6" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                        placeholder="Add notes about this trade... (strategy, emotions, lessons learned, etc.)"
                    >{{ old('notes', $position->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    async function addTag(tagId, tagName, tagColor) {
        try {
            const response = await fetch(`/trades/{{ $position->id }}/tags/${tagId}/attach`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                // Remove from available tags
                const availableBtn = document.getElementById(`available-tag-${tagId}`);
                if (availableBtn) {
                    availableBtn.remove();
                }

                // Add to current tags
                const currentTags = document.getElementById('current-tags');
                
                // Remove "No tags assigned" message if exists
                const noTagsMsg = currentTags.querySelector('.text-gray-500');
                if (noTagsMsg) {
                    noTagsMsg.remove();
                }

                const tagHtml = `
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-white" style="background-color: ${tagColor}" id="current-tag-${tagId}">
                        ${tagName}
                        <button type="button" onclick="removeTag(${tagId}, '${tagName}')" class="ml-2 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </span>
                `;
                currentTags.insertAdjacentHTML('beforeend', tagHtml);
                
                showMessage('success', data.message);
            } else {
                showMessage('error', data.message);
            }
        } catch (error) {
            showMessage('error', 'An error occurred while adding the tag');
            console.error('Error:', error);
        }
    }

    async function removeTag(tagId, tagName) {
        try {
            const response = await fetch(`/trades/{{ $position->id }}/tags/${tagId}/detach`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                // Remove from current tags
                const currentTag = document.getElementById(`current-tag-${tagId}`);
                if (currentTag) {
                    currentTag.remove();
                }

                // Check if no tags left
                const currentTags = document.getElementById('current-tags');
                if (currentTags.children.length === 0) {
                    currentTags.innerHTML = '<p class="text-sm text-gray-500">No tags assigned</p>';
                }

                // Add back to available tags
                location.reload(); // Reload to refresh available tags list
                
                showMessage('success', data.message);
            } else {
                showMessage('error', data.message);
            }
        } catch (error) {
            showMessage('error', 'An error occurred while removing the tag');
            console.error('Error:', error);
        }
    }

    function showMessage(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `mb-4 rounded-md p-4 ${type === 'success' ? 'bg-green-50' : 'bg-red-50'}`;
        alertDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 ${type === 'success' ? 'text-green-400' : 'text-red-400'}" viewBox="0 0 20 20" fill="currentColor">
                        ${type === 'success' 
                            ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />'
                            : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />'
                        }
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium ${type === 'success' ? 'text-green-800' : 'text-red-800'}">${message}</p>
                </div>
            </div>
        `;

        const container = document.querySelector('.px-6.py-5');
        container.insertBefore(alertDiv, container.firstChild);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
</script>
@endsection
