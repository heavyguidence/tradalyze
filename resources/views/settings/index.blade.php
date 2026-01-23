@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-900">Settings</h2>
    <p class="text-gray-600 mt-1">Manage your account settings and preferences</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Sidebar Navigation -->
    <div class="lg:col-span-2">
        <nav class="bg-white rounded-lg shadow-md p-4">
            <ul class="space-y-2">
                <li>
                    <a href="#profile" 
                       onclick="showSection('profile'); return false;"
                       id="nav-profile"
                       class="flex items-center px-4 py-3 rounded-lg transition-colors bg-orange-50 text-orange-600 font-medium">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>
                </li>
                <li>
                    <a href="#trade-settings" 
                       onclick="showSection('trade-settings'); return false;"
                       id="nav-trade-settings"
                       class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Trade Settings
                    </a>
                </li>
                <li>
                    <a href="#balances" 
                       onclick="showSection('balances'); return false;"
                       id="nav-balances"
                       class="flex items-center px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Balances
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Content Area -->
    <div class="lg:col-span-10">
        <!-- Profile Section -->
        <div id="section-profile" class="settings-section">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Profile Information -->
                <div class="border-b border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Profile Information</h3>
                    <p class="text-sm text-gray-600">Update your account's profile information and email address.</p>
                </div>

                <form action="{{ route('settings.profile.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', auth()->user()->name) }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', auth()->user()->email) }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-medium"
                            >
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                <div class="border-b border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Update Password</h3>
                    <p class="text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
                </div>

                <form action="{{ route('settings.password.update') }}" method="POST" class="p-6">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('current_password') border-red-500 @enderror"
                            >
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            >
                        </div>

                        <div class="flex justify-end">
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-medium"
                            >
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Trade Settings Section -->
        <div id="section-trade-settings" class="settings-section hidden">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="border-b border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Trade Setup Tags</h3>
                    <p class="text-sm text-gray-600">Create and manage tags to categorize your trades by setup type.</p>
                </div>

                <div class="p-6">
                    <!-- Add Tag Form -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4">Add New Tag</h4>
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <input 
                                    type="text" 
                                    id="tag-name" 
                                    placeholder="Tag name (e.g., Breakout)"
                                    maxlength="100"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                >
                            </div>
                            <div>
                                <input 
                                    type="color" 
                                    id="tag-color" 
                                    value="#3B82F6"
                                    class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer"
                                >
                            </div>
                            <button 
                                onclick="createTag()"
                                class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-medium whitespace-nowrap"
                            >
                                Add Tag
                            </button>
                        </div>
                    </div>

                    <!-- Tags List -->
                    <div id="tags-list" class="space-y-3">
                        @forelse($tags as $tag)
                            <div id="tag-{{ $tag->id }}" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3 flex-1">
                                    <div 
                                        class="w-8 h-8 rounded-md border border-gray-300 flex-shrink-0"
                                        style="background-color: {{ $tag->color }}"
                                    ></div>
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-900">{{ $tag->name }}</span>
                                        <span class="ml-2 text-sm text-gray-500">({{ $tag->positions()->count() }} trades)</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button 
                                        onclick="editTag({{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->color }}')"
                                        class="px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded-md transition-colors"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        onclick="deleteTag({{ $tag->id }})"
                                        class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <p>No tags yet. Create your first tag above.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Balances Section -->
<div id="edit-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Edit Tag</h3>
        <input type="hidden" id="edit-tag-id">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tag Name</label>
                <input 
                    type="text" 
                    id="edit-tag-name" 
                    maxlength="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <input 
                    type="color" 
                    id="edit-tag-color" 
                    class="h-10 w-full border border-gray-300 rounded-lg cursor-pointer"
                >
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button 
                onclick="closeEditModal()"
                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancel
            </button>
            <button 
                onclick="updateTag()"
                class="flex-1 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-medium"
            >
                Save Changes
            </button>
        </div>
    </div>
</div>

        <!-- Balances Section -->
        <div id="section-balances" class="settings-section hidden">
            <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-2xl">
                <div class="border-b border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Account Balances</h3>
                    <p class="text-sm text-gray-600">Manage your initial balance and track deposits/withdrawals.</p>
                </div>

                <div class="p-6">
                    <!-- Add Balance Form -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4">
                            @if(!$hasInitialBalance)
                                Add Initial Balance
                            @else
                                Add Deposit/Withdrawal
                            @endif
                        </h4>
                        <div class="space-y-4">
                            @if(!$hasInitialBalance)
                                <input type="hidden" id="balance-type" value="initial">
                            @else
                                <div>
                                    <label for="balance-type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                    <select 
                                        id="balance-type"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                    >
                                        <option value="deposit">Deposit</option>
                                        <option value="withdrawal">Withdrawal</option>
                                    </select>
                                </div>
                            @endif
                            <div>
                                <label for="balance-amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                                <input 
                                    type="number" 
                                    id="balance-amount" 
                                    placeholder="Enter amount"
                                    step="0.01"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                >
                            </div>
                            <div>
                                <label for="balance-date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                <input 
                                    type="date" 
                                    id="balance-date" 
                                    value="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                >
                            </div>
                            <div>
                                <label for="balance-description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                                <input 
                                    type="text" 
                                    id="balance-description" 
                                    placeholder="Enter description"
                                    maxlength="255"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                >
                            </div>
                        </div>
                        <div class="flex justify-end mt-6">
                            <button 
                                onclick="createBalance()"
                                class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-medium"
                            >
                                @if(!$hasInitialBalance)
                                    Set Initial Balance
                                @else
                                    Add Entry
                                @endif
                            </button>
                        </div>
                    </div>

                    <!-- Balances List -->
                    <div class="space-y-3" id="balances-list">
                        @forelse($balances as $balance)
                            <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow" data-balance-id="{{ $balance->id }}">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div class="flex-shrink-0">
                                        @if($balance->type === 'initial')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Initial
                                            </span>
                                        @elseif($balance->type === 'deposit')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Deposit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Withdrawal
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">
                                            ${{ number_format($balance->amount, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $balance->date->format('M d, Y') }}
                                            @if($balance->description)
                                                • {{ $balance->description }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($balance->type !== 'initial')
                                    <button 
                                        onclick="deleteBalance({{ $balance->id }})"
                                        class="ml-4 text-red-600 hover:text-red-800 transition-colors"
                                        title="Delete entry"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                @else
                                    <div class="ml-4 w-5"></div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500 text-sm">No balance entries yet</p>
                                <p class="text-gray-400 text-xs mt-1">Start by setting your initial balance</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Tag Modal -->
<div id="edit-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Edit Tag</h3>
        <input type="hidden" id="edit-tag-id">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tag Name</label>
                <input 
                    type="text" 
                    id="edit-tag-name" 
                    maxlength="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <input 
                    type="color" 
                    id="edit-tag-color" 
                    class="h-10 w-full border border-gray-300 rounded-lg cursor-pointer"
                >
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button 
                onclick="closeEditModal()"
                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
                Cancel
            </button>
            <button 
                onclick="updateTag()"
                class="flex-1 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors font-medium"
            >
                Save Changes
            </button>
        </div>
    </div>
</div>

<script>
    function showSection(section) {
        // Hide all sections
        document.querySelectorAll('.settings-section').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Remove active state from all nav items
        document.querySelectorAll('nav a').forEach(el => {
            el.classList.remove('bg-orange-50', 'text-orange-600', 'font-medium');
            el.classList.add('text-gray-700', 'hover:bg-gray-50');
        });
        
        // Show selected section
        document.getElementById('section-' + section).classList.remove('hidden');
        
        // Add active state to selected nav item
        const navItem = document.getElementById('nav-' + section);
        navItem.classList.add('bg-orange-50', 'text-orange-600', 'font-medium');
        navItem.classList.remove('text-gray-700', 'hover:bg-gray-50');
    }

    // Tag Management Functions
    async function createTag() {
        const name = document.getElementById('tag-name').value.trim();
        const color = document.getElementById('tag-color').value;

        if (!name) {
            showMessage('error', 'Please enter a tag name');
            return;
        }

        try {
            const response = await fetch('{{ route("settings.tags.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ name, color })
            });

            const data = await response.json();

            if (data.success) {
                showMessage('success', data.message);
                
                // Clear form
                document.getElementById('tag-name').value = '';
                document.getElementById('tag-color').value = '#3B82F6';
                
                // Add tag to list
                addTagToList(data.tag);
            } else {
                showMessage('error', data.message);
            }
        } catch (error) {
            showMessage('error', 'An error occurred while creating the tag');
            console.error('Error:', error);
        }
    }

    function addTagToList(tag) {
        const tagsList = document.getElementById('tags-list');
        
        // Remove empty state if exists
        const emptyState = tagsList.querySelector('.text-center.py-8');
        if (emptyState) {
            emptyState.remove();
        }

        const tagHtml = `
            <div id="tag-${tag.id}" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-md border border-gray-300" style="background-color: ${tag.color}"></div>
                    <span class="font-medium text-gray-900">${tag.name}</span>
                </div>
                <div class="flex gap-2">
                    <button onclick="editTag(${tag.id}, '${tag.name}', '${tag.color}')" class="px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        Edit
                    </button>
                    <button onclick="deleteTag(${tag.id})" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors">
                        Delete
                    </button>
                </div>
            </div>
        `;

        tagsList.insertAdjacentHTML('beforeend', tagHtml);
    }

    function editTag(id, name, color) {
        document.getElementById('edit-tag-id').value = id;
        document.getElementById('edit-tag-name').value = name;
        document.getElementById('edit-tag-color').value = color;
        document.getElementById('edit-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    async function updateTag() {
        const id = document.getElementById('edit-tag-id').value;
        const name = document.getElementById('edit-tag-name').value.trim();
        const color = document.getElementById('edit-tag-color').value;

        if (!name) {
            showMessage('error', 'Please enter a tag name');
            return;
        }

        try {
            const response = await fetch(`/settings/tags/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ name, color })
            });

            const data = await response.json();

            if (data.success) {
                showMessage('success', data.message);
                closeEditModal();
                
                // Update tag in list
                const tagEl = document.getElementById(`tag-${id}`);
                tagEl.querySelector('.w-8').style.backgroundColor = color;
                tagEl.querySelector('.font-medium').textContent = name;
                
                // Update edit button onclick
                const editBtn = tagEl.querySelector('button[onclick^="editTag"]');
                editBtn.setAttribute('onclick', `editTag(${id}, '${name}', '${color}')`);
            } else {
                showMessage('error', data.message);
            }
        } catch (error) {
            showMessage('error', 'An error occurred while updating the tag');
            console.error('Error:', error);
        }
    }

    async function deleteTag(id) {
        if (!confirm('Are you sure you want to delete this tag?')) {
            return;
        }

        try {
            const response = await fetch(`/settings/tags/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                showMessage('success', data.message);
                
                // Remove tag from list
                const tagEl = document.getElementById(`tag-${id}`);
                tagEl.remove();
                
                // Show empty state if no tags left
                const tagsList = document.getElementById('tags-list');
                if (tagsList.children.length === 0) {
                    tagsList.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <p>No tags yet. Create your first tag above.</p>
                        </div>
                    `;
                }
            } else {
                showMessage('error', data.message);
            }
        } catch (error) {
            showMessage('error', 'An error occurred while deleting the tag');
            console.error('Error:', error);
        }
    }

    function showMessage(type, message) {
        // Remove any existing messages
        const existingMessages = document.querySelectorAll('.alert-message');
        existingMessages.forEach(msg => msg.remove());
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert-message mb-6 border rounded-lg px-4 py-3 flex items-center ${
            type === 'success' 
                ? 'bg-green-50 border-green-200 text-green-800' 
                : 'bg-red-50 border-red-200 text-red-800'
        }`;
        
        alertDiv.innerHTML = `
            <svg class="w-5 h-5 mr-3 ${type === 'success' ? 'text-green-600' : 'text-red-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                }
            </svg>
            <span>${message}</span>
        `;
        
        const container = document.querySelector('.mb-8');
        container.parentNode.insertBefore(alertDiv, container.nextSibling);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Balance Management Functions
    async function createBalance() {
        const type = document.getElementById('balance-type').value;
        const amount = document.getElementById('balance-amount').value;
        const date = document.getElementById('balance-date').value;
        const description = document.getElementById('balance-description').value;

        if (!amount || amount <= 0) {
            showMessage('error', 'Please enter a valid amount');
            return;
        }

        if (!date) {
            showMessage('error', 'Please select a date');
            return;
        }

        try {
            const response = await fetch('{{ route("settings.balances.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type, amount, date, description })
            });

            const data = await response.json();
            
            if (response.ok && data.success) {
                showMessage('success', data.message);
                
                // Clear form
                document.getElementById('balance-amount').value = '';
                document.getElementById('balance-description').value = '';
                
                // Reload page to update the form and list
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showMessage('error', data.message);
            }
        } catch (error) {
            showMessage('error', 'An error occurred while creating the balance entry');
            console.error('Error:', error);
        }
    }

    async function deleteBalance(balanceId) {
        if (!confirm('Are you sure you want to delete this balance entry?')) {
            return;
        }

        try {
            const response = await fetch(`/settings/balances/${balanceId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            
            if (response.ok && data.success) {
                showMessage('success', data.message);
                
                // Remove from DOM
                const balanceElement = document.querySelector(`[data-balance-id="${balanceId}"]`);
                if (balanceElement) {
                    balanceElement.remove();
                }
                
                // Check if list is empty and show empty state
                const balancesList = document.getElementById('balances-list');
                if (balancesList.children.length === 0) {
                    balancesList.innerHTML = `
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No balance entries yet</p>
                            <p class="text-gray-400 text-xs mt-1">Start by setting your initial balance</p>
                        </div>
                    `;
                }
            } else {
                showMessage('error', data.message);
            }
        } catch (error) {
            showMessage('error', 'An error occurred while deleting the balance entry');
            console.error('Error:', error);
        }
    }
</script>
@endsection
