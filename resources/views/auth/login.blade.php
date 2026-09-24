<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title>Masuk — APEM</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #07080D; --bg-soft: #0D0F16;
    --surface: rgba(255,255,255,0.035); --surface-strong: rgba(255,255,255,0.06);
    --border: rgba(255,255,255,0.09); --border-strong: rgba(255,255,255,0.18);
    --ink: #F3F4F6; --ink-muted: #9BA1AD; --ink-faint: #5B6170;
    --red: #FF4B45; --blue: #3E8EFF;
    --grad: linear-gradient(120deg, var(--red), var(--blue));
    --radius-m: 16px; --radius-l: 22px;
  }
  *{ box-sizing: border-box; }
  html{ -webkit-text-size-adjust:100%; }
  body{
    margin:0; min-height:100svh; background: var(--bg); color: var(--ink);
    font-family:'Inter',-apple-system,sans-serif; line-height:1.5;
    display:flex; align-items:center; justify-content:center; padding: 28px 16px;
    position:relative; overflow-x:hidden;
  }
  h1,h2,.label-font{ font-family:'Space Grotesk','Inter',sans-serif; }
  button{ font-family:inherit; cursor:pointer; }
  :focus-visible{ outline: 2px solid var(--blue); outline-offset:2px; }
  @media (prefers-reduced-motion: reduce){ *{ animation-duration:0.001ms !important; transition-duration:0.001ms !important; } }

  .bg-grid{
    position: fixed; inset:0; z-index:0; pointer-events:none;
    background-image: linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
    background-size: 42px 42px;
    mask-image: radial-gradient(ellipse 70% 60% at 50% 40%, #000 40%, transparent 100%);
  }
  .glow{ position:fixed; z-index:0; border-radius:50%; pointer-events:none; filter: blur(90px); opacity:0.5; }
  .glow-red{ width:420px; height:420px; background: var(--red); top:-140px; left:-140px; animation: drift1 14s ease-in-out infinite; }
  .glow-blue{ width:460px; height:460px; background: var(--blue); bottom:-160px; right:-160px; animation: drift2 16s ease-in-out infinite; }
  @keyframes drift1{ 0%,100%{ transform: translate(0,0); } 50%{ transform: translate(25px,30px); } }
  @keyframes drift2{ 0%,100%{ transform: translate(0,0); } 50%{ transform: translate(-20px,-25px); } }

  /* ---------- Gradient-bordered card (the "lis") ---------- */
  .auth-shell{
    position: relative; z-index:1; width:100%; max-width: 400px;
    opacity:0; transform: translateY(10px); animation: rise 0.6s ease forwards;
  }
  @keyframes rise{ to{ opacity:1; transform: translateY(0); } }
  .auth-ring{ padding: 1.5px; border-radius: var(--radius-l); background: linear-gradient(135deg, var(--red), var(--blue) 55%, var(--red)); }
  .auth-card{
    border-radius: calc(var(--radius-l) - 1.5px);
    background: var(--bg-soft);
    padding: 30px 26px 26px;
    backdrop-filter: blur(18px);
  }

  .logo-badge{ width:56px; height:56px; margin:0 auto 16px; border-radius:16px; background: conic-gradient(from 180deg, var(--red), var(--blue), var(--red)); padding:2px; }
  .logo-badge-inner{ width:100%; height:100%; border-radius:14px; background: var(--bg-soft); display:flex; align-items:center; justify-content:center; }
  .logo-badge svg{ width:26px; height:26px; }

  .auth-title{ text-align:center; margin-bottom: 22px; }
  .auth-title h1{ font-size:20px; font-weight:600; margin:0 0 4px; }
  .auth-title p{ font-size:12.5px; color: var(--ink-muted); margin:0; }

  /* ---------- Google button ---------- */
  .google-btn{
    width:100%; display:flex; align-items:center; justify-content:center; gap:10px;
    background:#fff; color:#3C4043; border:none; border-radius:10px; padding:12px 16px;
    font-size:14px; font-weight:600; transition: transform 0.12s ease, box-shadow 0.15s ease; text-decoration: none;
  }
  .google-btn:hover{ box-shadow: 0 6px 20px -6px rgba(62,142,255,0.4); transform: translateY(-1px); }
  .google-btn svg{ width:18px; height:18px; flex-shrink:0; }

  .divider{ display:flex; align-items:center; gap:12px; margin: 20px 0; }
  .divider::before, .divider::after{ content:""; flex:1; height:1px; background: var(--border); }
  .divider span{ font-size:11.5px; color: var(--ink-faint); font-family:'JetBrains Mono',monospace; }

  /* ---------- Fields ---------- */
  .field{ margin-bottom: 14px; }
  .field label{ display:block; font-size:12px; font-weight:500; color: var(--ink-muted); margin-bottom:6px; }
  .field input{
    width:100%; background: var(--surface); border:1px solid var(--border); border-radius:10px;
    padding: 11px 13px; font-size:14px; color: var(--ink); font-family:'Inter';
  }
  .field input::placeholder{ color: var(--ink-faint); }
  .field input:focus{ border-color: var(--blue); background: var(--surface-strong); }
  .error-msg{ color: var(--red); font-size: 12px; margin-top: 4px; display: block; }

  .submit-btn{
    width:100%; margin-top: 6px; border:none; border-radius:10px; padding: 12px 16px;
    font-weight:600; font-size:14px; color:#fff; background: var(--grad);
    display:flex; align-items:center; justify-content:center; gap:8px;
    transition: opacity 0.15s ease, transform 0.12s ease;
  }
  .submit-btn:hover{ opacity:0.92; transform: translateY(-1px); }

  .foot-note{ text-align:center; font-size:11.5px; color: var(--ink-faint); margin-top:18px; }
</style>
</head>
<body>

<div class="bg-grid"></div>
<div class="glow glow-red"></div>
<div class="glow glow-blue"></div>

<div style="position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;width:100%;max-width:400px;">
  
  <div class="auth-shell">
    <div class="auth-ring">
      <div class="auth-card">
        <div class="logo-badge">
          <div class="logo-badge-inner">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
              <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
            </svg>
          </div>
        </div>
        <div class="auth-title">
          <h1>Masuk ke APEM</h1>
          <p>Aplikasi Pengesahan MKKG &mdash; Sudinkar Jakarta Utara</p>
        </div>

        <!-- Masuk sebagai pengguna melalui Google -->
        <a href="{{ route('google.login') }}" class="google-btn">
          <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M23.49 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47a5.54 5.54 0 0 1-2.4 3.63v3h3.88c2.27-2.09 3.54-5.17 3.54-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.08 7.95-2.91l-3.88-3c-1.08.72-2.45 1.15-4.07 1.15-3.13 0-5.78-2.11-6.73-4.96H1.27v3.1A12 12 0 0 0 12 24z"/><path fill="#FBBC05" d="M5.27 14.28A7.2 7.2 0 0 1 4.89 12c0-.79.14-1.56.38-2.28v-3.1H1.27A12 12 0 0 0 0 12c0 1.94.46 3.77 1.27 5.38z"/><path fill="#EA4335" d="M12 4.77c1.77 0 3.35.61 4.6 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.69 1.27 6.62l4 3.1C6.22 6.88 8.87 4.77 12 4.77z"/></svg>
          <span>Masuk lewat Google</span>
        </a>

        <div class="divider"><span>ATAU MASUK DENGAN ID</span></div>

        <!-- Borang Log Masuk Breeze Berfungsi -->
        <form method="POST" action="{{ route('login') }}">
          @csrf

          <div class="field">
            <label for="login-id">Masukkan ID</label>
            <input type="text" id="login-id" name="email" value="{{ old('email') }}" placeholder="Masukkan ID Anda" required autofocus autocomplete="username">
            @error('email')
                <span class="error-msg">{{ $message }}</span>
            @enderror
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required autocomplete="current-password">
            @error('password')
                <span class="error-msg">{{ $message }}</span>
            @enderror
          </div>

          <!-- Pilihan 'Ingat Saya' (Tersembunyi tetapi aktif untuk sesi) -->
          <input type="hidden" name="remember" value="on">

          <button type="submit" class="submit-btn">
            <span>Masuk</span>
          </button>
        </form>

      </div>
    </div>
  </div>
</div>

</body>
</html>
