<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | E-Supervisi Web</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  <div class="login-container">
    <h2>E-Supervisi Login</h2>

    @if ($errors->any())
      <div class="alert">Email atau password salah.</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <label for="email">Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required autofocus>

      <label for="password">Password</label>
      <input type="password" name="password" required>

      <button type="submit" class="login-button">Masuk</button>
    </form>

    <p class="footer-text">
      Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
    </p>
  </div>
</body>
</html>
