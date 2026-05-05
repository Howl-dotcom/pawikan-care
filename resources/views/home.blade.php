<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawikanCare</title>
  <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chelsea+Market&display=swap" rel="stylesheet">

  <style>
    /* Background */
    .bg-wrap {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: -1;
    }
    .bg-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(10);
    }
    .bg-dim {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 30, 60, 0);
  
    }

    /* Main login area */
    main#login {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
    }

    /* Logo */
    .logo {
      font-family: "Chelsea Market", sans-serif;
      font-size: 2.8rem;
      letter-spacing: 2px;
      color: #00b4d8;
      margin-bottom: 25px;
      text-shadow: 0 0 10px rgba(0, 180, 216, 0.4);
    }

    /* Form */
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 90%;
      max-width: 380px;
      background: rgba(0, 41, 73, 1);
      padding: 35px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 150, 200, 0.3);
      animation: fadeIn 0.8s ease;
    }

    input {
      padding: 12px;
      border: none;
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      font-size: 15px;
      outline: none;
      transition: background 0.3s;
    }
    input::placeholder {
      color: #bcdff1;
    }
    input:focus {
      background: rgba(255, 255, 255, 0.2);
    }

    /* Button */
    button {
      padding: 12px;
      border: none;
      border-radius: 6px;
      background: #00b4d8;
      color: #fff;
      font-weight: 600;
      font-size: 15px;
      letter-spacing: 0.5px;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }
    button:hover {
      background: #009dc1;
      transform: translateY(-2px);
    }

    /* Error */
    form + div,
    #login div[style*="color:red"] {
      color: #ff6b6b !important;
      margin-bottom: 10px;
      font-size: 14px;
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 600px) {
      .logo {
        font-size: 2rem;
      }
      form {
        padding: 25px;
      }
    }
  </style>
</head>

<body>
  <div class="bg-wrap" aria-hidden="true">
    <img src="/turtle-bg.png" class="bg-image" alt="">
    <div class="bg-dim"></div>
  </div>

  <main id="login" class="screen active" role="application" aria-labelledby="app-title">
    <h1 id="app-title" class="logo">PAWIKANCARE</h1>

    @if ($errors->any())
      <div>{{ $errors->first('email') }}</div>
    @endif

    <form id="loginForm" method="POST" action="{{ route('login.post') }}">
      @csrf
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">LOG IN</button>
    </form>
  </main>
</body>
</html>
