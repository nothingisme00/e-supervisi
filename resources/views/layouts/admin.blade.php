<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - E-Supervisi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-md fixed inset-y-0 left-0 flex flex-col">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-blue-600">E-Supervisi</h1>
        </div>

        <nav class="flex-1 p-4 space-y-3">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 text-gray-700 font-medium">
                🏠 Dashboard
            </a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 text-gray-700 font-medium">
                👥 Data Guru
            </a>
            <a href="#" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-blue-100 text-gray-700 font-medium">
                📄 Laporan Supervisi
            </a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="p-4 border-t">
            @csrf
            <button class="flex items-center gap-2 text-red-600 hover:text-red-700 font-medium w-full text-left">
                🚪 Logout
            </button>
        </form>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 ml-64 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-700">@yield('page-title')</h2>
            <div class="flex items-center gap-2">
                <span class="text-gray-600">{{ Auth::user()->name ?? 'Admin' }}</span>
                <div class="w-8 h-8 bg-blue-600 text-white flex items-center justify-center rounded-full uppercase">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </div>

        {{-- Page content --}}
        @yield('content')
    </div>

</body>
</html>
