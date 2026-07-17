import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';

const getDocumentMock = vi.fn();

vi.mock('pdfjs-dist', () => ({
    GlobalWorkerOptions: {},
    getDocument: (...args) => getDocumentMock(...args),
}));

vi.mock('pdfjs-dist/build/pdf.worker.min.mjs?url', () => ({ default: 'mock-worker-url' }));

function makeFakePdfDoc() {
    return {
        destroy: vi.fn(),
        getPage: vi.fn(() => Promise.resolve({
            getViewport: ({ scale }) => ({ width: 595 * scale, height: 842 * scale }),
            render: vi.fn(() => ({ promise: Promise.resolve() })),
        })),
    };
}

function setupDom() {
    document.body.innerHTML = `
        <form>
            <input type="file" data-thumbnail-source>
            <input type="file" data-thumbnail-target>
            <button type="submit">Unggah</button>
        </form>
    `;
}

/** File palsu ringan; modul hanya butuh arrayBuffer(). */
function fakePdfFile() {
    return { name: 'modul.pdf', arrayBuffer: () => Promise.resolve(new ArrayBuffer(8)) };
}

function selectFile(input, file) {
    input.files = file ? [file] : [];
    input.dispatchEvent(new Event('change'));
}

beforeEach(() => {
    vi.resetModules();
    getDocumentMock.mockReset();

    // input.files normalnya read-only di jsdom; buat dapat di-set agar mekanisme
    // DataTransfer (cara browser mengisi input file) bisa diuji.
    Object.defineProperty(HTMLInputElement.prototype, 'files', {
        configurable: true,
        get() { return this._files ?? []; },
        set(v) { this._files = v; },
    });
    HTMLCanvasElement.prototype.getContext = vi.fn(() => ({}));
    HTMLCanvasElement.prototype.toBlob = vi.fn((cb) => cb(new Blob(['x'], { type: 'image/jpeg' })));

    vi.stubGlobal('DataTransfer', class {
        constructor() { this._files = []; this.items = { add: (f) => this._files.push(f) }; }
        get files() { return this._files; }
    });
});

afterEach(() => {
    delete HTMLInputElement.prototype._files;
    vi.unstubAllGlobals();
    document.body.innerHTML = '';
});

describe('modul-thumbnail', () => {
    it('renders page 1 into the target input and toggles submit while working', async () => {
        setupDom();
        getDocumentMock.mockReturnValue({ promise: Promise.resolve(makeFakePdfDoc()) });

        await import('./modul-thumbnail.js');

        const source = document.querySelector('[data-thumbnail-source]');
        const target = document.querySelector('[data-thumbnail-target]');
        const submit = document.querySelector('button[type="submit"]');

        selectFile(source, fakePdfFile());
        // Disable segera saat render dimulai.
        expect(submit.disabled).toBe(true);

        await vi.waitFor(() => expect(target.files.length).toBe(1));
        expect(submit.disabled).toBe(false);
        expect(getDocumentMock).toHaveBeenCalled();
    });

    it('leaves the form submittable when PDF rendering fails', async () => {
        setupDom();
        getDocumentMock.mockReturnValue({ promise: Promise.reject(new Error('pdf rusak')) });
        vi.spyOn(console, 'warn').mockImplementation(() => {});

        await import('./modul-thumbnail.js');

        const source = document.querySelector('[data-thumbnail-source]');
        const target = document.querySelector('[data-thumbnail-target]');
        const submit = document.querySelector('button[type="submit"]');

        selectFile(source, fakePdfFile());

        await vi.waitFor(() => expect(submit.disabled).toBe(false));
        expect(target.files.length).toBe(0);
    });
});
