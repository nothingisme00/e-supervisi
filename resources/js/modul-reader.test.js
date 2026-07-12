import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';

const getDocumentMock = vi.fn();

vi.mock('pdfjs-dist', () => ({
    GlobalWorkerOptions: {},
    getDocument: (...args) => getDocumentMock(...args),
}));

vi.mock('pdfjs-dist/build/pdf.worker.min.mjs?url', () => ({ default: 'mock-worker-url' }));

function makeFakePdfDoc(numPages) {
    return {
        numPages,
        getPage: vi.fn((num) => Promise.resolve({
            getViewport: ({ scale }) => ({ width: 100 * scale, height: 141 * scale }),
            render: vi.fn(() => ({ promise: Promise.resolve() })),
        })),
    };
}

function setupDom({ jumlahHalaman = 5, halamanTerjauh = 1 } = {}) {
    document.head.innerHTML = '<meta name="csrf-token" content="test-csrf-token">';
    document.body.innerHTML = `
        <div id="modul-reader"
             data-pdf-url="/modul/1/file"
             data-progress-url="/modul/1/progress"
             data-halaman-terjauh="${halamanTerjauh}"
             data-jumlah-halaman="${jumlahHalaman}">
            <button id="pdf-prev" type="button">&larr;</button>
            <input id="pdf-page-input" type="number" value="${halamanTerjauh}">
            <span id="page-info"></span>
            <span id="progress-saved" class="hidden">Progres tersimpan</span>
            <button id="pdf-next" type="button">&rarr;</button>
            <div class="reader-body">
                <div id="pdf-skeleton" class="animate-pulse"></div>
                <canvas id="pdf-canvas" class="hidden"></canvas>
            </div>
        </div>
    `;
}

// jsdom `document` dipakai bersama antar test dalam satu file; listener yang
// dipasang instance modul lama (akibat vi.resetModules) harus dicabut supaya
// tidak mencemari test berikutnya.
let documentListeners = [];
const originalAddEventListener = document.addEventListener.bind(document);

beforeEach(() => {
    vi.resetModules();
    getDocumentMock.mockReset();
    document.addEventListener = (type, fn, opts) => {
        documentListeners.push([type, fn, opts]);
        return originalAddEventListener(type, fn, opts);
    };
    Object.defineProperty(HTMLElement.prototype, 'clientWidth', { configurable: true, value: 400 });
    HTMLCanvasElement.prototype.getContext = vi.fn(() => ({}));
    vi.stubGlobal('fetch', vi.fn(() => Promise.resolve({ ok: true })));
});

afterEach(() => {
    for (const [type, fn, opts] of documentListeners) {
        document.removeEventListener(type, fn, opts);
    }
    documentListeners = [];
    delete document.addEventListener;
    vi.unstubAllGlobals();
    vi.useRealTimers();
    document.head.innerHTML = '';
    document.body.innerHTML = '';
});

describe('modul-reader', () => {
    it('does nothing when #modul-reader is absent from the DOM', async () => {
        await import('./modul-reader.js');
        expect(getDocumentMock).not.toHaveBeenCalled();
    });

    it('loads the PDF, swaps skeleton for canvas, and renders the furthest page reached', async () => {
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 3 });
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });

        await import('./modul-reader.js');
        await vi.waitFor(() => {
            expect(document.getElementById('pdf-skeleton').classList.contains('hidden')).toBe(true);
        });

        expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false);
        // PDF.js v6 menolak argumen string; getDocument WAJIB dipanggil dengan bentuk objek { url }.
        expect(getDocumentMock).toHaveBeenCalledWith(expect.objectContaining({ url: '/modul/1/file' }));
        expect(fakeDoc.getPage).toHaveBeenCalledWith(3);
        expect(document.getElementById('page-info').textContent).toBe('dari 5 • 60%');
        expect(document.getElementById('pdf-page-input').value).toBe('3');
    });

    it('shows a friendly Indonesian message when the PDF fails to load', async () => {
        setupDom();
        getDocumentMock.mockReturnValue({ promise: Promise.reject(new Error('network down')) });

        await import('./modul-reader.js');
        await vi.waitFor(() => {
            expect(document.getElementById('pdf-skeleton').innerHTML).toContain('PDF gagal dimuat');
        });
        expect(document.getElementById('pdf-skeleton').classList.contains('animate-pulse')).toBe(false);
    });

    it('advances the page on next click and sends debounced progress with CSRF header', async () => {
        vi.useFakeTimers();
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });

        await import('./modul-reader.js');
        await vi.waitFor(() => {
            expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false);
        });

        document.getElementById('pdf-next').click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%'));

        expect(fetch).not.toHaveBeenCalled();
        await vi.advanceTimersByTimeAsync(2000);

        expect(fetch).toHaveBeenCalledWith('/modul/1/progress', expect.objectContaining({
            method: 'POST',
            headers: expect.objectContaining({
                'X-CSRF-TOKEN': 'test-csrf-token',
                'Content-Type': 'application/json',
            }),
            body: JSON.stringify({ halaman: 2 }),
        }));
    });

    it('retries a failed progress send silently on the next page turn', async () => {
        vi.useFakeTimers();
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });
        fetch.mockResolvedValueOnce({ ok: false }).mockResolvedValueOnce({ ok: true });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));

        document.getElementById('pdf-next').click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%'));
        await vi.advanceTimersByTimeAsync(2000);
        await vi.waitFor(() => expect(fetch).toHaveBeenCalledTimes(1));

        document.getElementById('pdf-next').click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 60%'));
        await vi.advanceTimersByTimeAsync(2000);
        await vi.waitFor(() => expect(fetch).toHaveBeenCalledTimes(2));

        expect(fetch).toHaveBeenLastCalledWith('/modul/1/progress', expect.objectContaining({
            body: JSON.stringify({ halaman: 3 }),
        }));
    });

    it('does not regress reported progress when retrying after navigating backward past a failed send', async () => {
        vi.useFakeTimers();
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });
        fetch.mockResolvedValueOnce({ ok: false }).mockResolvedValueOnce({ ok: true });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));

        // Maju ke halaman 5 dulu (klik next 4x), pengiriman progresnya gagal.
        const btnNext = document.getElementById('pdf-next');
        btnNext.click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%'));
        btnNext.click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 60%'));
        btnNext.click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 80%'));
        btnNext.click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 100%'));
        await vi.advanceTimersByTimeAsync(2000);
        await vi.waitFor(() => expect(fetch).toHaveBeenCalledTimes(1));

        // Lalu mundur baca ulang ke halaman 3 — progres yang dilaporkan tidak boleh turun dari 5.
        const btnPrev = document.getElementById('pdf-prev');
        btnPrev.click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 80%'));
        btnPrev.click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 60%'));
        await vi.advanceTimersByTimeAsync(2000);
        await vi.waitFor(() => expect(fetch).toHaveBeenCalledTimes(2));

        expect(fetch).toHaveBeenLastCalledWith('/modul/1/progress', expect.objectContaining({
            body: JSON.stringify({ halaman: 5 }),
        }));
    });

    it('disables navigation while a page is rendering and re-enables once it resolves', async () => {
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 2 });
        let releaseSecondPage;
        let callCount = 0;
        const fakeDoc = {
            numPages: 5,
            getPage: vi.fn(() => {
                callCount += 1;
                const page = {
                    getViewport: ({ scale }) => ({ width: 100 * scale, height: 141 * scale }),
                    render: vi.fn(() => ({ promise: Promise.resolve() })),
                };
                if (callCount === 1) return Promise.resolve(page);
                return new Promise((resolve) => { releaseSecondPage = () => resolve(page); });
            }),
        };
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));

        const btnNext = document.getElementById('pdf-next');
        btnNext.click();
        expect(btnNext.disabled).toBe(true);

        releaseSecondPage();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 60%'));
        expect(btnNext.disabled).toBe(false);
    });

    it('jumps to a typed page number on input change, clamped to the valid range', async () => {
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));

        const input = document.getElementById('pdf-page-input');
        input.value = '999';
        input.dispatchEvent(new Event('change'));
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 100%'));
        expect(input.value).toBe('5');
    });

    it('navigates with ArrowRight/ArrowLeft but ignores them while focus is in an input', async () => {
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 2 });
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));

        // Tombol panah saat fokus di input tidak boleh memindah halaman.
        const input = document.getElementById('pdf-page-input');
        input.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowRight', bubbles: true }));
        await new Promise((r) => setTimeout(r, 20));
        expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%');

        // Di luar input, panah kanan maju satu halaman.
        document.body.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowRight', bubbles: true }));
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 60%'));

        // Panah kiri mundur satu halaman.
        document.body.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowLeft', bubbles: true }));
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%'));
    });

    it('re-initializes on livewire:navigated for fresh DOM but not for an already-initialized one', async () => {
        setupDom();
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(getDocumentMock).toHaveBeenCalledTimes(1));
        expect(document.getElementById('modul-reader').dataset.readerInitialized).toBe('true');

        // Event pada DOM yang sama tidak boleh init dua kali.
        document.dispatchEvent(new Event('livewire:navigated'));
        expect(getDocumentMock).toHaveBeenCalledTimes(1);

        // DOM baru hasil navigasi Livewire harus di-init lagi.
        setupDom();
        document.dispatchEvent(new Event('livewire:navigated'));
        await vi.waitFor(() => expect(getDocumentMock).toHaveBeenCalledTimes(2));
    });

    it('recovers from a failed page render so navigation keeps working', async () => {
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        let callCount = 0;
        const goodPage = {
            getViewport: ({ scale }) => ({ width: 100 * scale, height: 141 * scale }),
            render: vi.fn(() => ({ promise: Promise.resolve() })),
        };
        const fakeDoc = {
            numPages: 5,
            getPage: vi.fn(() => {
                callCount += 1;
                if (callCount === 2) return Promise.reject(new Error('render pecah'));
                return Promise.resolve(goodPage);
            }),
        };
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 20%'));

        // Percobaan kedua gagal — halaman tidak berpindah.
        const btnNext = document.getElementById('pdf-next');
        btnNext.click();
        await vi.waitFor(() => expect(fakeDoc.getPage).toHaveBeenCalledTimes(2));
        expect(document.getElementById('page-info').textContent).toBe('dari 5 • 20%');

        // Navigasi berikutnya tetap berfungsi (tidak macet di flag rendering).
        await vi.waitFor(() => expect(btnNext.disabled).toBe(false));
        btnNext.click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%'));
    });

    it('does not let a stale keydown listener from a previous modul render or send progress after livewire re-init', async () => {
        vi.useFakeTimers();

        // Modul A.
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        const docA = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(docA) });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));
        // Biarkan kiriman progres sah dari render awal modul A selesai, lalu bersihkan catatan fetch.
        await vi.advanceTimersByTimeAsync(2000);
        fetch.mockClear();
        const getPageCallsA = docA.getPage.mock.calls.length;

        // Navigasi Livewire ke modul B: DOM diganti elemen segar dengan endpoint progres berbeda.
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        document.getElementById('modul-reader').dataset.progressUrl = '/modul/2/progress';
        const docB = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(docB) });
        document.dispatchEvent(new Event('livewire:navigated'));
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));

        // Panah kanan hanya boleh menggerakkan modul B — bukan handler basi milik A.
        document.body.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowRight', bubbles: true }));
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%'));
        await vi.advanceTimersByTimeAsync(2000);

        expect(docA.getPage.mock.calls.length).toBe(getPageCallsA);
        expect(fetch.mock.calls.length).toBeGreaterThan(0);
        for (const call of fetch.mock.calls) {
            expect(call[0]).toBe('/modul/2/progress');
        }
    });

    it('shows a brief "Progres tersimpan" affordance after progress is saved successfully', async () => {
        vi.useFakeTimers();
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));

        const saved = document.getElementById('progress-saved');
        expect(saved.classList.contains('hidden')).toBe(true);

        document.getElementById('pdf-next').click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%'));
        await vi.advanceTimersByTimeAsync(2000);

        // Muncul sesaat setelah kiriman progres sukses...
        await vi.waitFor(() => expect(saved.classList.contains('hidden')).toBe(false));

        // ...lalu menyembunyikan diri lagi setelah jeda singkat.
        await vi.advanceTimersByTimeAsync(1500);
        expect(saved.classList.contains('hidden')).toBe(true);
    });

    it('keeps the "Progres tersimpan" affordance hidden when the progress send fails', async () => {
        vi.useFakeTimers();
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        const fakeDoc = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(fakeDoc) });
        fetch.mockResolvedValue({ ok: false });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));

        document.getElementById('pdf-next').click();
        await vi.waitFor(() => expect(document.getElementById('page-info').textContent).toBe('dari 5 • 40%'));
        await vi.advanceTimersByTimeAsync(2000);
        await vi.waitFor(() => expect(fetch).toHaveBeenCalledTimes(1));
        await vi.advanceTimersByTimeAsync(50);

        expect(document.getElementById('progress-saved').classList.contains('hidden')).toBe(true);
    });

    it('detaches the keydown listener when navigating from a reader page to a non-reader page', async () => {
        vi.useFakeTimers();

        // Modul A.
        setupDom({ jumlahHalaman: 5, halamanTerjauh: 1 });
        const docA = makeFakePdfDoc(5);
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(docA) });

        await import('./modul-reader.js');
        await vi.waitFor(() => expect(document.getElementById('pdf-canvas').classList.contains('hidden')).toBe(false));
        // Biarkan kiriman progres sah dari render awal modul A selesai, lalu bersihkan catatan fetch.
        await vi.advanceTimersByTimeAsync(2000);
        fetch.mockClear();
        const getPageCallsA = docA.getPage.mock.calls.length;

        // Navigasi Livewire ke halaman TANPA #modul-reader (mis. daftar modul).
        document.body.innerHTML = '<div id="halaman-lain">Bukan pembaca modul</div>';
        document.dispatchEvent(new Event('livewire:navigated'));

        // Panah kanan tidak boleh lagi menggerakkan modul A yang sudah lepas dari DOM.
        document.body.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowRight', bubbles: true }));
        await vi.advanceTimersByTimeAsync(2000);

        expect(docA.getPage.mock.calls.length).toBe(getPageCallsA);
        expect(fetch).not.toHaveBeenCalled();
    });
});
