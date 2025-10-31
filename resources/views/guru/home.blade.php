@extends('layouts.modern')

@section('page-title', 'Beranda')

@section('content')
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold mb-2">Selamat Datang</h2>
            <p class="text-sm text-indigo-100">Dokumentasikan proses pembelajaran Anda</p>
        </div>
        <div>
            @if(!$activeSupervisi)
                <a href="{{ route('guru.supervisi.create') }}" class="inline-block px-5 py-2.5 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors">
                    Mulai Supervisi Baru
                </a>
            @else
                <a href="{{ route('guru.supervisi.continue', $activeSupervisi->id) }}" class="inline-block px-5 py-2.5 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                    Lanjutkan Supervisi
                </a>
            @endif
        </div>
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('guru.home') }}">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama guru atau mata pelajaran..."
                   class="md:col-span-7 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

            <select name="tingkat"
                    class="md:col-span-3 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">Semua Tingkat</option>
                <option value="SD" {{ request('tingkat') == 'SD' ? 'selected' : '' }}>SD</option>
                <option value="SMP" {{ request('tingkat') == 'SMP' ? 'selected' : '' }}>SMP</option>
                <option value="SMA" {{ request('tingkat') == 'SMA' ? 'selected' : '' }}>SMA</option>
            </select>

            <button type="submit" class="md:col-span-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Cari
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <h3 class="text-base font-semibold text-gray-900 mb-4">Daftar Supervisi</h3>

    @if($supervisiList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
            @foreach($supervisiList as $item)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white text-base font-semibold flex-shrink-0">
                        {{ substr($item->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 text-sm mb-0.5 truncate">{{ $item->user->name }}</div>
                        <div class="text-gray-600 text-xs">{{ $item->user->mata_pelajaran }} • {{ $item->user->tingkat }}</div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-3">
                    @if($item->status == 'submitted')
                        <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Disubmit</span>
                    @elseif($item->status == 'reviewed')
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Direview</span>
                    @elseif($item->status == 'completed')
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Selesai</span>
                    @endif

                    @if($item->feedback->count() > 0)
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ $item->feedback->count() }} Feedback</span>
                    @endif
                </div>

                <div class="pt-3 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-gray-600 text-xs">
                        {{ \Carbon\Carbon::parse($item->tanggal_supervisi)->format('d M Y') }}
                    </div>

                    @if($item->user_id == auth()->id())
                        <a href="{{ route('guru.supervisi.continue', $item->id) }}" class="inline-block px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition-colors">
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
            <p class="text-gray-500 text-sm mb-4">Belum ada supervisi</p>
            @if(!$activeSupervisi)
                <a href="{{ route('guru.supervisi.create') }}" class="inline-block px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Mulai Supervisi Baru
                </a>
            @endif
        </div>
    @endif
</div>

<div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-3">Tips</h3>
    <ul class="text-gray-700 text-sm leading-relaxed space-y-2 list-disc list-inside">
        <li>Pastikan semua dokumen evaluasi sudah lengkap sebelum submit</li>
        <li>Upload video pembelajaran dengan kualitas yang baik</li>
        <li>Isi refleksi pembelajaran dengan detail dan jujur</li>
        <li>Periksa kembali semua data sebelum mengirim supervisi</li>
    </ul>
</div>
@endsection
