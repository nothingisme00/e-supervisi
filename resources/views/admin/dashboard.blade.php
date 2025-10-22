<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
        }
        main {
            margin-top: 20px;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            background-color: #3498db;
            padding: 10px 15px;
            border-radius: 8px;
        }
        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <header>
        <h2>Selamat Datang, {{ auth()->user()->name }} ({{ auth()->user()->role }})</h2>
    </header>

    <main>
        <p>Ini adalah halaman Dashboard Admin.</p>
        <a href="{{ route('users.index') }}">Kelola User</a>
        <form action="/logout" method="POST" style="display:inline">
            @csrf
            <button type="submit" style="background-color:#e74c3c;border:none;padding:10px 15px;border-radius:8px;color:white;cursor:pointer;">Logout</button>
        </form>
    </main>
</body>
</html>
