@extends('layouts.modern')

@section('page-title', 'Kelola Pengguna')

@section('content')
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Kelola Pengguna']
]" />

<!-- Livewire User Management Component -->
@livewire('admin.user-management')

<!-- Info Card -->
<div class="mt-5 mb-8 md:mb-0 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 sm:p-5">
    <div class="flex items-start gap-3 sm:gap-4">
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-sm sm:text-base font-bold text-blue-900 dark:text-blue-300 mb-1">Informasi</h3>
            <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-400 leading-relaxed">
                Anda dapat mengelola semua pengguna sistem dari halaman ini. Filter akan langsung diterapkan saat Anda memilih opsi.
            </p>
        </div>
    </div>
</div>

<script>
    // Handle delete form submission
    function handleDelete(event, userName) {
        event.preventDefault();
        const form = event.target;
        showConfirmModal(
            `Apakah Anda yakin ingin menghapus pengguna ${userName}? Data yang dihapus tidak dapat dikembalikan.`,
            'Konfirmasi Hapus',
            function() {
                form.submit();
            },
            { type: 'danger', confirmText: 'Ya, Hapus' }
        );
        return false;
    }

    // Reset password
    function resetPassword(userId, userName) {
        showConfirmModal(
            `Apakah Anda yakin ingin mereset password untuk user "${userName}"? Password akan direset ke: pass123456`,
            'Konfirmasi Reset Password',
            function() {
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
                        showToast(data.message || 'Password berhasil direset!', 'success');
                    } else {
                        showToast(data.message || 'Gagal mereset password', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat mereset password', 'error');
                });
            },
            { type: 'warning', confirmText: 'Ya, Reset Password' }
        );
    }

    // Listen for Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('status-updated', (data) => {
            // Optional: Show toast notification
        });
    });
</script>
@endsection
