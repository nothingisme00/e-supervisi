{{-- Link Notifikasi sidebar (dipakai semua peran, posisi #2 setelah Beranda/Dashboard). --}}
<a href="{{ route('notifikasi.index') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 min-h-11 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('notifikasi.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
    <x-icon name="bell" class="w-5 h-5 flex-shrink-0" />
    <span class="flex-1">Notifikasi</span>
    @if(($unreadNotifCount ?? 0) > 0)
        <span class="min-w-[20px] h-5 px-1.5 rounded-full bg-red-600 text-white text-[10px] font-bold flex items-center justify-center">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span>
    @elseif(request()->routeIs('notifikasi.*'))
        <div class="w-1.5 h-1.5 bg-primary-600 dark:bg-primary-400 rounded-full"></div>
    @endif
</a>
