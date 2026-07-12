{{--
    Kartu timeline supervisi (dipakai guru.home, milik sendiri maupun rekan sejawat).

    Props:
        - supervisi (Supervisi, wajib): item timeline dengan relasi user, dokumenEvaluasi,
          prosesPembelajaran, feedback (+ feedback.user, feedback.replies.user) sudah di-eager-load.
        - milikSendiri (bool, wajib): true jika supervisi.user_id === auth()->id() — mengatur
          badge "Saya" di header dan footer aksi (hapus/lanjutkan/revisi/detail vs lihat saja).
--}}
@props(['supervisi', 'milikSendiri'])

<x-card flush class="hover:border-primary-300 dark:hover:border-primary-700 transition-colors duration-200">
    <!-- Header Card -->
    <div class="p-3 sm:p-3 md:p-4 bg-primary-50/80 dark:bg-primary-900/20">
        <div class="flex items-start justify-between gap-3 sm:gap-2.5 md:gap-3">
            <div class="flex items-center gap-3 sm:gap-2.5 md:gap-3 flex-1 min-w-0">
                <div class="shrink-0">
                    <div class="w-11 h-11 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-base sm:text-base md:text-lg shadow-md ring-2 ring-white dark:ring-gray-800">
                        {{ strtoupper(substr($supervisi->user->name, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 sm:gap-2 flex-wrap">
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base sm:text-base truncate">
                            {{ $supervisi->user->name }}
                        </h4>
                        @if($milikSendiri)
                            <span class="inline-flex items-center gap-1 sm:gap-1 px-2 py-0.5 sm:px-2 bg-primary-600 dark:bg-primary-500 text-white text-xs sm:text-xs font-medium rounded-full">
                                <x-icon name="check-circle" class="w-3.5 h-3.5" />
                                Saya
                            </span>
                        @endif
                    </div>
                    @if($supervisi->user && ($supervisi->user->mata_pelajaran || $supervisi->user->tingkat))
                    <p class="text-sm sm:text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1.5 sm:gap-1.5 mt-0.5">
                        @if($supervisi->user->mata_pelajaran)
                        <span class="inline-flex items-center gap-1">
                            <x-icon name="book-open" class="w-4 h-4" />
                            {{ $supervisi->user->mata_pelajaran }}
                        </span>
                        @endif
                        @if($supervisi->user->mata_pelajaran && $supervisi->user->tingkat)
                        <span class="text-gray-400">•</span>
                        @endif
                        @if($supervisi->user->tingkat)
                        <span class="inline-flex items-center gap-1">
                            <x-icon name="users" class="w-3.5 h-3.5" />
                            {{ $supervisi->user->tingkat }}
                        </span>
                        @endif
                    </p>
                    @endif
                    <p class="text-xs sm:text-xs text-gray-500 dark:text-gray-500 mt-1">
                        <x-icon name="calendar" class="w-3.5 h-3.5 inline mr-1" />
                        {{ \Carbon\Carbon::parse($supervisi->tanggal_supervisi)->translatedFormat('d M Y') }}
                    </p>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="shrink-0">
                <x-status-badge :status="$supervisi->status" />
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="px-3 py-2.5 sm:px-3 sm:py-2.5 md:px-4 md:py-3 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
        <!-- Info Cards (Dokumen & Proses) -->
        <div class="flex items-center gap-2 sm:gap-2 md:gap-3 flex-wrap mb-3 sm:mb-2.5 md:mb-3">
            @php
                $docCount = $supervisi->dokumenEvaluasi->count();
                $hasProses = $supervisi->prosesPembelajaran != null;
            @endphp

            <div class="flex items-center gap-1.5 sm:gap-1.5 md:gap-2 px-2.5 py-1.5 sm:px-2.5 sm:py-1.5 md:px-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg md:rounded-lg border border-primary-100 dark:border-primary-800">
                <x-icon name="document" class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-primary-600 dark:text-primary-400" />
                <span class="text-xs sm:text-xs font-semibold text-gray-700 dark:text-gray-300">Dokumen: <span class="{{ $docCount == 7 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">{{ $docCount }}/7</span></span>
            </div>

            @if($hasProses)
                <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2 px-2 py-1 sm:px-2.5 sm:py-1.5 md:px-3 bg-green-50 dark:bg-green-900/20 rounded-lg md:rounded-lg border border-green-100 dark:border-green-800">
                    <x-icon name="check" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-green-600 dark:text-green-400" />
                    <span class="text-[10px] sm:text-xs font-semibold text-green-700 dark:text-green-300">Proses Selesai</span>
                </div>
            @else
                <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2 px-2 py-1 sm:px-2.5 sm:py-1.5 md:px-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg md:rounded-lg border border-gray-200 dark:border-gray-600">
                    <x-icon name="clock" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-gray-400" />
                    <span class="text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400">Proses Belum</span>
                </div>
            @endif

            <x-video-praktik-badge :supervisi="$supervisi" />

            @if($supervisi->feedback->count() > 0)
                <div class="flex items-center gap-1 sm:gap-1.5 md:gap-2 px-2 py-1 sm:px-2.5 sm:py-1.5 md:px-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg md:rounded-lg border border-primary-100 dark:border-primary-800">
                    <x-icon name="chat-bubble" class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-primary-600 dark:text-primary-400" />
                    <span class="text-[10px] sm:text-xs font-semibold text-primary-700 dark:text-primary-300">{{ $supervisi->feedback->count() }} Feedback</span>
                </div>
            @endif
        </div>

        <!-- Komentar Terbaru (hanya tampil jika sudah submit) - Accordion -->
        @if($supervisi->status !== 'draft')
        <div class="mb-3">
            <button type="button"
                    onclick="toggleComments('{{ $supervisi->id }}')"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-slate-50 dark:bg-gray-900/30 hover:bg-slate-100 dark:hover:bg-gray-900/50 rounded-lg border border-slate-200 dark:border-gray-700 transition-colors">
                <div class="flex items-center gap-2">
                    <x-icon name="chat-bubble" class="w-4 h-4 text-slate-500 dark:text-gray-400" />
                    <span class="text-xs font-semibold text-slate-600 dark:text-gray-400">
                        {{ $supervisi->feedback ? count($supervisi->feedback) : 0 }} Komentar
                    </span>
                </div>
                <x-icon name="chevron-down" id="chevron-{{ $supervisi->id }}" class="w-4 h-4 text-slate-500 dark:text-gray-400 transform transition-transform duration-300 ease-in-out" />
            </button>
            <div id="comments-{{ $supervisi->id }}" class="overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0; opacity: 0;">
                <div class="mt-2 space-y-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                    @if($supervisi->feedback && count($supervisi->feedback) > 0)
                        @php
                            $parentComments = $supervisi->feedback->whereNull('parent_id')->sortByDesc('created_at');
                        @endphp
                        @foreach($parentComments as $fb)
                        <div class="bg-slate-50 dark:bg-gray-900/50 rounded-lg p-2.5 border border-slate-200 dark:border-gray-700">
                            <div class="flex items-start gap-2">
                                <div class="w-7 h-7 rounded-full bg-primary-600 flex items-center justify-center text-white font-semibold text-xs shrink-0">
                                    {{ strtoupper(substr($fb->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5 mb-1 flex-wrap">
                                        <p class="text-xs font-semibold text-slate-700 dark:text-gray-300">{{ $fb->user->name ?? 'User' }}</p>
                                        @if($fb->user && $fb->user->role === 'kepala_sekolah')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">
                                            <x-icon name="star" class="w-2.5 h-2.5 mr-0.5" />
                                            Kepsek
                                        </span>
                                        @elseif($fb->user_id == auth()->id())
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                            Anda
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-gray-400">{{ $fb->komentar }}</p>

                                    <!-- Nested Replies -->
                                    @if($fb->replies && $fb->replies->count() > 0)
                                        <div class="mt-2 ml-4 space-y-2 pl-2 border-l-2 border-slate-200 dark:border-gray-700">
                                            @foreach($fb->replies->take(2) as $reply)
                                            <div class="bg-white dark:bg-gray-800/50 rounded p-2">
                                                <div class="flex items-start gap-1.5">
                                                    <div class="w-5 h-5 rounded-full bg-gray-500 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                                                        {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-1 mb-0.5">
                                                            <span class="text-xs font-semibold text-slate-700 dark:text-gray-300">{{ $reply->user->name ?? 'User' }}</span>
                                                            @if($reply->user_id == auth()->id())
                                                            <span class="inline-flex items-center px-1 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                                                Anda
                                                            </span>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs text-slate-600 dark:text-gray-400">{{ $reply->komentar }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @if($fb->replies->count() > 2)
                                            <p class="text-xs text-slate-500 dark:text-gray-400 italic pl-2">+{{ $fb->replies->count() - 2 }} balasan lainnya...</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <p class="text-xs text-slate-500 dark:text-gray-400">Belum ada komentar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Footer -->
    <div class="px-3 py-2.5 sm:px-3 sm:py-2.5 md:px-4 md:py-3 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2.5 sm:gap-2.5 md:gap-3">
        <div class="flex items-center gap-2 sm:gap-2">
            @if($milikSendiri)
                {{-- Only show delete button for draft status, not for revision --}}
                @if($supervisi->status == 'draft')
                    <form id="delete-supervisi-{{ $supervisi->id }}" method="POST" action="{{ route('guru.supervisi.delete', $supervisi->id) }}" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <x-button type="button" variant="danger" size="sm" onclick="confirmDeleteSupervisi({{ $supervisi->id }})" title="Hapus supervisi">
                            <x-icon name="trash" class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" />
                            <span class="hidden sm:inline">Hapus</span>
                        </x-button>
                    </form>
                @endif

                @if($supervisi->status == 'draft')
                    <x-button href="{{ route('guru.supervisi.continue', $supervisi->id) }}" variant="primary" size="sm">
                        <x-icon name="arrow-right" class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" />
                        Lanjutkan
                    </x-button>
                @elseif($supervisi->status == 'revision')
                    {{-- Button to edit/revise supervisi --}}
                    <x-button href="{{ route('guru.supervisi.continue', $supervisi->id) }}" variant="primary" size="sm">
                        <x-icon name="pencil" class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" />
                        <span class="hidden sm:inline">Revisi</span>
                        <span class="sm:hidden">Edit</span>
                    </x-button>
                @else
                    <x-button href="{{ route('guru.supervisi.detail', $supervisi->id) }}" variant="secondary" size="sm">
                        <x-icon name="eye" class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" />
                        Detail
                    </x-button>
                @endif
            @else
                <x-button href="{{ route('guru.supervisi.view', $supervisi->id) }}" variant="secondary" size="sm">
                    <x-icon name="eye" class="w-4 h-4 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" />
                    Lihat
                </x-button>
            @endif
        </div>
    </div>
</x-card>
