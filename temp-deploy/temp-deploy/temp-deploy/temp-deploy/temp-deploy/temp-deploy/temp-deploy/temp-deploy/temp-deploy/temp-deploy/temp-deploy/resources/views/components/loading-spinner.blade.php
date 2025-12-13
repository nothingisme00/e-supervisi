<!-- Loading Spinner Component -->
<div id="loadingSpinner" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="text-center">
        <!-- Spinner -->
        <div class="w-16 h-16 border-4 border-gray-300 border-t-indigo-600 rounded-full animate-spin mx-auto mb-4"></div>

        <!-- Loading Text -->
        <p class="text-white font-medium">Memuat...</p>
    </div>
</div>

<script>
// Function to show loading spinner
function showLoading() {
    document.getElementById('loadingSpinner').classList.remove('hidden');
}

// Function to hide loading spinner
function hideLoading() {
    document.getElementById('loadingSpinner').classList.add('hidden');
}

// Auto hide after 3 seconds (safety net)
function showLoadingWithTimeout() {
    showLoading();
    setTimeout(() => {
        hideLoading();
    }, 3000);
}
</script>
