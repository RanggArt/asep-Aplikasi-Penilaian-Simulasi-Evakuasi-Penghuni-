<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title>ASEP — Aplikasi Penilaian Simulasi Evakuasi Penghuni</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #07080D;
    --bg-soft: #0D0F16;
    --surface: rgba(255,255,255,0.035);
    --surface-strong: rgba(255,255,255,0.06);
    --border: rgba(255,255,255,0.09);
    --border-strong: rgba(255,255,255,0.18);
    --ink: #F3F4F6;
    --ink-muted: #9BA1AD;
    --ink-faint: #5B6170;
    --red: #FF4B45;
    --red-soft: rgba(255,75,69,0.14);
    --blue: #3E8EFF;
    --blue-soft: rgba(62,142,255,0.14);
    --grad: linear-gradient(120deg, var(--red), var(--blue));
    --radius-m: 16px;
    --radius-l: 22px;
  }
  *{ box-sizing: border-box; }
  html{ -webkit-text-size-adjust: 100%; }
  body{
    margin:0;
    background: var(--bg);
    color: var(--ink);
    font-family: 'Inter', -apple-system, sans-serif;
    line-height: 1.55;
    position: relative;
    overflow-x: hidden;
  }
  h1,h2,h3,.label-font{ font-family:'Space Grotesk','Inter',sans-serif; }
  a{ color: inherit; text-decoration: none; }
  @media (prefers-reduced-motion: reduce){
    *{ animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
  }

  /* ---------- Ambient background ---------- */
  .bg-grid{
    position: fixed; inset:0; z-index:0; pointer-events:none;
    background-image:
      linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
    background-size: 42px 42px;
    mask-image: radial-gradient(ellipse 70% 55% at 50% 0%, #000 40%, transparent 100%);
  }
  .glow{
    position: fixed; z-index:0; border-radius:50%; pointer-events:none;
    filter: blur(90px); opacity: 0.55;
  }
  .glow-red{ width: 460px; height: 460px; background: var(--red); top: -160px; left: -120px; animation: drift1 14s ease-in-out infinite; }
  .glow-blue{ width: 520px; height: 520px; background: var(--blue); top: -60px; right: -180px; animation: drift2 16s ease-in-out infinite; }
  @keyframes drift1{ 0%,100%{ transform: translate(0,0); } 50%{ transform: translate(30px, 40px); } }
  @keyframes drift2{ 0%,100%{ transform: translate(0,0); } 50%{ transform: translate(-25px, 30px); } }

  /* ---------- Hero ---------- */
  .hero{
    position: relative; z-index:1;
    padding: 64px 18px 40px;
    text-align: center;
  }
  .hero-inner{
    max-width: 640px; margin: 0 auto;
    opacity: 0; transform: translateY(12px);
    animation: rise 0.7s ease forwards;
  }
  @keyframes rise{ to{ opacity:1; transform: translateY(0); } }

  .logo-badge{
    width: 84px; height: 84px; margin: 0 auto 22px;
    border-radius: 22px;
    background: conic-gradient(from 180deg, var(--red), var(--blue), var(--red));
    padding: 2px;
  }
  .logo-badge-inner{
    width:100%; height:100%; border-radius: 20px;
    background: var(--bg-soft);
    display:flex; align-items:center; justify-content:center;
  }
  .logo-badge svg{ width: 40px; height: 40px; }

  .agency-line{
    display:inline-flex; align-items:center; gap:8px;
    font-size: 12px; letter-spacing: 0.04em; text-transform: uppercase;
    color: var(--ink-muted); margin: 0 0 22px;
    font-family: 'JetBrains Mono', monospace;
  }
  .agency-line .dot{
    width:6px; height:6px; border-radius:50%;
    background: var(--grad);
  }

  .hero h1{
    margin: 0 0 10px; font-size: clamp(48px, 12vw, 72px);
    font-weight: 700; letter-spacing: 0.01em; line-height: 1;
    background: linear-gradient(120deg, #fff 0%, #fff 30%, var(--red) 65%, var(--blue) 100%);
    -webkit-background-clip: text; background-clip: text; color: transparent;
  }
  .hero .full-name{
    margin: 0 0 16px; font-size: clamp(14px, 3.2vw, 17px);
    font-weight: 500; color: var(--ink);
  }
  .hero p.desc{
    margin: 0 auto; max-width: 48ch;
    font-size: 14.5px; color: var(--ink-muted);
  }

  /* ---------- Layout ---------- */
  .wrap{ max-width: 900px; margin: 0 auto; padding: 0 16px; position: relative; z-index: 1; }

  .section-head{ margin: 40px 0 14px; display:flex; align-items:baseline; gap:10px; }
  .section-head .idx{
    font-family:'JetBrains Mono',monospace; font-size: 12px; color: var(--ink-faint);
  }
  .section-head h2{ font-size: 19px; font-weight: 600; margin: 0; }
  .section-sub{ font-size: 13px; color: var(--ink-muted); margin: 4px 0 16px; }

  .grid{ display:grid; grid-template-columns: 1fr; gap: 12px; }
  @media (min-width: 640px){ .grid{ grid-template-columns: 1fr 1fr; } }

  .menu-card{
    position: relative;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-m);
    padding: 18px;
    display:flex; gap:14px; align-items:flex-start;
    backdrop-filter: blur(16px);
    transition: border-color 0.2s ease, transform 0.15s ease, background 0.2s ease;
    overflow: hidden;
  }
  a.menu-card:hover{
    border-color: var(--border-strong);
    transform: translateY(-2px);
    background: var(--surface-strong);
  }
  a.menu-card.accent-red:hover{ box-shadow: 0 8px 28px -8px rgba(255,75,69,0.35); }
  a.menu-card.accent-blue:hover{ box-shadow: 0 8px 28px -8px rgba(62,142,255,0.35); }

  .menu-card.is-dummy{ opacity: 0.5; cursor: not-allowed; }
  .menu-card.is-active::before{
    content:""; position:absolute; left:0; top:0; bottom:0; width:3px;
    background: var(--grad);
  }

  .card-icon{
    flex-shrink:0; width: 44px; height: 44px; border-radius: 12px;
    display:flex; align-items:center; justify-content:center;
  }
  .card-icon svg{ width: 22px; height: 22px; }
  .accent-red .card-icon{ background: var(--red-soft); color: var(--red); }
  .accent-blue .card-icon{ background: var(--blue-soft); color: var(--blue); }
  .is-dummy .card-icon{ background: rgba(255,255,255,0.06); color: var(--ink-faint); }

  .card-body{ flex:1; min-width:0; }
  .card-no{ font-family:'JetBrains Mono',monospace; font-size: 11px; color: var(--ink-faint); }
  .card-title{ font-weight: 600; font-size: 14.5px; margin: 2px 0 3px; color: var(--ink); }
  .card-desc{ font-size: 12.5px; color: var(--ink-muted); margin: 0; line-height: 1.45; }

  .badge{
    flex-shrink:0; align-self:flex-start; margin-top:2px;
    font-family:'JetBrains Mono',monospace; font-size: 10px; font-weight: 500;
    padding: 4px 9px; border-radius: 999px; white-space:nowrap;
    letter-spacing: 0.02em;
  }
  .badge.active{ background: var(--blue-soft); color: var(--blue); }
  .badge.soon{ background: rgba(255,255,255,0.06); color: var(--ink-faint); }

  footer{
    text-align:center; margin: 54px 0 28px; padding-top: 26px;
    border-top: 1px solid var(--border);
    position: relative; z-index: 1;
  }
  footer .agency{ font-size: 12.5px; color: var(--ink-muted); margin: 0 0 6px; }
  footer .credit{
    font-size: 11.5px; color: var(--ink-faint); margin: 0;
    font-family: 'JetBrains Mono', monospace; letter-spacing: 0.02em;
  }
</style>
</head>
<body>

<div class="bg-grid"></div>
<div class="glow glow-red"></div>
<div class="glow glow-blue"></div>

<div class="hero">
  <div class="hero-inner">
    <div class="logo-badge">
      <div class="logo-badge-inner">
        <img src="{{ asset('assets/logo_damkar.png') }}" alt="Logo Damkar" style="width: 54px; height: 54px; object-fit: contain;">
      </div>
    </div>
    <div class="agency-line"><span class="dot"></span>Suku Dinas Penanggulangan Kebakaran &amp; Penyelamatan &mdash; Jakarta Utara</div>
    <h1>ASEP</h1>
    <p class="full-name">Aplikasi Penilaian Simulasi Evakuasi Penghuni</p>
    <p class="desc">Sistem penilaian pintar untuk mengukur tingkat kesiapsiagaan, kecepatan respons, proteksi keselamatan kebakaran, serta komunikasi gedung saat simulasi darurat.</p>
  </div>
</div>

<div class="wrap">

  <div class="section-head"><span class="idx">01</span><h2>Penilaian Kuantitatif</h2></div>
  <p class="section-sub">Delapan kategori penilaian utama dengan skala skor 1&ndash;4.</p>
  <div class="grid" id="quantGrid"></div>

  <div class="section-head"><span class="idx">02</span><h2>Observasi &amp; Wawancara Kualitatif</h2></div>
  <p class="section-sub">Catatan deskriptif dari wawancara dan pengecekan lapangan.</p>
  <div class="grid" id="qualGrid"></div>

<div style="margin-top: 40px; padding: 24px; background: rgba(62,142,255,0.05); border: 1px dashed rgba(62,142,255,0.3); border-radius: 16px; text-align: center;">
    <h3 style="margin: 0 0 12px; font-size: 16px; color: #3E8EFF;">Rekapitulasi</h3>
    <a href="{{ route('rekap') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #3E8EFF; color: #fff; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="8" y1="6" x2="16" y2="6"></line><line x1="16" y1="14" x2="16" y2="14.01"></line><line x1="12" y1="14" x2="12" y2="14.01"></line><line x1="8" y1="14" x2="8" y2="14.01"></line><line x1="16" y1="18" x2="16" y2="18.01"></line><line x1="12" y1="18" x2="12" y2="18.01"></line><line x1="8" y1="18" x2="8" y2="18.01"></line><line x1="16" y1="10" x2="16" y2="10.01"></line><line x1="12" y1="10" x2="12" y2="10.01"></line><line x1="8" y1="10" x2="8" y2="10.01"></line></svg>
      Buka Kalkulator Rekapitulasi
    </a>
  </div>

  <footer>
    <p class="agency">Suku Dinas Penanggulangan Kebakaran dan Penyelamatan &mdash; Kota Administrasi Jakarta Utara</p>
    <p class="credit">crafted by: Rangga Riswanto</p>
  </footer>
</div>

<script>
(function(){
  const ICON_CHECK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>';
  const ICON_CHAT = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';

  const QUANT = [
    { title: "Penilaian Umum Evakuasi", desc: "Kecepatan respons, keteraturan, dan jalur evakuasi penghuni.", href: "{{ route('form.umum') }}", active: true },
    { title: "Penilaian Koordinator Lapangan / FSM", desc: "Kinerja Fire Safety Manager saat memimpin simulasi.", href: "{{ route('form.fsm') }}", active: true },
    { title: "Penilaian Tim Teknisi", desc: "Kesiapan dan respons tim teknisi gedung.", href: "{{ route('form.teknisi') }}", active: true },
    { title: "Penilaian Tim Evakuasi", desc: "Kinerja petugas dalam memandu proses evakuasi.", href: "{{ route('form.timevakuasi') }}", active: true },
    { title: "Penilaian Tim Penanganan Titik Kumpul", desc: "Ketertiban dan pendataan penghuni di titik kumpul.", href: "{{ route('form.titikkumpul') }}", active: true },
    { title: "Penilaian Tim Rescue dan P3K", desc: "Kesiapan pertolongan dan penanganan medis darurat.", href: "{{ route('form.rescue') }}", active: true },
    { title: "Penilaian Tim Pemadam Kebakaran Internal", desc: "Respons tim pemadam internal gedung.", href: "{{ route('form.pemadam') }}", active: true },
    { title: "Penilaian Tim Pengamanan", desc: "Pengamanan area dan pengendalian akses selama simulasi.", href: "{{ route('form.pengamanan') }}", active: true }
  ];

  const QUAL = [
    { title: "Partisipasi Penghuni", desc: "Pengalaman dan saran dari penghuni/tenant." },
    { title: "Kesiapan Floor Warden", desc: "Wawancara tugas dan pengalaman Kapten Floor." },
    { title: "Kondisi Tempat Berkumpul", desc: "Kelayakan lokasi dan keaktifan petugas di titik kumpul." },
    { title: "Pengecekan Fasilitas & Sistem Tambahan", desc: "Alarm, tangga darurat, general alarm, hingga Siamese Connection." }
  ];

  // Fungsi untuk membagikan link
  window.bagikanLink = function(e, url, title) {
    e.preventDefault(); // Mencegah pindah halaman saat tombol diklik
    e.stopPropagation(); // Mencegah klik menyebar ke area kartu

    if (navigator.share) {
      // Memanggil fitur share native di HP (WhatsApp, dll)
      navigator.share({
        title: 'Formulir ' + title,
        text: 'Mohon isi Formulir ' + title + ' pada simulasi ini:',
        url: url
      }).catch((err) => console.log('Batal bagikan:', err));
    } else {
      // Fallback jika dibuka di PC/Browser lama: salin teks ke clipboard
      navigator.clipboard.writeText(url).then(() => {
        alert('Tautan form berhasil disalin! Silakan paste di WhatsApp.');
      });
    }
  };

  function renderCard(item, index, iconSvg){
    const isActive = !!item.active;
    const tag = isActive ? 'a' : 'div';
    const hrefAttr = isActive ? `href="${item.href}"` : '';
    const accent = index % 2 === 0 ? 'accent-red' : 'accent-blue';
    const stateClass = isActive ? 'is-active' : 'is-dummy';
    
    // Ikon share SVG
    const shareIcon = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:12px; height:12px; margin-right:4px;"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>`;

    // Jika aktif, tampilkan tombol bagikan
    const badge = isActive
      ? `<button class="badge active" onclick="bagikanLink(event, '${item.href}', '${item.title}')" style="border:none; cursor:pointer; display:inline-flex; align-items:center;">${shareIcon} Bagikan</button>`
      : '<span class="badge soon">Segera Hadir</span>';

    return `
      <${tag} ${hrefAttr} class="menu-card ${accent} ${stateClass}">
        <div class="card-icon">${iconSvg}</div>
        <div class="card-body">
          <div class="card-no">${String(index+1).padStart(2,'0')}</div>
          <p class="card-title">${item.title}</p>
          <p class="card-desc">${item.desc}</p>
        </div>
        ${badge}
      </${tag}>`;
  }

  document.getElementById('quantGrid').innerHTML =
    QUANT.map((item, i) => renderCard(item, i, ICON_CHECK)).join('');

  document.getElementById('qualGrid').innerHTML =
    QUAL.map((item, i) => renderCard(item, i, ICON_CHAT)).join('');
})();
</script>
</body>
</html>
