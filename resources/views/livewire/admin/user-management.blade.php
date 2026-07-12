<div>
    <x-page-header title="Kelola Pengguna" subtitle="Manajemen akun sistem">
        <x-slot:actions>
            <x-button href="{{ route('admin.users.create') }}" size="sm">
                <x-icon name="plus" class="w-4 h-4" />
                <span class="hidden sm:inline">Tambah</span>
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <!-- Filter & Search (x-card tanpa flush: dropdown butuh overflow visible) -->
    <x-card class="mb-5">
        <div class="p-4 sm:p-5">
        <!-- Desktop: satu baris | Mobile: search + toggle filter -->
        <div class="flex flex-wrap md:flex-nowrap items-center gap-3">
            <!-- Search Input -->
            <div class="flex-1 min-w-0">
                <div class="relative">
                    <x-icon name="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500 pointer-events-none" />
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Cari pengguna..."
                           class="form-control pl-11 py-2.5">
                </div>
            </div>

            <!-- Filter Toggle Button (Mobile Only) -->
            <button type="button"
                    x-data="{ open: false }"
                    @click="open = !open; $dispatch('toggle-filter')"
                    class="md:hidden flex items-center justify-center gap-1.5 px-4 py-2.5 min-h-[44px] bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-lg border border-primary-200 dark:border-primary-800 hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-colors font-medium cursor-pointer">
                <x-icon name="funnel" class="w-4 h-4" />
                <span>Filter</span>
                @if($hasFilters)
                    <span class="w-2 h-2 bg-primary-600 rounded-full"></span>
                @endif
            </button>

            <!-- Desktop Filters (Hidden on mobile, inline on md+) -->
            <div class="hidden md:flex items-center gap-2">
                <!-- Role Filter -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" type="button"
                            class="min-w-[155px] px-4 py-2.5 text-left border bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 transition-colors flex items-center justify-between gap-2 cursor-pointer
                            {{ $role ? 'border-primary-500 dark:border-primary-400' : 'border-gray-300 dark:border-gray-600' }}">
                        <span class="{{ $role ? '' : 'text-gray-400 dark:text-gray-500' }}">
                            @if($role == 'admin') Admin
                            @elseif($role == 'guru') Guru
                            @elseif($role == 'kepala_sekolah') Kepala Sekolah
                            @else Semua Role
                            @endif
                        </span>
                        <x-icon name="chevron-down" ::class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute top-full mt-1 left-0 min-w-full w-max bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-[9990] origin-top">
                        <div class="p-1.5 space-y-0.5">
                            <div @click="$wire.set('role', ''); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors flex items-center gap-2 {{ !$role ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Semua Role</div>
                            <div @click="$wire.set('role', 'admin'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors flex items-center gap-2 {{ $role == 'admin' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Admin</div>
                            <div @click="$wire.set('role', 'guru'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors flex items-center gap-2 {{ $role == 'guru' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Guru</div>
                            <div @click="$wire.set('role', 'kepala_sekolah'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors flex items-center gap-2 {{ $role == 'kepala_sekolah' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Kepala Sekolah</div>
                        </div>
                    </div>
                </div>

                <!-- Tingkat Filter -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" type="button"
                            class="min-w-[155px] px-4 py-2.5 text-left border bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 transition-colors flex items-center justify-between gap-2 cursor-pointer
                            {{ $tingkat ? 'border-primary-500 dark:border-primary-400' : 'border-gray-300 dark:border-gray-600' }}">
                        <span class="{{ $tingkat ? '' : 'text-gray-400 dark:text-gray-500' }}">
                            @if($tingkat == 'SD') SD
                            @elseif($tingkat == 'SMP') SMP
                            @else Semua Tingkat
                            @endif
                        </span>
                        <x-icon name="chevron-down" ::class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute top-full mt-1 left-0 min-w-full w-max bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-[9990] origin-top">
                        <div class="p-1.5 space-y-0.5">
                            <div @click="$wire.set('tingkat', ''); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ !$tingkat ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Semua Tingkat</div>
                            <div @click="$wire.set('tingkat', 'SD'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $tingkat == 'SD' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">SD</div>
                            <div @click="$wire.set('tingkat', 'SMP'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $tingkat == 'SMP' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">SMP</div>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" type="button"
                            class="min-w-[140px] px-4 py-2.5 text-left border bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 transition-colors flex items-center justify-between gap-2 cursor-pointer
                            {{ $status ? 'border-primary-500 dark:border-primary-400' : 'border-gray-300 dark:border-gray-600' }}">
                        <span class="{{ $status ? '' : 'text-gray-400 dark:text-gray-500' }}">
                            @if($status == 'active') Aktif
                            @elseif($status == 'inactive') Nonaktif
                            @else Semua Status
                            @endif
                        </span>
                        <x-icon name="chevron-down" ::class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="absolute top-full mt-1 left-0 min-w-full w-max bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-[9990] origin-top">
                        <div class="p-1.5 space-y-0.5">
                            <div @click="$wire.set('status', ''); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ !$status ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Semua Status</div>
                            <div @click="$wire.set('status', 'active'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $status == 'active' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Aktif</div>
                            <div @click="$wire.set('status', 'inactive'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $status == 'inactive' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Nonaktif</div>
                        </div>
                    </div>
                </div>

                @if($hasFilters)
                <button wire:click="resetFilters" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors flex items-center gap-2 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                    <x-icon name="x-mark" class="w-4 h-4" />
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
             class="md:hidden mt-4">
            <div class="grid grid-cols-1 gap-3">
                <!-- Role Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Role</label>
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" type="button"
                                class="w-full px-4 py-2.5 min-h-[44px] text-left border bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 transition-colors flex items-center justify-between gap-2 cursor-pointer
                                {{ $role ? 'border-primary-500 dark:border-primary-400' : 'border-gray-300 dark:border-gray-600' }}">
                            <span class="{{ $role ? '' : 'text-gray-400 dark:text-gray-500' }}">
                                @if($role == 'admin') Admin
                                @elseif($role == 'guru') Guru
                                @elseif($role == 'kepala_sekolah') Kepala Sekolah
                                @else Semua Role
                                @endif
                            </span>
                            <x-icon name="chevron-down" ::class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" />
                        </button>
                        <div x-show="open" x-transition class="absolute top-full mt-1 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-[9990]">
                            <div class="p-1.5 space-y-0.5">
                                <div @click="$wire.set('role', ''); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ !$role ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Semua Role</div>
                                <div @click="$wire.set('role', 'admin'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $role == 'admin' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Admin</div>
                                <div @click="$wire.set('role', 'guru'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $role == 'guru' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Guru</div>
                                <div @click="$wire.set('role', 'kepala_sekolah'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $role == 'kepala_sekolah' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Kepala Sekolah</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tingkat Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Tingkat</label>
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" type="button"
                                class="w-full px-4 py-2.5 min-h-[44px] text-left border bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 transition-colors flex items-center justify-between gap-2 cursor-pointer
                                {{ $tingkat ? 'border-primary-500 dark:border-primary-400' : 'border-gray-300 dark:border-gray-600' }}">
                            <span class="{{ $tingkat ? '' : 'text-gray-400 dark:text-gray-500' }}">
                                @if($tingkat == 'SD') SD
                                @elseif($tingkat == 'SMP') SMP
                                @else Semua Tingkat
                                @endif
                            </span>
                            <x-icon name="chevron-down" ::class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" />
                        </button>
                        <div x-show="open" x-transition class="absolute top-full mt-1 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-[9990]">
                            <div class="p-1.5 space-y-0.5">
                                <div @click="$wire.set('tingkat', ''); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ !$tingkat ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Semua Tingkat</div>
                                <div @click="$wire.set('tingkat', 'SD'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $tingkat == 'SD' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">SD</div>
                                <div @click="$wire.set('tingkat', 'SMP'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $tingkat == 'SMP' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">SMP</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Status</label>
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" type="button"
                                class="w-full px-4 py-2.5 min-h-[44px] text-left border bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 transition-colors flex items-center justify-between gap-2 cursor-pointer
                                {{ $status ? 'border-primary-500 dark:border-primary-400' : 'border-gray-300 dark:border-gray-600' }}">
                            <span class="{{ $status ? '' : 'text-gray-400 dark:text-gray-500' }}">
                                @if($status == 'active') Aktif
                                @elseif($status == 'inactive') Nonaktif
                                @else Semua Status
                                @endif
                            </span>
                            <x-icon name="chevron-down" ::class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0" />
                        </button>
                        <div x-show="open" x-transition class="absolute top-full mt-1 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-[9990]">
                            <div class="p-1.5 space-y-0.5">
                                <div @click="$wire.set('status', ''); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ !$status ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Semua Status</div>
                                <div @click="$wire.set('status', 'active'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $status == 'active' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Aktif</div>
                                <div @click="$wire.set('status', 'inactive'); open = false" class="px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors {{ $status == 'inactive' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Nonaktif</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($hasFilters)
                <button wire:click="resetFilters" class="w-full px-4 py-2.5 min-h-[44px] bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                    <x-icon name="x-mark" class="w-4 h-4" />
                    Reset Filter
                </button>
                @endif
            </div>
        </div>
        </div>
    </x-card>

    <!-- Loading indicator -->
    <div wire:loading class="fixed inset-0 bg-gray-900/20 dark:bg-gray-900/40 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl px-6 py-4 shadow-2xl flex items-center gap-3 border border-gray-200 dark:border-gray-700">
            <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Memuat data...</span>
        </div>
    </div>

    <!-- ==================== DESKTOP TABLE VIEW (lg+) ==================== -->
    <x-card flush class="hidden md:block">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">NIK</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tingkat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" wire:key="desktop-user-{{ $user->id }}">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300 font-mono whitespace-nowrap tabular-nums">{{ $user->nik }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shrink-0 mr-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role == 'admin')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">Admin</span>
                            @elseif($user->role == 'guru')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">Guru</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300">Kepala Sekolah</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->tingkat)
                                <span class="text-sm text-gray-900 dark:text-gray-300">{{ $user->tingkat }}</span>
                                @if($user->mata_pelajaran)
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ $user->mata_pelajaran }}</span>
                                @endif
                            @else
                                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="toggleStatus({{ $user->id }})"
                                    wire:confirm="{{ $user->is_active ? 'Nonaktifkan akun ' . $user->name . '? User tidak akan bisa login.' : 'Aktifkan kembali akun ' . $user->name . '?' }}"
                                    class="inline-flex cursor-pointer rounded-full transition-opacity hover:opacity-80 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                    title="Klik untuk ubah status">
                                <x-status-badge :status="$user->is_active ? 'aktif' : 'nonaktif'" />
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                   title="Edit" aria-label="Edit {{ $user->name }}">
                                    <x-icon name="pencil" class="w-4 h-4" />
                                </a>
                                <button onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')"
                                        class="inline-flex items-center justify-center w-9 h-9 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                        title="Reset Password" aria-label="Reset password {{ $user->name }}">
                                    <x-icon name="key" class="w-4 h-4" />
                                </button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return handleDelete(event, '{{ $user->name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-9 h-9 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                            title="Hapus" aria-label="Hapus {{ $user->name }}">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4">
                            <x-empty-state
                                :icon="$hasFilters ? 'search' : 'users'"
                                :title="$hasFilters ? 'Tidak ada pengguna ditemukan' : 'Belum ada pengguna'"
                                :description="$hasFilters ? 'Coba ubah filter pencarian Anda' : 'Klik &quot;Tambah&quot; untuk menambahkan pengguna baru'"
                                :compact="true"
                            />
                            @if($hasFilters)
                            <div class="text-center pb-4">
                                <x-button wire:click="resetFilters" size="sm">Reset Filter</x-button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            {{ $users->links() }}
        </div>
        @endif
    </x-card>

    <!-- ==================== MOBILE TABLE VIEW (< md) ==================== -->
    <x-card flush class="md:hidden">
        <!-- Header -->
        <div class="bg-primary-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-primary-600 flex items-center justify-center">
                        <x-icon name="users" class="w-4 h-4 text-white" />
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Daftar Pengguna</h2>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $users->total() }} pengguna</p>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                    <x-icon name="arrow-right" class="w-4 h-4" />
                    <span>Geser</span>
                </div>
            </div>
        </div>

        <!-- Scrollable Table Container -->
        <div class="relative">
            <!-- Scroll Shadow Indicator (Right) -->
            <div class="absolute right-0 top-0 bottom-0 w-6 bg-gradient-to-l from-white dark:from-gray-800 to-transparent pointer-events-none z-10"></div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th style="position: sticky; left: 0;" class="z-[5] bg-gray-50 dark:bg-gray-700 px-3 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider min-w-[140px] border-r border-gray-200 dark:border-gray-600">Nama</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider min-w-[130px]">NIK</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider min-w-[90px]">Role</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider min-w-[100px]">Tingkat</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider min-w-[80px]">Status</th>
                            <th class="px-3 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider min-w-[160px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30" wire:key="mobile-table-user-{{ $user->id }}">
                            <!-- Nama (Sticky) -->
                            <td style="position: sticky; left: 0;" class="z-[4] px-3 py-3 border-r border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[90px]">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[90px]">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <!-- NIK -->
                            <td class="px-3 py-3">
                                <span class="text-xs font-mono text-gray-700 dark:text-gray-300 tabular-nums">{{ $user->nik }}</span>
                            </td>
                            <!-- Role -->
                            <td class="px-3 py-3">
                                @if($user->role == 'admin')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">Admin</span>
                                @elseif($user->role == 'guru')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">Guru</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300">Kepsek</span>
                                @endif
                            </td>
                            <!-- Tingkat -->
                            <td class="px-3 py-3">
                                @if($user->tingkat)
                                    <div class="text-xs font-medium text-gray-800 dark:text-gray-200">{{ $user->tingkat }}</div>
                                    @if($user->mata_pelajaran)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->mata_pelajaran }}</div>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <!-- Status -->
                            <td class="px-3 py-3">
                                <button wire:click="toggleStatus({{ $user->id }})"
                                        wire:confirm="{{ $user->is_active ? 'Nonaktifkan akun ' . $user->name . '? User tidak akan bisa login.' : 'Aktifkan kembali akun ' . $user->name . '?' }}"
                                        class="inline-flex cursor-pointer rounded-full transition-opacity hover:opacity-80 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                        title="Klik untuk ubah status">
                                    <x-status-badge :status="$user->is_active ? 'aktif' : 'nonaktif'" />
                                </button>
                            </td>
                            <!-- Aksi -->
                            <td class="px-3 py-3">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="inline-flex items-center justify-center w-11 h-11 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                       title="Edit" aria-label="Edit {{ $user->name }}">
                                        <x-icon name="pencil" class="w-4 h-4" />
                                    </a>
                                    <button onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')"
                                            class="inline-flex items-center justify-center w-11 h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                            title="Reset Password" aria-label="Reset password {{ $user->name }}">
                                        <x-icon name="key" class="w-4 h-4" />
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return handleDelete(event, '{{ $user->name }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-11 h-11 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                                                title="Hapus" aria-label="Hapus {{ $user->name }}">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4">
                                <x-empty-state
                                    :icon="$hasFilters ? 'search' : 'users'"
                                    :title="$hasFilters ? 'Tidak ditemukan' : 'Belum ada pengguna'"
                                    :description="$hasFilters ? 'Coba ubah filter' : 'Tambahkan pengguna baru'"
                                    :compact="true"
                                />
                                @if($hasFilters)
                                <div class="text-center pb-3">
                                    <x-button wire:click="resetFilters" size="sm">Reset Filter</x-button>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            {{ $users->links() }}
        </div>
        @endif
    </x-card>
</div>
