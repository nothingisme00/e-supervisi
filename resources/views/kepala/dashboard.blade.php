@extends('layouts.modern')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Total Supervisi</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalSupervisi }}</div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Menunggu Review</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiSubmitted }}</div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Sudah Direview</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiReviewed }}</div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Selesai</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $supervisiCompleted }}</div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Supervisi Terbaru</h3>
        <a href="{{ route('kepala.evaluasi.index') }}" class="inline-block px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors">
            Lihat Semua
        </a>
    </div>

    @if($recentSupervisi->count() > 0)
        <div class="overflow-x-auto -mx-6 px-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Guru</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-700 dark:text-gray-300">Mata Pelajaran</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-700 dark:text-gray-300">Tanggal</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSupervisi as $supervisi)
                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="py-3 px-3 text-sm font-semibold text-gray-900 dark:text-white">{{ $supervisi->user->name }}</td>
                        <td class="py-3 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $supervisi->user->mata_pelajaran }}</td>
                        <td class="py-3 px-3 text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->format('d/m/Y') }}</td>
                        <td class="py-3 px-3">
                            @if($supervisi->status == 'submitted')
                                <span class="inline-block px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs font-medium rounded-full">Menunggu</span>
                            @elseif($supervisi->status == 'reviewed')
                                <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-medium rounded-full">Direview</span>
                            @endif
                        </td>
                        <td class="py-3 px-3">
                            <a href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" class="inline-block px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-xs font-medium rounded-lg transition-colors">
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-10">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada supervisi yang perlu direview</p>
        </div>
    @endif
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Informasi</h3>
    <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
        Selamat datang di Dashboard Kepala Sekolah. Dari sini Anda dapat melihat dan mengevaluasi supervisi pembelajaran yang telah disubmit oleh para guru.
    </p>
</div>
@endsection
