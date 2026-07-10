import * as pdfjsLib from 'pdfjs-dist';
import workerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?url';

pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;

// Listener keyboard aktif; diganti setiap init agar handler modul lama
// tidak menumpuk saat berpindah halaman lewat navigasi Livewire.
let currentKeydownHandler = null;

function initModulReader() {
    const el = document.getElementById('modul-reader');
    if (!el || el.dataset.readerInitialized) return;
    el.dataset.readerInitialized = 'true';

    const pdfUrl = el.dataset.pdfUrl;
    const progressUrl = el.dataset.progressUrl;
    const jumlahHalaman = parseInt(el.dataset.jumlahHalaman, 10);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const canvas = document.getElementById('pdf-canvas');
    const skeleton = document.getElementById('pdf-skeleton');
    const btnPrev = document.getElementById('pdf-prev');
    const btnNext = document.getElementById('pdf-next');
    const pageInput = document.getElementById('pdf-page-input');
    const pageInfo = document.getElementById('page-info');

    let pdfDoc = null;
    let pageNum = Math.min(jumlahHalaman, Math.max(1, parseInt(el.dataset.halamanTerjauh, 10) || 1));
    let rendering = false;
    let pendingPage = null;
    let sendTimer = null;
    let gagalTerkirim = null; // halaman yang gagal dikirim, dicoba ulang diam-diam

    pdfjsLib.getDocument(pdfUrl).promise.then((doc) => {
        pdfDoc = doc;
        skeleton.classList.add('hidden');
        canvas.classList.remove('hidden');
        renderPage(pageNum);
    }).catch(() => {
        skeleton.classList.remove('animate-pulse');
        skeleton.innerHTML = '<p class="p-6 text-sm text-center text-gray-600 dark:text-gray-400">PDF gagal dimuat. Periksa koneksi lalu muat ulang halaman.</p>';
        skeleton.style.aspectRatio = 'auto';
    });

    function renderPage(num) {
        if (rendering) { pendingPage = num; return; }
        rendering = true;
        setNavDisabled(true);

        pdfDoc.getPage(num).then((page) => {
            const containerWidth = canvas.parentElement.clientWidth;
            const viewport = page.getViewport({ scale: 1 });
            const scale = containerWidth / viewport.width;
            const scaled = page.getViewport({ scale: scale * (window.devicePixelRatio || 1) });

            canvas.width = scaled.width;
            canvas.height = scaled.height;

            return page.render({ canvasContext: canvas.getContext('2d'), viewport: scaled }).promise;
        }).then(() => {
            rendering = false;
            pageNum = num;
            updateControls();
            setNavDisabled(false);
            queueProgress(num);
            if (pendingPage !== null) { const p = pendingPage; pendingPage = null; renderPage(p); }
        }).catch(() => {
            rendering = false;
            setNavDisabled(false);
        });
    }

    function setNavDisabled(disabled) {
        btnPrev.disabled = disabled || pageNum <= 1;
        btnNext.disabled = disabled || pageNum >= jumlahHalaman;
    }

    // Debounce 2 detik supaya server tidak dibanjiri saat guru membalik cepat.
    function queueProgress(page) {
        clearTimeout(sendTimer);
        sendTimer = setTimeout(() => sendProgress(page), 2000);
    }

    function sendProgress(page) {
        const halaman = Math.max(page, gagalTerkirim ?? 0);
        fetch(progressUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ halaman }),
        }).then((res) => {
            gagalTerkirim = res.ok ? null : halaman;
        }).catch(() => {
            gagalTerkirim = halaman; // dicoba ulang pada perpindahan halaman berikutnya
        });
    }

    function updateControls() {
        pageInput.value = pageNum;
        const persen = Math.min(100, Math.round(pageNum / jumlahHalaman * 100));
        pageInfo.textContent = `dari ${jumlahHalaman} • ${persen}%`;
    }

    function goTo(num) {
        const target = Math.min(jumlahHalaman, Math.max(1, num));
        if (pdfDoc && target !== pageNum) renderPage(target);
    }

    btnPrev.addEventListener('click', () => goTo(pageNum - 1));
    btnNext.addEventListener('click', () => goTo(pageNum + 1));
    pageInput.addEventListener('change', () => goTo(parseInt(pageInput.value, 10) || 1));
    if (currentKeydownHandler) {
        document.removeEventListener('keydown', currentKeydownHandler);
    }
    currentKeydownHandler = (e) => {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        if (e.key === 'ArrowLeft') goTo(pageNum - 1);
        if (e.key === 'ArrowRight') goTo(pageNum + 1);
    };
    document.addEventListener('keydown', currentKeydownHandler);
}

initModulReader();
// Halaman bisa dimasuki lewat navigasi Livewire (wire:navigate) dari halaman lain.
document.addEventListener('livewire:navigated', initModulReader);
