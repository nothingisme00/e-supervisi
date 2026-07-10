<a href="{{ route('notifikasi.buka', $n->id) }}"
   class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $n->read_at ? '' : 'bg-primary-50/60 dark:bg-primary-900/10' }}">
    <span class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
    </span>
    <span class="flex-1 min-w-0">
        <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $n->data['judul'] ?? 'Notifikasi' }}</span>
        <span class="block text-xs text-gray-600 dark:text-gray-400">{{ $n->data['pesan'] ?? '' }}</span>
        <span class="block text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $n->created_at->diffForHumans() }}</span>
    </span>
    @unless($n->read_at)
        <span class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-primary-500" aria-label="Belum dibaca"></span>
    @endunless
</a>
