@extends('layouts.modern')

@section('page-title', 'Kelola Pengguna')

@section('content')
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Kelola Pengguna']
]" />

<!-- Header dengan Tombol Tambah -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4 mb-4 sm:mb-6">
    <div>
        <h2 class="text-base sm:text-2xl font-bold text-gray-900 dark:text-white">Manajemen Pengguna</h2>
        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-0.5 sm:mt-1">Kelola data pengguna sistem E-Supervisi</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Pengguna
    </a>
</div>

<!-- Filter & Search -->
<div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-lg border border-gray-200 dark:border-gray-700 p-3 sm:p-6 mb-4 sm:mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}">
        <!-- Preserve sorting parameters -->
        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
        <input type="hidden" name="sort_direction" value="{{ request('sort_direction') }}">

        <!-- Search Row with Filter Toggle (Always Visible) -->
        <div class="flex gap-2 mb-2 md:mb-0">
            <!-- Search Input -->
            <div class="flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari NIK, nama, atau email..."
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>
            
            <!-- Filter Toggle Button (Mobile Only) -->
            <button type="button" id="filterToggle" class="md:hidden flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-md border border-indigo-200 dark:border-indigo-800 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                @if(request('role') || request('tingkat') || request('status'))
                    <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                @endif
                <svg id="filterChevron" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <!-- Filter Dropdowns (Hidden on mobile by default, shown on md+) -->
        <div id="filterContent" class="hidden md:block mt-2 sm:mt-3">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 sm:gap-3">
                <!-- Role Filter -->
                <div class="md:col-span-3 lg:col-span-2">
                    <div class="relative">
                        <select name="role" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-8 sm:pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all appearance-none cursor-pointer">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="kepala_sekolah" {{ request('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 sm:px-3 text-gray-500 dark:text-gray-400">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tingkat Filter -->
                <div class="md:col-span-3 lg:col-span-2">
                    <div class="relative">
                        <select name="tingkat" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-8 sm:pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all appearance-none cursor-pointer">
                            <option value="">Semua Tingkat</option>
                            <option value="SD" {{ request('tingkat') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ request('tingkat') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 sm:px-3 text-gray-500 dark:text-gray-400">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-3 lg:col-span-2">
                    <div class="relative">
                        <select name="status" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 pr-8 sm:pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all appearance-none cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 sm:px-3 text-gray-500 dark:text-gray-400">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="md:col-span-3 lg:col-span-6 flex gap-1.5 sm:gap-2">
                    <button type="submit" class="flex-1 md:flex-none px-3 sm:px-4 py-2 sm:py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        Filter
                    </button>
                    @if(request('search') || request('role') || request('tingkat') || request('status'))
                    <a href="{{ route('admin.users.index') }}" class="px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-medium rounded-md sm:rounded-lg transition-colors">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    const filterContent = document.getElementById('filterContent');
    const filterChevron = document.getElementById('filterChevron');
    
    if (filterToggle && filterContent && filterChevron) {
        filterToggle.addEventListener('click', function() {
            filterContent.classList.toggle('hidden');
            filterChevron.classList.toggle('rotate-180');
        });
    }
});
</script>

<!-- Tabel Pengguna -->
<div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Scrollable Table untuk Semua Layar -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <tr>
                    <!-- Sortable NIK Column -->
                    <th class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'nik', 'sort_direction' => ($sortBy === 'nik' && $sortDirection === 'asc') ? 'desc' : 'asc'])) }}"
                           class="group inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span>NIK</span>
                            @if($sortBy === 'nik')
                                @if($sortDirection === 'asc')
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 dark:text-gray-600 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            @endif
                        </a>
                    </th>

                    <!-- Sortable Nama Column -->
                    <th class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'name', 'sort_direction' => ($sortBy === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc'])) }}"
                           class="group inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span>Nama</span>
                            @if($sortBy === 'name')
                                @if($sortDirection === 'asc')
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 dark:text-gray-600 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            @endif
                        </a>
                    </th>

                    <th class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Email</th>
                    <th class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Tingkat</th>
                    <th class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Role</th>
                    <th class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Status</th>
                    <th class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 text-left text-[10px] sm:text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 md:py-4 text-[10px] sm:text-xs md:text-sm text-gray-900 dark:text-gray-300 font-mono whitespace-nowrap">{{ $user->nik }}</td>
                    <td class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 md:py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-indigo-600 dark:bg-indigo-500 rounded-full flex items-center justify-center text-white text-[10px] sm:text-xs md:text-sm font-semibold flex-shrink-0 mr-1.5 sm:mr-2 md:mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] sm:text-xs md:text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                                @if($user->mata_pelajaran && $user->tingkat)
                                    <div class="text-[9px] sm:text-[10px] md:text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->mata_pelajaran }} • {{ $user->tingkat }}</div>
                                @elseif($user->tingkat)
                                    <div class="text-[9px] sm:text-[10px] md:text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->tingkat }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 md:py-4 text-[10px] sm:text-xs md:text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                        <div class="max-w-[100px] sm:max-w-[150px] md:max-w-none truncate">{{ $user->email }}</div>
                    </td>
                    <td class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 md:py-4 whitespace-nowrap">
                        @if($user->tingkat)
                            <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] md:text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                {{ $user->tingkat }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-600 text-[10px] sm:text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 md:py-4 whitespace-nowrap">
                        @if($user->role == 'admin')
                            <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] md:text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                Admin
                            </span>
                        @elseif($user->role == 'guru')
                            <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] md:text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                Guru
                            </span>
                        @else
                            <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] md:text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                Kepsek
                            </span>
                        @endif
                    </td>
                    <td class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 md:py-4 whitespace-nowrap">
                        <button onclick="toggleStatus({{ $user->id }}, this)" class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 rounded-full text-[9px] sm:text-[10px] md:text-xs font-medium transition-all {{ $user->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/50' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/50' }}" title="Klik untuk ubah status">
                            <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 {{ $user->is_active ? 'bg-green-500 dark:bg-green-400' : 'bg-red-500 dark:bg-red-400' }} rounded-full mr-1 sm:mr-1.5"></span>
                            <span class="status-text">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </button>
                    </td>
                    <td class="px-2 sm:px-3 md:px-6 py-2 sm:py-3 md:py-4 whitespace-nowrap">
                        <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="inline-flex items-center px-1.5 sm:px-2 md:px-3 py-0.5 sm:py-1 md:py-1.5 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white text-[8px] sm:text-[10px] md:text-xs font-medium rounded-md sm:rounded-lg transition-colors"
                               title="Edit user">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-4 md:h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="hidden md:inline">Edit</span>
                            </a>
                            <button onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')"
                                    class="inline-flex items-center px-1.5 sm:px-2 md:px-3 py-0.5 sm:py-1 md:py-1.5 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-[8px] sm:text-[10px] md:text-xs font-medium rounded-md sm:rounded-lg transition-colors"
                                    title="Reset password">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-4 md:h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                <span class="hidden md:inline">Reset</span>
                            </button>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return handleDelete(event, '{{ $user->name }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-1.5 sm:px-2 md:px-3 py-0.5 sm:py-1 md:py-1.5 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white text-[8px] sm:text-[10px] md:text-xs font-medium rounded-md sm:rounded-lg transition-colors"
                                        title="Hapus user">
                                    <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-4 md:h-4 md:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span class="hidden md:inline">Hapus</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-3 sm:px-6 py-8 sm:py-12">
                        <x-empty-state 
                            icon="@if(request('search') || request('role') || request('tingkat') || request('status')) search @else users @endif" 
                            title="@if(request('search') || request('role') || request('tingkat') || request('status')) Tidak ada pengguna ditemukan @else Belum ada pengguna @endif" 
                            description="@if(request('search') || request('role') || request('tingkat') || request('status')) Coba ubah filter pencarian Anda @else Klik 'Tambah Pengguna' untuk menambahkan pengguna baru @endif"
                            actionText="@if(request('search') || request('role') || request('tingkat') || request('status')) Reset Filter @endif"
                            actionUrl="{{ route('admin.users.index') }}"
                        />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Info Card -->
<div class="mt-4 sm:mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md sm:rounded-lg p-3 sm:p-4">
    <div class="flex">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <h3 class="text-xs sm:text-sm font-semibold text-blue-900 dark:text-blue-300 mb-0.5 sm:mb-1">Informasi</h3>
            <p class="text-[10px] sm:text-xs text-blue-800 dark:text-blue-400 leading-relaxed">
                Anda dapat mengelola semua pengguna sistem dari halaman ini. Gunakan filter untuk mempermudah pencarian pengguna.
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown hover effect (border color only)
        const allSelects = document.querySelectorAll('select');
        allSelects.forEach(select => {
            select.addEventListener('mouseenter', function() {
                if (document.activeElement !== this) {
                    this.style.borderColor = '#818cf8';
                }
            });
            select.addEventListener('mouseleave', function() {
                if (document.activeElement !== this) {
                    this.style.borderColor = '';
                }
            });
        });
    });

    // Handle delete form submission
    function handleDelete(event, userName) {
        event.preventDefault();
        const confirmed = confirm(`Apakah Anda yakin ingin menghapus pengguna ${userName}?\n\nData yang dihapus tidak dapat dikembalikan.`);
        if (confirmed) {
            event.target.submit();
        }
        return false;
    }

    // Toggle user status
    function toggleStatus(userId, button) {
        console.log('Toggle status called for user:', userId);
        
        if (!confirm('Apakah Anda yakin ingin mengubah status user ini?')) {
            return;
        }

        // Disable button during request
        button.disabled = true;
        button.classList.add('opacity-70');

        const url = `{{ url('admin/users') }}/${userId}/toggle-status`;
        console.log('Fetching URL:', url);

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update button appearance
                const statusDot = button.querySelector('span:first-child');
                const statusText = button.querySelector('.status-text');

                if (data.is_active) {
                    button.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/50';
                    statusDot.className = 'w-1.5 h-1.5 bg-green-500 dark:bg-green-400 rounded-full mr-1.5';
                    statusText.textContent = 'Aktif';
                } else {
                    button.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/50';
                    statusDot.className = 'w-1.5 h-1.5 bg-red-500 dark:bg-red-400 rounded-full mr-1.5';
                    statusText.textContent = 'Nonaktif';
                }

                alert('Status user berhasil diubah!');
                button.disabled = false;
                button.classList.remove('opacity-70');
            } else {
                throw new Error('Response success is false');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            alert('Terjadi kesalahan saat mengubah status user. Silakan coba lagi.\n\nError: ' + error.message);
            button.disabled = false;
            button.classList.remove('opacity-70');
        });
    }

    // Reset password
    function resetPassword(userId, userName) {
        console.log('Reset password called for user:', userId, userName);
        
        if (!confirm(`Apakah Anda yakin ingin mereset password untuk user "${userName}"?\n\nPassword akan direset ke: pass123456`)) {
            return;
        }

        const url = `{{ url('admin/users') }}/${userId}/reset-password`;
        console.log('Fetching URL:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('Password Berhasil Direset!\n\n' + data.message);
            } else {
                throw new Error('Response success is false');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            alert('Terjadi kesalahan saat mereset password. Silakan coba lagi.\n\nError: ' + error.message);
        });
    }
</script>
@endsection
