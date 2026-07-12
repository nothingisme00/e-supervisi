{{--
    Script anti-flash tema (dark mode) — WAJIB dimuat di <head>, sebelum body
    di-paint, supaya tidak ada kedipan (flash) tema terang sesaat sebelum
    tema gelap diterapkan.

    Prioritas: localStorage.theme ('dark'/'light') menang bila ada; bila
    kosong, ikuti preferensi sistem (prefers-color-scheme). Kelas `dark`
    di-set/dihapus langsung di <html> secara sinkron (bukan di dalam
    DOMContentLoaded/load), supaya berjalan sebelum browser sempat
    merender apa pun.
--}}
<script>
    (function () {
        var stored = localStorage.getItem('theme');
        var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (stored === 'dark' || (!stored && prefersDark)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    })();
</script>
