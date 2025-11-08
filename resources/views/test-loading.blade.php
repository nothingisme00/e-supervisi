@extends('layouts.modern')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">
            Test Loading Spinner
        </h1>

        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Klik tombol di bawah untuk melihat loading spinner
        </p>

        <!-- Test Buttons -->
        <div class="space-y-4">
            <!-- Manual Show/Hide -->
            <div>
                <button onclick="showLoading()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 mr-2">
                    Show Loading
                </button>
                <button onclick="hideLoading()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Hide Loading
                </button>
            </div>

            <!-- Auto hide after 3 seconds -->
            <div>
                <button onclick="showLoadingWithTimeout()" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Show Loading (Auto Hide 3s)
                </button>
            </div>

            <!-- Simulate Form Submit -->
            <div>
                <button onclick="simulateFormSubmit()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simulasi Submit Form
                </button>
            </div>
        </div>

        <!-- Example Code -->
        <div class="mt-12 text-left bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-sm font-bold text-gray-800 dark:text-white mb-2">Cara Penggunaan:</p>
            <pre class="text-xs text-gray-700 dark:text-gray-300 overflow-x-auto"><code>// Manual control
showLoading();  // Tampilkan spinner
hideLoading();  // Sembunyikan spinner

// Auto hide setelah 3 detik
showLoadingWithTimeout();

// Pada form submit
&lt;form onsubmit="showLoading()"&gt;
  ...
&lt;/form&gt;</code></pre>
        </div>
    </div>
</div>

<!-- Include Loading Spinner Component -->
<x-loading-spinner />

<script>
// Simulate form submission
function simulateFormSubmit() {
    showLoading();

    // Simulate processing
    setTimeout(() => {
        hideLoading();
        alert('Form berhasil disubmit!');
    }, 2000);
}
</script>
@endsection
