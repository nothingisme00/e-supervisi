<div>
    <!-- Filter & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-lg border border-gray-200 dark:border-gray-700 p-3 sm:p-4 mb-4 sm:mb-6">
        <!-- Desktop: All in one row | Mobile: Search + Filter Toggle -->
        <div class="flex flex-wrap md:flex-nowrap items-center gap-2 sm:gap-3">
            <!-- Search Input -->
            <div class="flex-1 min-w-0">
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Cari NIK, nama, atau email..."
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md sm:rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>
            
            <!-- Filter Toggle Button (Mobile Only) -->
            <button type="button" 
                    x-data="{ open: false }"
                    @click="open = !open; $dispatch('toggle-filter')"
                    class="md:hidden flex items-center justify-center gap-1.5 px-3 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-md border border-indigo-200 dark:border-indigo-800 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                @if($hasFilters)
                    <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                @endif
                <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Desktop Filters (Hidden on mobile, inline on md+) -->
            <div class="hidden md:flex items-center gap-2">
                <!-- Role Filter -->
                <div class="relative">
                    <select wire:model.live="role" class="w-full min-w-[130px] px-3 py-2.5 pr-8 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Role</option>
                        <option value="admin">Administrator</option>
                        <option value="guru">Guru</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Tingkat Filter -->
                <div class="relative">
                    <select wire:model.live="tingkat" class="w-full min-w-[130px] px-3 py-2.5 pr-8 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Tingkat</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select wire:model.live="status" class="w-full min-w-[130px] px-3 py-2.5 pr-8 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                @if($hasFilters)
                <button wire:click="resetFilters" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </button>
                @endif
            </div>
        </div>

        <!-- Mobile Filter Dropdowns (Collapsible) -->
        <div x-data="{ open: false }" 
             @toggle-filter.window="open = !open"
             x-show="open" 
             x-collapse
             class="md:hidden mt-3">
            <div class="grid grid-cols-1 gap-2">
                <!-- Role Filter -->
                <div class="relative">
                    <select wire:model.live="role" class="w-full px-3 py-2.5 pr-8 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Role</option>
                        <option value="admin">Administrator</option>
                        <option value="guru">Guru</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Tingkat Filter -->
                <div class="relative">
                    <select wire:model.live="tingkat" class="w-full px-3 py-2.5 pr-8 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Tingkat</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select wire:model.live="status" class="w-full px-3 py-2.5 pr-8 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                @if($hasFilters)
                <button wire:click="resetFilters" class="w-full px-3 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md transition-colors">
                    Reset Filter
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading indicator -->
    <div wire:loading class="fixed inset-0 bg-gray-900/20 dark:bg-gray-900/40 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg px-6 py-4 shadow-xl flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Memuat...</span>
        </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="bg-white dark:bg-gray-800 rounded-md sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Scrollable Table untuk Semua Layar -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <!-- Sortable NIK Column -->
                        <th class="px-3 sm:px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                            <button wire:click="sortBy('nik')" class="group inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <span>NIK</span>
                                @if($sortBy === 'nik')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>

                        <!-- Sortable Nama Column -->
                        <th class="px-3 sm:px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                            <button wire:click="sortBy('name')" class="group inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <span>Nama</span>
                                @if($sortBy === 'name')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @else
                                    <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>

                        <th class="px-3 sm:px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Email</th>
                        <th class="px-3 sm:px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Tingkat</th>
                        <th class="px-3 sm:px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Role</th>
                        <th class="px-3 sm:px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-3 sm:px-4 md:px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" wire:key="user-{{ $user->id }}">
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-900 dark:text-gray-300 font-mono whitespace-nowrap">{{ $user->nik }}</td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-600 dark:bg-indigo-500 rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-semibold flex-shrink-0 mr-2 sm:mr-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                                    @if($user->mata_pelajaran && $user->tingkat)
                                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->mata_pelajaran }} • {{ $user->tingkat }}</div>
                                    @elseif($user->tingkat)
                                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->tingkat }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                            <div class="max-w-[120px] sm:max-w-[180px] md:max-w-none truncate">{{ $user->email }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            @if($user->tingkat)
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                    {{ $user->tingkat }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-600 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            @if($user->role == 'admin')
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                    Admin
                                </span>
                            @elseif($user->role == 'guru')
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                    Guru
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                    Kepsek
                                </span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <button wire:click="toggleStatus({{ $user->id }})" 
                                    class="inline-flex items-center px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-medium transition-all {{ $user->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/50' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/50' }}" 
                                    title="Klik untuk ubah status">
                                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 {{ $user->is_active ? 'bg-green-500 dark:bg-green-400' : 'bg-red-500 dark:bg-red-400' }} rounded-full mr-1.5"></span>
                                <span>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </button>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 sm:w-auto sm:h-auto sm:px-3 sm:py-1.5 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white text-xs font-medium rounded-lg transition-colors"
                                   title="Edit user">
                                    <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                <button onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')"
                                        class="inline-flex items-center justify-center w-8 h-8 sm:w-auto sm:h-auto sm:px-3 sm:py-1.5 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors"
                                        title="Reset password">
                                    <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Reset</span>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return handleDelete(event, '{{ $user->name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 sm:w-auto sm:h-auto sm:px-3 sm:py-1.5 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors"
                                            title="Hapus user">
                                        <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 sm:px-6 py-8 sm:py-12">
                            <x-empty-state 
                                :icon="$hasFilters ? 'search' : 'users'" 
                                :title="$hasFilters ? 'Tidak ada pengguna ditemukan' : 'Belum ada pengguna'" 
                                :description="$hasFilters ? 'Coba ubah filter pencarian Anda' : 'Klik \'Tambah Pengguna\' untuk menambahkan pengguna baru'"
                                :actionText="$hasFilters ? 'Reset Filter' : ''"
                                actionUrl="#"
                                wire:click="resetFilters"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
