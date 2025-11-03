@extends('layouts.modern')

@section('page-title', 'Kelola Pengguna')

@section('content')
<!-- Header dengan Tombol Tambah -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Pengguna</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola data pengguna sistem E-Supervisi</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Pengguna
    </a>
</div>

<!-- Filter & Search -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}">
        <!-- Preserve sorting parameters -->
        <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
        <input type="hidden" name="sort_direction" value="{{ request('sort_direction') }}">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Search -->
            <div class="md:col-span-12 lg:col-span-4">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari NIK, nama, atau email..."
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            <!-- Role Filter -->
            <div class="md:col-span-6 lg:col-span-2">
                <div class="relative">
                    <select name="role" class="w-full px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="kepala_sekolah" {{ request('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tingkat Filter -->
            <div class="md:col-span-6 lg:col-span-2">
                <div class="relative">
                    <select name="tingkat" class="w-full px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all appearance-none cursor-pointer">
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
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-6 lg:col-span-2">
                <div class="relative">
                    <select name="status" class="w-full px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:border-indigo-400 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-6 lg:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    Filter
                </button>
                @if(request('search') || request('role') || request('tingkat') || request('status'))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Tabel Pengguna -->
<div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <tr>
                    <!-- Sortable NIK Column -->
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'nik', 'sort_direction' => ($sortBy === 'nik' && $sortDirection === 'asc') ? 'desc' : 'asc'])) }}"
                           class="group inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span>Nomor Induk Kependudukan (NIK)</span>
                            @if($sortBy === 'nik')
                                @if($sortDirection === 'asc')
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-600 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            @endif
                        </a>
                    </th>

                    <!-- Sortable Nama Column -->
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        <a href="{{ route('admin.users.index', array_merge(request()->except(['sort_by', 'sort_direction']), ['sort_by' => 'name', 'sort_direction' => ($sortBy === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc'])) }}"
                           class="group inline-flex items-center gap-1 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span>Nama</span>
                            @if($sortBy === 'name')
                                @if($sortDirection === 'asc')
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-600 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            @endif
                        </a>
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tingkat</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300 font-mono">{{ $user->nik }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-600 dark:bg-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0 mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                @if($user->mata_pelajaran)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->mata_pelajaran }} • {{ $user->tingkat }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->tingkat)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                {{ $user->tingkat }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-600 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role == 'admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                Admin
                            </span>
                        @elseif($user->role == 'guru')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                Guru
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                Kepala Sekolah
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="toggleStatus({{ $user->id }}, this)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all {{ $user->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/50' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/50' }}" title="Klik untuk ubah status">
                            <span class="w-1.5 h-1.5 {{ $user->is_active ? 'bg-green-500 dark:bg-green-400' : 'bg-red-500 dark:bg-red-400' }} rounded-full mr-1.5"></span>
                            <span class="status-text">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </button>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white text-xs font-medium rounded-lg transition-colors"
                               title="Edit user">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <button onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors"
                                    title="Reset password">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                Reset
                            </button>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return handleDelete(event, '{{ $user->name }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors"
                                        title="Hapus user">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            @if(request('search') || request('role') || request('tingkat') || request('status'))
                                Tidak ada pengguna yang sesuai dengan filter
                            @else
                                Belum ada pengguna terdaftar
                            @endif
                        </p>
                        @if(request('search') || request('role') || request('tingkat') || request('status'))
                            <a href="{{ route('admin.users.index') }}" class="inline-block mt-3 text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:text-indigo-700 dark:hover:text-indigo-300">
                                Reset filter
                            </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Info Card -->
<div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <div class="flex">
        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">Informasi</h3>
            <p class="text-xs text-blue-800 dark:text-blue-400 leading-relaxed">
                Anda dapat mengelola semua pengguna sistem dari halaman ini. Gunakan filter untuk mempermudah pencarian pengguna berdasarkan role atau kata kunci tertentu.
            </p>
        </div>
    </div>
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

    // Handle delete form submission
    async function handleDelete(event, userName) {
        event.preventDefault();
        const confirmed = await confirmModal(
            `Apakah Anda yakin ingin menghapus pengguna ${userName}? Data yang dihapus tidak dapat dikembalikan.`,
            'Konfirmasi Hapus User'
        );
        if (confirmed) {
            event.target.submit();
        }
        return false;
    }

    // Toggle user status
    async function toggleStatus(userId, button) {
        const confirmed = await confirmModal('Apakah Anda yakin ingin mengubah status user ini?', 'Konfirmasi Ubah Status');
        if (!confirmed) {
            return;
        }

        // Disable button during request
        button.disabled = true;
        button.classList.add('opacity-70');

        fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button appearance
                const statusDot = button.querySelector('span:first-child');
                const statusText = button.querySelector('.status-text');

                if (data.is_active) {
                    button.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/50';
                    statusDot.className = 'w-1.5 h-1.5 bg-green-500 dark:bg-green-400 rounded-full mr-1.5';
                    statusText.textContent = 'Aktif';
                } else {
                    button.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-900/50';
                    statusDot.className = 'w-1.5 h-1.5 bg-red-500 dark:bg-red-400 rounded-full mr-1.5';
                    statusText.textContent = 'Nonaktif';
                }

                button.disabled = false;
                button.classList.remove('opacity-70');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal('Gagal Mengubah Status', 'Terjadi kesalahan saat mengubah status user. Silakan coba lagi.', 'error');
            button.disabled = false;
            button.classList.remove('opacity-70');
        });
    }

    // Reset password
    async function resetPassword(userId, userName) {
        const confirmed = await confirmModal(`Apakah Anda yakin ingin mereset password untuk user "${userName}"?\n\nPassword akan direset ke: pass123456`, 'Konfirmasi Reset Password');
        if (!confirmed) {
            return;
        }

        fetch(`/admin/users/${userId}/reset-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showModal('Password Berhasil Direset', data.message, 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal('Gagal Reset Password', 'Terjadi kesalahan saat mereset password. Silakan coba lagi.', 'error');
        });
    }
</script>
@endsection
