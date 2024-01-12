<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Sistem Laporan Insiden</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        body {
            background-image: url('img/login_bg.svg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Open Sans', sans-serif;
            box-sizing: border-box;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-sizing: border-box;
            text-align: center;
            color: rgba(0, 114, 185, 0.90);
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: rgba(0, 114, 185, 0.90);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="left-box">
            <div class="title-box">
                <h2 style="font-size: 20px; margin: 0; padding: 0">LOGIN SISTEM</h2>
                <h2 style="font-size: 20px; margin: 0; padding: 0;">LAPORKAN INSIDEN</h2>
            </div>
            <div class="login-form">
                <form id="loginForm" method="post" action="{{ route('login') }}">
                    @csrf
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <p style="font-size: 10px; color:#323232">Jika lupa password hubungi admin di admin@ap1.co.id.</p>
                    <button type="button" onclick="submitForm()">Login</button>
                </form>
            </div>
        </div>
        <div class="right-box">
            <img src="img/loginGambar.svg" alt="Login Image" style="max-height: 100%; max-width: 100%;">
            @if (isset($token) && isset($role))
                <script>
                    var token = "{{ $token }}";
                    var role = "{{ $role }}";
                    var redirectRoute = "";

                    switch (role) {
                        case 'Admin':
                            redirectRoute = "{{ route('admin.contentManagement') }}";
                            break;
                        case 'Pelapor':
                            redirectRoute = "{{ route('pelapor.reportPelapor') }}";
                            break;
                        case 'Pimpinan':
                            redirectRoute = "{{ route('pimpinan.dashboard') }}";
                            break;
                        case 'Superuser':
                            redirectRoute = "{{ route('superuser') }}";
                            break;
                        default:
                            redirectRoute = "{{ route('user.beranda') }}";
                    }

                    window.location.href = redirectRoute;
                </script>
            @endif

            <a href="{{ route('user.beranda') }}" class="back-to-home">Kembali Ke Beranda</a>
        </div>
        @if ($errors->has('popup'))
            <script>
                alert('Anda telah mencapai batas percobaan login maksimum. Silakan tunggu selama 20 detik.');
            </script>
        @endif
    </div>

    <script>
        function submitForm() {
            var emailInput = document.getElementById('email');
            var passwordInput = document.getElementById('password');

            if (!emailInput.value.endsWith('@gmail.com')) {
                alert('Mohon masukkan alamat email yang berakhiran @gmail.com.');
                return;
            }

            fetch('{{ route('login') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    email: emailInput.value,
                    password: passwordInput.value,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error); // Display any error message from the server
                } else {
                    localStorage.setItem('token', data.token);
                    var redirectRoute = data.redirect_route || "{{ route('user.beranda') }}";
                    window.location.href = redirectRoute;
                }
            })
            .catch(error => {
                console.error('Error during login:', error);
                alert('Login gagal. Silakan coba lagi.');
            });
        }
    </script>
</body>
</html>
