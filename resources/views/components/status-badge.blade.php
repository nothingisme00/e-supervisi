@props(['status'])
@php
    // R9: label + warna baku status supervisi untuk semua layar & role
    $map = [
        'draft' => ['Draft', 'bg-gray-100 text-gray-700 dark:bg-gray-700/40 dark:text-gray-300'],
        'submitted' => ['Disubmit', 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'],
        'under_review' => ['Ditinjau', 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'],
        'revision' => ['Revisi', 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'],
        'completed' => ['Selesai', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
        'aktif' => ['Aktif', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'],
        'nonaktif' => ['Nonaktif', 'bg-gray-100 text-gray-700 dark:bg-gray-700/40 dark:text-gray-300'],
    ];
    [$label, $classes] = $map[$status] ?? [ucfirst($status), 'bg-gray-100 text-gray-700 dark:bg-gray-700/40 dark:text-gray-300'];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ' . $classes]) }}>{{ $label }}</span>
