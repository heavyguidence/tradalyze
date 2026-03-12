@extends('layouts.app')

@section('title', 'View Diary Entry')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header with Back Button -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('diary') }}" class="inline-flex items-center text-orange-600 hover:text-orange-700 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Diary
            </a>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Diary Entry</h2>
            <p class="text-gray-600">
                {{ $entry->created_at->format('F j, Y \a\t g:i A') }}
                @if($entry->created_at != $entry->updated_at)
                    <span class="text-sm text-gray-500">(edited {{ $entry->updated_at->format('M j, Y \a\t g:i A') }})</span>
                @endif
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button 
                onclick="toggleEditMode()"
                id="editToggleBtn"
                class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg"
                title="Edit Entry"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </button>
            
            <button 
                onclick="confirmDelete()"
                class="p-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-lg"
                title="Delete Entry"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Entry Date Badge -->
    @if($entry->entry_date)
        <div class="mb-4 flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Trading Day: {{ $entry->entry_date->format('l, F d, Y') }}
            </span>
        </div>
    @endif

    <!-- View Mode -->
    <div id="viewMode" class="bg-white rounded-xl shadow-lg p-8">
        <div class="entry-content prose prose-lg max-w-none">
            {!! $entry->content !!}
        </div>
    </div>

    <!-- Trades on this day panel -->
    @if($entry->entry_date && $tradingDayPositions->isNotEmpty())
    <div class="bg-white rounded-xl shadow-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Trades on this Day
                </h3>
                <p class="text-sm text-gray-500 mt-0.5">{{ $tradingDayPositions->count() }} position{{ $tradingDayPositions->count() !== 1 ? 's' : '' }} closed on {{ $entry->entry_date->format('F d, Y') }}</p>
            </div>
            @php
                $dayTotal = $tradingDayPositions->sum('realized_pnl');
                $wins = $tradingDayPositions->filter(fn($p) => $p->realized_pnl > 0)->count();
                $losses = $tradingDayPositions->filter(fn($p) => $p->realized_pnl < 0)->count();
            @endphp
            <div class="flex items-center gap-3">
                @if($wins > 0)
                    <span class="text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full">{{ $wins }}W</span>
                @endif
                @if($losses > 0)
                    <span class="text-xs font-medium text-red-700 bg-red-100 px-2 py-0.5 rounded-full">{{ $losses }}L</span>
                @endif
                <span class="font-bold text-lg {{ $dayTotal > 0 ? 'text-green-600' : ($dayTotal < 0 ? 'text-red-600' : 'text-gray-500') }}">
                    {{ $dayTotal >= 0 ? '+' : '-' }}${{ number_format(abs($dayTotal), 2) }}
                </span>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($tradingDayPositions as $pos)
            <div class="px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <a href="{{ route('trades.show', $pos) }}" class="font-semibold text-gray-900 hover:text-orange-600 transition-colors text-sm whitespace-nowrap">
                            {{ $pos->instrument->symbol }}
                        </a>
                        @if($pos->instrument->isOption())
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                {{ $pos->instrument->put_call }} ${{ number_format($pos->instrument->strike, 0) }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-400">{{ number_format($pos->quantity, 0) }} {{ $pos->instrument->isOption() ? 'contracts' : 'shares' }}</span>
                    </div>
                    <div class="flex items-center gap-4 flex-shrink-0">
                        @if($pos->isClosed())
                            <span class="text-sm font-bold {{ $pos->isProfitable() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $pos->isProfitable() ? '+' : '-' }}${{ number_format(abs($pos->realized_pnl), 2) }}
                            </span>
                            @if($pos->isProfitable())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Win</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Loss</span>
                            @endif
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Open</span>
                        @endif
                        <a href="{{ route('trades.show', $pos) }}" class="text-xs text-orange-600 hover:text-orange-700 font-medium whitespace-nowrap">View →</a>
                    </div>
                </div>
                {{-- Screenshots for this position --}}
                @if($pos->screenshots->isNotEmpty())
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($pos->screenshots as $shot)
                    <a href="{{ $shot->url }}" target="_blank" class="block">
                        <img src="{{ $shot->url }}"
                             alt="{{ $shot->original_name ?? 'Screenshot' }}"
                             class="h-20 w-32 object-cover rounded border border-gray-200 hover:border-orange-400 transition-colors">
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Edit Mode -->
    <div id="editMode" class="bg-white rounded-xl shadow-lg p-8 hidden">
        <form id="updateForm">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Edit Your Trading Notes
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
                >{!! $entry->content !!}</div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button 
                    type="button"
                    onclick="toggleEditMode()"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
                >
                    Cancel
                </button>
                
                <button 
                    type="submit"
                    class="px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium shadow-lg"
                >
                    Update Entry
                </button>
            </div>
        </form>
    </div>
</div>

<style>
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
</style>

<script>
    // Format text using execCommand
    function formatText(command, value = null) {
        document.execCommand(command, false, value);
        document.getElementById('editor').focus();
    }
    
    // Toggle between view and edit mode
    function toggleEditMode() {
        const viewMode = document.getElementById('viewMode');
        const editMode = document.getElementById('editMode');
        const editBtn = document.getElementById('editToggleBtn');
        
        if (viewMode.classList.contains('hidden')) {
            // Switch to view mode
            viewMode.classList.remove('hidden');
            editMode.classList.add('hidden');
            editBtn.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>`;
            editBtn.title = 'Edit Entry';
        } else {
            // Switch to edit mode
            viewMode.classList.add('hidden');
            editMode.classList.remove('hidden');
            editBtn.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>`;
            editBtn.title = 'Cancel';
        }
    }
    
    // Confirm delete
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this entry? This action cannot be undone.')) {
            deleteEntry();
        }
    }
    
    // Delete entry
    async function deleteEntry() {
        try {
            const response = await fetch(`/diary/{{ $entry->id }}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '{{ route('diary') }}';
                }, 1000);
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Failed to delete entry. Please try again.', 'error');
        }
    }
    
    // Paste image handler for editor
    document.getElementById('editor').addEventListener('paste', async function(e) {
        const items = e.clipboardData && e.clipboardData.items;
        if (!items) return;
        for (const item of items) {
            if (item.type.startsWith('image/')) {
                e.preventDefault();
                const file = item.getAsFile();
                const placeholder = document.createElement('p');
                placeholder.textContent = 'Uploading image…';
                placeholder.style.color = '#9CA3AF';
                document.execCommand('insertHTML', false, placeholder.outerHTML);
                const fd = new FormData();
                fd.append('image', file);
                fd.append('_token', '{{ csrf_token() }}');
                try {
                    const res = await fetch('{{ route('diary.upload-image') }}', { method: 'POST', body: fd });
                    const json = await res.json();
                    const placeholders = document.getElementById('editor').querySelectorAll('p');
                    for (const p of placeholders) {
                        if (p.textContent === 'Uploading image…') {
                            p.outerHTML = `<img src="${json.url}" style="max-width:100%;height:auto;margin:1em 0;">`;
                            break;
                        }
                    }
                } catch {
                    // leave placeholder text on error
                }
                return;
            }
        }
    });

    // Handle form submission
    document.getElementById('updateForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const content = document.getElementById('editor').innerHTML;

        if (!content.trim()) {
            showNotification('Please write something before saving!', 'error');
            return;
        }

        try {
            const response = await fetch(`/diary/{{ $entry->id }}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    content: content
                })
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                
                // Update view mode content
                document.querySelector('.entry-content').innerHTML = content;
                
                // Switch back to view mode
                setTimeout(() => {
                    toggleEditMode();
                }, 1000);
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Failed to update entry. Please try again.', 'error');
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
