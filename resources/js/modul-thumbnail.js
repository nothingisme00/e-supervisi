import * as pdfjsLib from 'pdfjs-dist';
import workerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?url';

pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;

// Lebar sampul yang cukup tajam untuk kartu namun kecil untuk diunggah.
const TARGET_WIDTH = 640;

/** Render halaman 1 PDF terpilih menjadi blob JPEG. */
async function renderCover(file) {
    const data = await file.arrayBuffer();
    const doc = await pdfjsLib.getDocument({ data }).promise;
    try {
        const page = await doc.getPage(1);
        const base = page.getViewport({ scale: 1 });
        const viewport = page.getViewport({ scale: TARGET_WIDTH / base.width });

        const canvas = document.createElement('canvas');
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;

        // toBlob JPEG dipilih karena webp toBlob belum universal; server mengonversi ke webp.
        return await new Promise((resolve, reject) => {
            canvas.toBlob(
                (blob) => (blob ? resolve(blob) : reject(new Error('Kanvas gagal menghasilkan blob'))),
                'image/jpeg',
                0.85,
            );
        });
    } finally {
        doc.destroy?.();
    }
}

function bindInput(input) {
    if (input.dataset.thumbnailInitialized) return;
    input.dataset.thumbnailInitialized = 'true';

    const form = input.form;
    if (!form) return;
    const target = form.querySelector('input[data-thumbnail-target]');
    if (!target) return;
    const submits = form.querySelectorAll('button[type="submit"]');

    input.addEventListener('change', async () => {
        // Selalu kosongkan sampul lama saat PDF dipilih ulang.
        target.files = new DataTransfer().files;

        const file = input.files && input.files[0];
        if (!file) return;

        submits.forEach((btn) => { btn.disabled = true; });
        try {
            const blob = await renderCover(file);
            const dt = new DataTransfer();
            dt.items.add(new File([blob], 'cover.jpg', { type: 'image/jpeg' }));
            target.files = dt.files;
        } catch (e) {
            // Gagal membuat sampul bukan penghalang — modul tetap dapat disimpan tanpa sampul.
            console.warn('Gagal membuat sampul PDF:', e);
        } finally {
            submits.forEach((btn) => { btn.disabled = false; });
        }
    });
}

function initModulThumbnail() {
    document.querySelectorAll('input[data-thumbnail-source]').forEach(bindInput);
}

initModulThumbnail();
document.addEventListener('livewire:navigated', initModulThumbnail);
