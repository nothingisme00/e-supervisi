@extends('layouts.modern')

@section('page-title', 'Beranda')

@section('content')
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 text-white rounded-lg p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold mb-2">Selamat Datang</h2>
            <p class="text-sm text-indigo-100 dark:text-indigo-200">Dokumentasikan proses pembelajaran Anda</p>
        </div>
        <div>
            @if(!$activeSupervisi)
                <a href="{{ route('guru.supervisi.create') }}" class="inline-block px-5 py-2.5 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                    Mulai Supervisi Baru
                </a>
            @else
                <a href="{{ route('guru.supervisi.continue', $activeSupervisi->id) }}" class="inline-block px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                    Lanjutkan Supervisi
                </a>
            @endif
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <form method="GET" action="{{ route('guru.home') }}">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama guru atau mata pelajaran..."
                   class="md:col-span-7 px-4 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">

            <div class="md:col-span-3 relative">
                <select name="tingkat"
                        class="w-full px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all appearance-none cursor-pointer">
                    <option value="">Semua Tingkat</option>
                    <option value="SD" {{ request('tingkat') == 'SD' ? 'selected' : '' }}>SD</option>
                    <option value="SMP" {{ request('tingkat') == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ request('tingkat') == 'SMA' ? 'selected' : '' }}>SMA</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <button type="submit" class="md:col-span-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                Cari
            </button>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Daftar Supervisi</h3>

    @if($supervisiList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            @foreach($supervisiList as $item)
            <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-600 dark:bg-indigo-500 rounded-full flex items-center justify-center text-white text-base font-semibold flex-shrink-0">
                        {{ substr($item->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 dark:text-white text-sm mb-0.5 truncate">{{ $item->user->name }}</div>
                        <div class="text-gray-600 dark:text-gray-400 text-xs">{{ $item->user->mata_pelajaran }} • {{ $item->user->tingkat }}</div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-3">
                    @if($item->status == 'submitted')
                        <span class="inline-block px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs font-medium rounded-full">Disubmit</span>
                    @elseif($item->status == 'reviewed')
                        <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-medium rounded-full">Direview</span>
                    @elseif($item->status == 'completed')
                        <span class="inline-block px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-medium rounded-full">Selesai</span>
                    @endif

                    @if($item->feedback->count() > 0)
                        <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-medium rounded-full">{{ $item->feedback->count() }} Feedback</span>
                    @endif
                </div>

                <div class="pt-3 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
                    <div class="text-gray-600 dark:text-gray-400 text-xs">
                        {{ \Carbon\Carbon::parse($item->tanggal_supervisi)->format('d M Y') }}
                    </div>

                    @if($item->user_id == auth()->id())
                        <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-block px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs font-medium rounded-lg transition-colors">
                            Lihat
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center">
            {{ $supervisiList->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Belum ada supervisi</p>
            @if(!$activeSupervisi)
                <a href="{{ route('guru.supervisi.create') }}" class="inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors">
                    Mulai Supervisi Baru
                </a>
            @endif
        </div>
    @endif
</div>

<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Tips</h3>
    <ul class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed space-y-2 list-disc list-inside">
        <li>Pastikan semua dokumen evaluasi sudah lengkap sebelum submit</li>
        <li>Upload video pembelajaran dengan kualitas yang baik</li>
        <li>Isi refleksi pembelajaran dengan detail dan jujur</li>
        <li>Periksa kembali semua data sebelum mengirim supervisi</li>
    </ul>
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
</script>
@endsection
