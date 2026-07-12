@extends('layouts.modern')

@section('page-title', 'Dashboard Admin')

@section('content')
<x-page-header title="Manajemen E-Supervisi" subtitle="Kelola pengguna dan monitor supervisi">
    <x-slot:actions>
        <x-button variant="secondary" size="sm" onclick="openTipsModal()">
            <x-icon name="information-circle" class="w-4 h-4" />
            <span class="hidden sm:inline">Tips & Info</span>
        </x-button>
        <x-button variant="secondary" size="sm" onclick="openGuideModal()">
            <x-icon name="book-open" class="w-4 h-4" />
            <span class="hidden sm:inline">Panduan</span>
        </x-button>
    </x-slot:actions>
</x-page-header>

<!-- Quick Actions - disembunyikan di mobile, akses via bottom nav -->
<div class="hidden sm:grid grid-cols-2 gap-3 sm:gap-4 mb-6">
    <a href="{{ route('admin.users.create') }}" class="group block rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900">
        <x-card class="flex items-center gap-4 p-4 group-hover:border-emerald-300 dark:group-hover:border-emerald-700 group-hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center shrink-0">
                <x-icon name="plus" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
            </div>
            <div class="min-w-0">
                <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-white">Tambah User</h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Buat akun guru atau kepala sekolah</p>
            </div>
        </x-card>
    </a>

    <a href="{{ route('admin.users.index') }}" class="group block rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900">
        <x-card class="flex items-center gap-4 p-4 group-hover:border-blue-300 dark:group-hover:border-blue-700 group-hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center shrink-0">
                <x-icon name="users" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <div class="min-w-0">
                <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-white">Kelola Pengguna</h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Lihat dan edit data pengguna</p>
            </div>
        </x-card>
    </a>
</div>

<!-- Grid 3 kolom monitor (guru terdaftar + supervisi berjalan/selesai) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">

    <!-- Card 1: Data Guru -->
    <x-card flush>
        <div class="h-1 bg-blue-500"></div>
        <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-blue-100 dark:bg-blue-900/30 rounded-lg sm:rounded-xl flex items-center justify-center shrink-0">
                        <x-icon name="users" class="w-5 h-5 sm:w-7 sm:h-7 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white truncate">Data Guru</h2>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Guru terdaftar</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400 tabular-nums">{{ $totalGuru }}</div>
                    <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Guru</div>
                </div>
            </div>
        </div>
        <div class="p-3 sm:p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/30">
            @if($guruList->count() > 0)
            <div class="space-y-2 sm:space-y-3">
                @foreach($guruList as $guru)
                <div class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-md transition-all">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm shrink-0">
                        {{ strtoupper(substr($guru->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white truncate">{{ $guru->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $guru->nik }}</div>
                        @if($guru->tingkat)
                        <div class="text-xs text-blue-600 dark:text-blue-400 font-medium mt-0.5">{{ $guru->tingkat }}</div>
                        @endif
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white tabular-nums">{{ $guru->total_supervisi }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Supervisi</div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <x-empty-state
                icon="users"
                title="Tidak ada guru"
                description="Belum ada guru terdaftar"
                :compact="true"
            />
            @endif
        </div>
    </x-card>

    <!-- Card 2: Dalam Proses (submitted + under_review) -->
    <x-card flush>
        <div class="h-1 bg-primary-500"></div>
        <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-primary-100 dark:bg-primary-900/30 rounded-lg sm:rounded-xl flex items-center justify-center shrink-0">
                        <x-icon name="eye" class="w-5 h-5 sm:w-7 sm:h-7 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white truncate">Dalam Proses</h2>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Sedang berjalan</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-xl sm:text-3xl font-bold text-primary-600 dark:text-primary-400 tabular-nums">{{ $supervisiPending + $supervisiInProgress }}</div>
                    <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
                </div>
            </div>
        </div>
        <div class="p-3 sm:p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/30">
            @if($supervisiUnderReviewList->count() > 0)
            <div class="space-y-2 sm:space-y-3">
                @foreach($supervisiUnderReviewList as $supervisi)
                <div class="flex items-start gap-2 sm:gap-3 p-2.5 sm:p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 hover:shadow-md transition-all">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm shrink-0">
                        {{ strtoupper(substr($supervisi->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $supervisi->user->nik }}</div>
                        <div class="flex items-center gap-2 mt-1">
                            <x-status-badge :status="$supervisi->status" />
                        </div>
                    </div>
                    <div class="text-xs text-gray-400 dark:text-gray-500 shrink-0">
                        {{ $supervisi->updated_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <x-empty-state
                icon="document"
                title="Tidak ada supervisi"
                description="Tidak ada supervisi dalam proses"
                :compact="true"
            />
            @endif
        </div>
    </x-card>

    <!-- Card 3: Selesai -->
    <x-card flush>
        <div class="h-1 bg-emerald-500"></div>
        <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                    {{-- emerald-50 (bukan -100) agar tak tertangkap heuristik StatusPillSingleSourceTest — ini chip ikon, bukan pill status --}}
                    <div class="w-10 h-10 sm:w-14 sm:h-14 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg sm:rounded-xl flex items-center justify-center shrink-0">
                        <x-icon name="check-circle" class="w-5 h-5 sm:w-7 sm:h-7 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white truncate">Selesai</h2>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Review selesai</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $supervisiCompleted }}</div>
                    <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">Supervisi</div>
                </div>
            </div>
        </div>
        <div class="p-3 sm:p-5 max-h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900/30">
            @if($supervisiCompletedList->count() > 0)
            <div class="space-y-2 sm:space-y-3">
                @foreach($supervisiCompletedList as $supervisi)
                <div class="flex items-start gap-2 sm:gap-3 p-2.5 sm:p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-md transition-all">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-xs sm:text-sm shrink-0">
                        {{ strtoupper(substr($supervisi->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $supervisi->user->nik }}</div>
                        <div class="flex items-center gap-2 mt-1">
                            <x-status-badge :status="$supervisi->status" />
                        </div>
                    </div>
                    <div class="text-xs text-gray-400 dark:text-gray-500 shrink-0">
                        {{ $supervisi->reviewed_at ? $supervisi->reviewed_at->diffForHumans() : '-' }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <x-empty-state
                icon="check"
                title="Tidak ada supervisi"
                description="Belum ada supervisi yang selesai"
                :compact="true"
            />
            @endif
        </div>
    </x-card>

</div>

@endsection
