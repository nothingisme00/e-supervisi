@extends('layouts.app')

@section('title', 'Unggah Lembar Evaluasi Diri')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-700 text-center">Lembar Evaluasi Diri</h2>

    {{-- Form Upload --}}
    <form action="{{ route('guru.supervisi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Bagian Upload 7 File --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
                $berkas = ['CP','ATP','Kalender','Program Tahunan','Program Semester','Modul Ajar','Bahan Ajar'];
            @endphp

            @foreach($berkas as $idx => $name)
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">{{ $name }} (JPG/PDF, max 2MB)</label>
                    <input type="file" name="file{{ $idx+1 }}" accept=".jpg,.jpeg,.pdf" required
                        class="border rounded-lg w-full p-2">
                </div>
            @endforeach
        </div>

        <hr class="my-8">

        {{-- Bagian Link --}}
        <h3 class="text-lg font-semibold mb-3">Link Pembelajaran</h3>
        <div class="mb-4">
            <label class="block font-medium text-gray-700 mb-1">Link YouTube / Google Drive (wajib)</label>
            <input type="url" name="link_youtube" placeholder="https://drive.google.com/..."
                class="border rounded-lg w-full p-2" required>
        </div>

        <div class="mb-6">
            <label class="block font-medium text-gray-700 mb-1">Link Google Meet (opsional)</label>
            <input type="url" name="link_meet" placeholder="https://meet.google.com/..."
                class="border rounded-lg w-full p-2">
        </div>

        <hr class="my-8">

        {{-- Bagian Refleksi --}}
        <h3 class="text-lg font-semibold mb-3">Refleksi Diri</h3>
        @for ($i = 1; $i <= 4; $i++)
            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">
                    Pertanyaan {{ $i }}
                </label>
                <textarea name="jawaban{{ $i }}" rows="3" required
                    class="border rounded-lg w-full p-2"></textarea>
            </div>
        @endfor

        {{-- Tombol Submit --}}
        <div class="mt-8 text-center">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg">
                Kirim Supervisi
            </button>
        </div>
    </form>
</div>
@endsection
