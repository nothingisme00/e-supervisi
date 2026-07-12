<a href="{{ route('notifikasi.buka', $n->id) }}"
   class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $n->read_at ? '' : 'bg-primary-50/60 dark:bg-primary-900/10' }}">
    <span class="mt-0.5 shrink-0">
        @include('notifikasi._icon', ['ikon' => $n->data['ikon'] ?? 'default'])
    </span>
    <span class="flex-1 min-w-0">
        <span class="block text-sm {{ $n->read_at ? 'font-medium text-gray-600 dark:text-gray-400' : 'font-semibold text-gray-900 dark:text-white' }}">{{ $n->data['judul'] ?? 'Notifikasi' }}</span>
        <span class="block text-xs text-gray-600 dark:text-gray-400">{{ $n->data['pesan'] ?? '' }}</span>
        <span class="block text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $n->created_at->diffForHumans() }}</span>
    </span>
    @unless($n->read_at)
        <span class="mt-1 shrink-0 w-2 h-2 rounded-full bg-primary-500" aria-label="Belum dibaca"></span>
    @endunless
</a>
