<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthy Motor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 30px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .logo-container {
            margin-bottom: 10px;
        }

        .logo-container img {
            width: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .dm5s {
            font-size: 48px;
            font-weight: bold;
            color: black;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .healthy-motor {
            font-size: 20px;
            color: black;
            margin-bottom: 30px;
            font-weight: normal;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn {
            padding: 12px 35px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login {
            background-color: #FFD700;
            color: black;
        }

        .btn-register {
            background-color: white;
            color: black;
            border: 2px solid #FFD700;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card {
                padding: 30px 20px;
            }

            .logo-container img {
                width: 150px;
            }

            .dm5s {
                font-size: 36px;
            }

            .healthy-motor {
                font-size: 18px;
            }

            .btn {
                padding: 10px 25px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            {{-- Gambar mesin di tengah --}}
            <div class="logo-container">
                <img src="{{ asset('assets/images/mesin.png') }}" alt="Motor Logo">
            </div>

            {{-- Teks DM5S di atas container putih --}}
            <div class="dm5s">DM5S</div>

            {{-- Teks Healthy Motor di bawah gambar --}}
            <div class="healthy-motor">Healthy Motor</div>

            {{-- Tombol Login dan Register bersebelahan --}}
            <div class="button-group">
                <button class="btn btn-login">Login</button>
                <button class="btn btn-register">Register</button>
            </div>
        </div>
    </div>
</body>
</html>
