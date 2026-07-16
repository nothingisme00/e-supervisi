{{--
    Thread diskusi & feedback supervisi — satu sumber untuk tiga halaman:
    guru/supervisi/detail, guru/supervisi/view (rekan sejawat), kepala/evaluasi/show.

    Aksen kiri kartu: merah = permintaan revisi, biru = komentar milik sendiri,
    amber = komentar pihak lain (selaras makna warna status).

    Props:
        - feedbacks (Collection<Feedback>, wajib): relasi user + replies.user ter-eager-load.
        - supervisi (Supervisi, wajib): induk thread.
        - action (string, wajib bila readonly=false): URL POST form balasan (route
          guru.supervisi.comment atau kepala.evaluasi.feedback) — nama field
          komentar/parent_id sama di keduanya. Boleh null saat readonly (admin).
        - readonly (bool, default false): sembunyikan tombol Balas + form reply
          (dipakai kepala saat status completed; view rekan sejawat TETAP false karena
          backend mengizinkan komentar rekan).
        - minlength (int|null, default null): minlength textarea balasan (kepala pakai 10).
        - revisionNoteTitle / revisionNote (string|null): kotak catatan di bawah komentar
          permintaan revisi; tidak dirender bila revisionNote kosong.

    Butuh fungsi global toggleReplyForm(id) dari halaman pemanggil.
--}}
@props([
    'feedbacks',
    'supervisi',
    'action' => null,
    'readonly' => false,
    'minlength' => null,
    'revisionNoteTitle' => null,
    'revisionNote' => null,
])

<div class="space-y-2 sm:space-y-3 max-h-80 sm:max-h-96 overflow-y-auto mb-3 sm:mb-4">
@if($feedbacks && $feedbacks->count() > 0)
    @foreach($feedbacks->whereNull('parent_id')->sortByDesc('created_at') as $fb)
    <div class="border-l-4 {{ $fb->is_revision_request ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : ($fb->user_id == auth()->id() ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-amber-500 bg-amber-50 dark:bg-amber-900/20') }} rounded-r-lg p-3 sm:p-4">
        <div class="flex items-start gap-2 sm:gap-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 {{ $fb->user_id == auth()->id() ? 'bg-primary-600' : 'bg-amber-500' }} rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-bold shrink-0">
                {{ strtoupper(substr($fb->user->name ?? 'U', 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $fb->user->name ?? 'User' }}</span>

                        @if(($fb->user->role ?? null) === 'kepala_sekolah')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">
                                <x-icon name="star" class="w-3 h-3" />
                                Kepala Sekolah
                            </span>
                        @elseif($fb->user_id == auth()->id())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Anda</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">Guru</span>
                        @endif

                        @if($fb->is_revision_request)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                <x-icon name="exclamation-triangle" class="w-3 h-3" />
                                Revisi Diminta
                            </span>
                        @endif

                        @if($fb->sudah_direvisi)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                <x-icon name="check" class="w-3 h-3" />
                                Sudah Direvisi
                            </span>
                        @endif
                    </div>
                    <div class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <x-icon name="clock" class="w-3.5 h-3.5" />
                        {{ $fb->created_at->diffForHumans() }}
                    </div>
                </div>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $fb->komentar }}</p>

                @if($fb->is_revision_request && $revisionNote)
                    <div class="mt-3 p-3 bg-red-100 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-800/50">
                        <p class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-800 dark:text-red-300 mb-1">
                            <x-icon name="exclamation-triangle" class="w-3.5 h-3.5" />
                            {{ $revisionNoteTitle ?? 'Tindakan Diperlukan' }}
                        </p>
                        <p class="text-xs text-red-700 dark:text-red-400">{{ $revisionNote }}</p>
                    </div>
                @endif

                @unless($readonly)
                <!-- Tombol Balas -->
                <div class="mt-3">
                    <button onclick="toggleReplyForm({{ $fb->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors cursor-pointer">
                        <x-icon name="arrow-uturn-left" class="w-3.5 h-3.5" />
                        Balas
                    </button>
                </div>

                <!-- Form Balas -->
                <div id="reply-form-{{ $fb->id }}" class="hidden mt-3 pl-4 border-l-2 border-primary-200 dark:border-primary-800">
                    <form action="{{ $action }}" method="POST" class="space-y-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $fb->id }}">
                        <textarea
                            name="komentar"
                            rows="2"
                            required
                            @if($minlength) minlength="{{ $minlength }}" @endif
                            class="form-control resize-none"
                            placeholder="Tulis balasan Anda..."></textarea>
                        <div class="flex gap-2">
                            <x-button type="submit" size="sm">
                                <x-icon name="paper-airplane" class="w-3.5 h-3.5" />
                                Kirim
                            </x-button>
                            <x-button type="button" variant="ghost" size="sm" onclick="toggleReplyForm({{ $fb->id }})">
                                Batal
                            </x-button>
                        </div>
                    </form>
                </div>
                @endunless

                <!-- Balasan Nested -->
                @if($fb->replies && $fb->replies->count() > 0)
                    <div class="mt-4 ml-6 space-y-3 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                        @foreach($fb->replies as $reply)
                        <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3">
                            <div class="flex items-start gap-2">
                                <div class="w-8 h-8 {{ $reply->user_id == auth()->id() ? 'bg-blue-500' : 'bg-gray-500' }} rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($reply->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $reply->user->name ?? 'User' }}</span>

                                        @if(($reply->user->role ?? null) === 'kepala_sekolah')
                                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/40 dark:text-primary-300">
                                                <x-icon name="star" class="w-2.5 h-2.5" />
                                                Kepsek
                                            </span>
                                        @elseif($reply->user_id == auth()->id())
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Anda</span>
                                        @endif

                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-gray-700 dark:text-gray-300">{{ $reply->komentar }}</p>

                                    @unless($readonly)
                                    <!-- Balas balasan -->
                                    <div class="mt-2">
                                        <button onclick="toggleReplyForm({{ $reply->id }})" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 cursor-pointer">
                                            <x-icon name="arrow-uturn-left" class="w-3 h-3" />
                                            Balas
                                        </button>
                                    </div>

                                    <div id="reply-form-{{ $reply->id }}" class="hidden mt-2">
                                        <form action="{{ $action }}" method="POST" class="space-y-2">
                                            @csrf
                                            {{-- balasan atas balasan tetap menempel ke induk thread ($fb) --}}
                                            <input type="hidden" name="parent_id" value="{{ $fb->id }}">
                                            <textarea
                                                name="komentar"
                                                rows="2"
                                                required
                                                @if($minlength) minlength="{{ $minlength }}" @endif
                                                class="form-control resize-none"
                                                placeholder="Tulis balasan Anda..."></textarea>
                                            <div class="flex gap-2">
                                                <x-button type="submit" size="sm">Kirim</x-button>
                                                <x-button type="button" variant="ghost" size="sm" onclick="toggleReplyForm({{ $reply->id }})">Batal</x-button>
                                            </div>
                                        </form>
                                    </div>
                                    @endunless
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
@else
    <x-empty-state
        icon="chat-bubble"
        title="Belum ada komentar"
        description="Diskusi akan tampil di sini setelah ada komentar"
        :compact="true"
    />
@endif
</div>
