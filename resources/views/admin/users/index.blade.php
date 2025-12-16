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

<!-- Livewire User Management Component -->
@livewire('admin.user-management')

<!-- Info Card -->
<div class="mt-4 sm:mt-6 mb-6 md:mb-0 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md sm:rounded-lg p-3 sm:p-4">
    <div class="flex">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <h3 class="text-xs sm:text-sm font-semibold text-blue-900 dark:text-blue-300 mb-0.5 sm:mb-1">Informasi</h3>
            <p class="text-[10px] sm:text-xs text-blue-800 dark:text-blue-400 leading-relaxed">
                Anda dapat mengelola semua pengguna sistem dari halaman ini. Filter akan langsung diterapkan saat Anda memilih opsi.
            </p>
        </div>
    </div>
</div>

<script>
    // Handle delete form submission
    function handleDelete(event, userName) {
        event.preventDefault();
        const confirmed = confirm(`Apakah Anda yakin ingin menghapus pengguna ${userName}?\n\nData yang dihapus tidak dapat dikembalikan.`);
        if (confirmed) {
            event.target.submit();
        }
        return false;
    }

    // Reset password
    function resetPassword(userId, userName) {
        if (!confirm(`Apakah Anda yakin ingin mereset password untuk user "${userName}"?\n\nPassword akan direset ke: pass123456`)) {
            return;
        }

        const url = `{{ url('admin/users') }}/${userId}/reset-password`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
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

    // Listen for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('status-updated', (data) => {
            // Optional: Show toast notification
        });
    });
</script>
@endsection
