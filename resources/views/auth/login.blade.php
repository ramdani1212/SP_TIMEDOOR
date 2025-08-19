<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Timedoor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E8F5E9; /* Hijau muda */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .logo {
            width: 100px; /* Ukuran lebar logo yang sudah ditetapkan */
            height: auto; /* Agar rasio gambar tetap proporsional */
            margin: 0 auto 20px auto; /* Memusatkan logo dan memberikan jarak bawah */
        }
        h1 {
            color: #66BB6A;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .password-container {
            position: relative;
        }
        input[type="email"], .password-container input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        input[type="email"]:focus, .password-container input:focus {
            border-color: #66BB6A;
            outline: none;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            user-select: none;
        }
        .login-button {
            width: 100%;
            background-color: #66BB6A;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-button:hover {
            background-color: #5cb85c;
        }
        .error-message {
            color: #d9534f;
            margin-top: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/logo2.png') }}" alt="Logo Timedoor" class="logo">
        <h1>Login Admin</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input id="password" type="password" name="password" required>
                    <span class="password-toggle" onclick="togglePasswordVisibility('password')">Show</span>
                </div>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif
    </div>

    <script>
        function togglePasswordVisibility(id) {
            const passwordInput = document.getElementById(id);
            const toggleButton = passwordInput.nextElementSibling;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = 'Show';
            }
        }
    </script>
</body>
</html>