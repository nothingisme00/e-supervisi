@extends('layouts.app')

@section('title', 'Detail Supervisi - ' . $supervisi->user->name)

@section('content')
<!-- Breadcrumb -->
<div class="mb-4">
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Review Supervisi', 'url' => route('admin.supervisi.index')],
        ['label' => 'Detail', 'icon' => true]
    ]" />
</div>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="w-full lg:w-3/4 mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">{{ $supervisi->user->name }}</h1>
                        <p class="text-slate-600 mt-1">{{ $supervisi->user->email }}</p>
                        <p class="text-sm text-slate-500 mt-1">NIK: {{ $supervisi->user->nik }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @if($supervisi->status === 'submitted')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                            <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                            Menunggu Peninjauan
                        </span>
                    @elseif($supervisi->status === 'under_review')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2"></span>
                            Sedang Ditinjau
                        </span>
                    @elseif($supervisi->status === 'completed')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                            Telah Ditinjau
                        </span>
                    @elseif($supervisi->status === 'revision')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-800">
                            <span class="w-2 h-2 bg-rose-500 rounded-full mr-2"></span>
                            Perlu Revisi
                        </span>
                    @endif
                    <p class="text-xs text-slate-500 mt-2">Disubmit: {{ $supervisi->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Grid Layout: 2x2 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start mb-8">
            
            <!-- Dokumen Evaluasi Diri -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-800">Dokumen Evaluasi Diri</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto space-y-2">
                        @forelse($supervisi->dokumenEvaluasi as $index => $dokumen)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    @if(str_ends_with($dokumen->file_path, '.pdf'))
                                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Dokumen {{ $index + 1 }}</p>
                                        @if($dokumen->deskripsi)
                                            <p class="text-xs text-slate-500">{{ $dokumen->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('admin.supervisi.download', $dokumen->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        @empty
                            <p class="text-slate-500 text-sm text-center py-4">Tidak ada dokumen</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Link Pembelajaran -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-800">Link Pembelajaran</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto space-y-3">
                        @if($supervisi->prosesPembelajaran)
                            @if($supervisi->prosesPembelajaran->video_link)
                                <div class="p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-100">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700 mb-1">Link Video Pembelajaran</p>
                                            <a href="{{ $supervisi->prosesPembelajaran->video_link }}" 
                                               target="_blank"
                                               class="text-sm text-blue-600 hover:text-blue-700 underline break-all">
                                                {{ Str::limit($supervisi->prosesPembelajaran->video_link, 50) }}
                                            </a>
                                        </div>
                                        <a href="{{ $supervisi->prosesPembelajaran->video_link }}" 
                                           target="_blank"
                                           class="ml-2 text-red-600 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($supervisi->prosesPembelajaran->meeting_link)
                                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700 mb-1">Link Meeting/Zoom</p>
                                            <a href="{{ $supervisi->prosesPembelajaran->meeting_link }}" 
                                               target="_blank"
                                               class="text-sm text-blue-600 hover:text-blue-700 underline break-all">
                                                {{ Str::limit($supervisi->prosesPembelajaran->meeting_link, 50) }}
                                            </a>
                                        </div>
                                        <a href="{{ $supervisi->prosesPembelajaran->meeting_link }}" 
                                           target="_blank"
                                           class="ml-2 text-blue-600 hover:text-blue-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if(!$supervisi->prosesPembelajaran->video_link && !$supervisi->prosesPembelajaran->meeting_link)
                                <p class="text-slate-500 text-sm text-center py-4">Tidak ada link pembelajaran</p>
                            @endif
                        @else
                            <p class="text-slate-500 text-sm text-center py-4">Tidak ada data pembelajaran</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Refleksi Pembelajaran -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-800">Refleksi Pembelajaran</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto space-y-4">
                        @if($supervisi->prosesPembelajaran)
                            @php
                                $reflections = [
                                    ['label' => 'Apakah hal terbaik yang dapat saya lakukan?', 'value' => $supervisi->prosesPembelajaran->refleksi_1],
                                    ['label' => 'Apa yang dapat saya tingkatkan?', 'value' => $supervisi->prosesPembelajaran->refleksi_2],
                                    ['label' => 'Apakah saya sudah menerapkan strategi terbaik?', 'value' => $supervisi->prosesPembelajaran->refleksi_3],
                                    ['label' => 'Bagaimana respons murid terhadap pembelajaran?', 'value' => $supervisi->prosesPembelajaran->refleksi_4],
                                    ['label' => 'Apa rencana perbaikan ke depan?', 'value' => $supervisi->prosesPembelajaran->refleksi_5],
                                ];
                            @endphp

                            @foreach($reflections as $index => $reflection)
                                @if($reflection['value'])
                                    <div class="p-3 bg-slate-50 rounded-lg">
                                        <p class="text-xs font-semibold text-slate-600 mb-2">{{ $index + 1 }}. {{ $reflection['label'] }}</p>
                                        <p class="text-sm text-slate-700 leading-relaxed">{{ $reflection['value'] }}</p>
                                    </div>
                                @endif
                            @endforeach

                            @if(!$supervisi->prosesPembelajaran->refleksi_1 && !$supervisi->prosesPembelajaran->refleksi_2 && !$supervisi->prosesPembelajaran->refleksi_3 && !$supervisi->prosesPembelajaran->refleksi_4 && !$supervisi->prosesPembelajaran->refleksi_5)
                                <p class="text-slate-500 text-sm text-center py-4">Tidak ada refleksi</p>
                            @endif
                        @else
                            <p class="text-slate-500 text-sm text-center py-4">Tidak ada data refleksi</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Feedback yang Ada -->
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-slate-800">Riwayat Feedback</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto space-y-3">
                        @forelse($supervisi->feedback as $fb)
                            <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg border border-amber-100">
                                <div class="flex items-start justify-between mb-2">
                                    <p class="text-xs font-semibold text-slate-600">{{ $fb->created_at->format('d M Y, H:i') }}</p>
                                    @if($fb->is_revision_request)
                                        <span class="text-xs px-2 py-1 bg-rose-100 text-rose-700 rounded-full font-medium">
                                            Revisi Diminta
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-700 leading-relaxed">{{ $fb->komentar }}</p>
                            </div>
                        @empty
                            <p class="text-slate-500 text-sm text-center py-4">Belum ada feedback</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- Feedback Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
            <h3 class="text-xl font-bold text-slate-800 mb-4">Berikan Feedback</h3>
            
            <form action="{{ route('admin.supervisi.feedback', $supervisi->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="komentar" class="block text-sm font-medium text-slate-700 mb-2">
                        Komentar / Feedback <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="komentar" 
                        id="komentar" 
                        rows="6" 
                        required
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none transition-all duration-200"
                        placeholder="Tuliskan feedback Anda untuk guru..."
                    >{{ old('komentar') }}</textarea>
                    @error('komentar')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="mark_completed" 
                        id="mark_completed" 
                        value="1"
                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                    >
                    <label for="mark_completed" class="ml-2 text-sm font-medium text-slate-700">
                        Tandai sebagai "Telah Ditinjau" setelah memberikan feedback
                    </label>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.supervisi.index') }}" 
                       class="px-6 py-2.5 text-slate-700 bg-slate-100 hover:bg-slate-200 font-medium rounded-lg transition-colors duration-200">
                        Kembali
                    </a>
                    <div class="flex space-x-3">
                        <button 
                            type="button"
                            onclick="document.getElementById('revisionModal').classList.remove('hidden')"
                            class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                            Minta Revisi
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                            Kirim Feedback
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Revision Request Modal -->
<div id="revisionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-slate-800">Minta Revisi</h3>
                <button 
                    type="button"
                    onclick="document.getElementById('revisionModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('admin.supervisi.revision', $supervisi->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="revision_notes" class="block text-sm font-medium text-slate-700 mb-2">
                        Catatan Revisi <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="revision_notes" 
                        id="revision_notes" 
                        rows="5" 
                        required
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent resize-none"
                        placeholder="Jelaskan apa yang perlu direvisi..."
                    ></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        type="button"
                        onclick="document.getElementById('revisionModal').classList.add('hidden')"
                        class="px-5 py-2.5 text-slate-700 bg-slate-100 hover:bg-slate-200 font-medium rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-lg transition-colors duration-200">
                        Kirim Permintaan Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif

@endsection
