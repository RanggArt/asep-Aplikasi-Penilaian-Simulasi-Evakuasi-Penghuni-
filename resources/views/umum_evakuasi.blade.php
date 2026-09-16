<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title>Formulir Penilaian Evakuasi Penghuni</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<style>
  :root{
    --bg: #F7F4F1;
    --surface: #FFFFFF;
    --ink: #221C1A;
    --ink-muted: #756762;
    --ink-faint: #A79A95;
    --line: #E7DFDA;
    --primary: #AB2A1E;
    --primary-dark: #7C1E15;
    --primary-tint: #FBEAE7;
    --accent: #E07A12;
    --score-4: #1E7A4C;
    --score-4-tint: #E7F4EC;
    --score-3: #0E7C86;
    --score-3-tint: #E4F3F4;
    --score-2: #C1790A;
    --score-2-tint: #FBF0DE;
    --score-1: #AB2A1E;
    --score-1-tint: #FBEAE7;
    --radius-s: 6px;
    --radius-m: 10px;
    --shadow: 0 1px 2px rgba(34,28,26,0.06), 0 2px 10px rgba(34,28,26,0.05);
  }

  *{ box-sizing: border-box; }
  html{ -webkit-text-size-adjust: 100%; }
  body{
    margin:0;
    background: var(--bg);
    color: var(--ink);
    font-family: 'Inter', -apple-system, sans-serif;
    line-height: 1.5;
    padding-bottom: 96px;
  }
  h1,h2,h3, .label-font{ font-family:'Oswald', 'Inter', sans-serif; }

  @media (prefers-reduced-motion: reduce){
    *{ animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
  }

  a, button{ font-family: inherit; }
  button{ cursor: pointer; }
  :focus-visible{ outline: 2px solid var(--accent); outline-offset: 2px; }

  /* ---------- Header ---------- */
  .top{
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color:#fff;
    padding: 22px 18px 34px;
    position: relative;
    overflow: hidden;
  }
  .top::after{
    content:"";
    position:absolute; right:-40px; top:-40px;
    width:180px; height:180px; border-radius:50%;
    background: rgba(255,255,255,0.06);
  }
  .top::before{
    content:"";
    position:absolute; left:-30px; bottom:-60px;
    width:140px; height:140px; border-radius:50%;
    background: rgba(255,255,255,0.05);
  }
  .top-inner{
    max-width: 760px; margin: 0 auto; position: relative;
    opacity: 0; transform: translateY(8px);
    animation: rise 0.55s ease forwards;
  }
  @keyframes rise{ to{ opacity:1; transform: translateY(0); } }
  .top-eyebrow{
    display:flex; align-items:center; gap:8px;
    font-size: 12.5px; color: rgba(255,255,255,0.85);
    letter-spacing: 0.02em; margin-bottom: 10px;
  }
  .top-eyebrow svg{ width:16px; height:16px; flex-shrink:0; }
  h1{
    margin:0 0 6px; font-size: clamp(24px, 6vw, 32px);
    font-weight: 600; letter-spacing: 0.01em; line-height:1.15;
  }
  .top p{ margin:0; color: rgba(255,255,255,0.82); font-size: 14.5px; max-width: 46ch; }

  /* ---------- Layout ---------- */
  .wrap{ max-width: 760px; margin: 0 auto; padding: 0 16px; }
  .card-shell{
    background: var(--surface);
    border-radius: var(--radius-m);
    border: 1px solid var(--line);
    box-shadow: var(--shadow);
  }

  .meta{
    margin-top: -20px;
    padding: 18px;
    display:grid; gap: 14px;
    grid-template-columns: 1fr;
  }
  @media (min-width: 620px){
    .meta{ grid-template-columns: 1fr 1fr; }
  }
  .field{ display:flex; flex-direction:column; gap:6px; }
  .field label{
    font-size: 12.5px; font-weight: 600; color: var(--ink-muted);
    letter-spacing: 0.01em;
  }
  .field input{
    border: 1px solid var(--line);
    border-radius: var(--radius-s);
    padding: 11px 12px;
    font-size: 15px;
    color: var(--ink);
    background: var(--surface);
    font-family: 'Inter', sans-serif;
  }
  .field input:focus{ border-color: var(--primary); }

  .section-head{
    display:flex; align-items:baseline; justify-content: space-between;
    margin: 30px 0 12px; padding: 0 2px;
  }
  .section-head h2{
    font-size: 18px; font-weight: 600; margin:0; color: var(--ink);
  }
  .section-head span{ font-size: 13px; color: var(--ink-faint); font-family:'Inter'; }

  /* ---------- Indicator cards ---------- */
  .items{ display:flex; flex-direction:column; gap: 12px; }
  .item{
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-m);
    padding: 16px;
    transition: border-color 0.2s ease;
  }
  .item.is-filled{ border-color: #D8CEC8; }
  .item-top{ display:flex; gap: 12px; margin-bottom: 14px; }
  .item-no{
    flex-shrink:0;
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--primary-tint);
    color: var(--primary-dark);
    display:flex; align-items:center; justify-content:center;
    font-family:'Oswald'; font-weight:600; font-size: 13px;
    transition: background 0.2s ease, color 0.2s ease;
  }
  .item.is-filled .item-no{ background: var(--primary); color:#fff; }
  .item-title{ font-weight: 600; font-size: 15px; margin: 0 0 3px; color: var(--ink); }
  .item-desc{ font-size: 13.5px; color: var(--ink-muted); margin:0; line-height:1.45; }

  .scale{
    display:grid; grid-template-columns: repeat(2, 1fr);
    gap: 8px;
  }
  @media (min-width: 560px){
    .scale{ grid-template-columns: repeat(4, 1fr); }
  }
  .scale button{
    border: 1.5px solid var(--line);
    background: var(--surface);
    border-radius: var(--radius-s);
    padding: 10px 6px 9px;
    min-height: 54px;
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap: 2px;
    transition: transform 0.12s ease, border-color 0.15s ease, background 0.15s ease;
  }
  .scale button:active{ transform: scale(0.96); }
  .scale button .num{ font-family:'Oswald'; font-weight:600; font-size: 16px; line-height:1; }
  .scale button .txt{ font-size: 11.5px; color: var(--ink-muted); }

  .scale button[data-v="4"].active{ background: var(--score-4-tint); border-color: var(--score-4); }
  .scale button[data-v="4"].active .num,
  .scale button[data-v="4"].active .txt{ color: var(--score-4); }
  .scale button[data-v="3"].active{ background: var(--score-3-tint); border-color: var(--score-3); }
  .scale button[data-v="3"].active .num,
  .scale button[data-v="3"].active .txt{ color: var(--score-3); }
  .scale button[data-v="2"].active{ background: var(--score-2-tint); border-color: var(--score-2); }
  .scale button[data-v="2"].active .num,
  .scale button[data-v="2"].active .txt{ color: var(--score-2); }
  .scale button[data-v="1"].active{ background: var(--score-1-tint); border-color: var(--score-1); }
  .scale button[data-v="1"].active .num,
  .scale button[data-v="1"].active .txt{ color: var(--score-1); }

  .ket{ margin-top: 10px; }
  .ket input{
    width:100%; border: 1px solid var(--line); border-radius: var(--radius-s);
    padding: 9px 10px; font-size: 13.5px; font-family:'Inter';
    background: #FCFAF9;
  }
  .ket input::placeholder{ color: var(--ink-faint); }
  .ket input:focus{ border-color: var(--primary); background: var(--surface); }

  /* ---------- Summary ---------- */
  .summary{ margin-top: 30px; padding: 18px; }
  .summary h2{ font-size: 17px; margin: 0 0 14px; }
  .summary-grid{
    display:grid; grid-template-columns: repeat(2, 1fr); gap: 10px;
  }
  @media (min-width: 480px){ .summary-grid{ grid-template-columns: repeat(4, 1fr); } }
  .sum-cell{
    border: 1px solid var(--line); border-radius: var(--radius-s);
    padding: 10px; text-align:center;
  }
  .sum-cell .n{ font-family:'Oswald'; font-weight:600; font-size: 20px; }
  .sum-cell .l{ font-size: 11px; color: var(--ink-muted); margin-top:2px; }
  .sum-cell.c4{ border-color: var(--score-4); } .sum-cell.c4 .n{ color: var(--score-4); }
  .sum-cell.c3{ border-color: var(--score-3); } .sum-cell.c3 .n{ color: var(--score-3); }
  .sum-cell.c2{ border-color: var(--score-2); } .sum-cell.c2 .n{ color: var(--score-2); }
  .sum-cell.c1{ border-color: var(--score-1); } .sum-cell.c1 .n{ color: var(--score-1); }

  .total-row{
    margin-top: 14px; padding-top: 14px; border-top: 1px dashed var(--line);
    display:flex; align-items:center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
  }
  .total-left .n{ font-family:'Oswald'; font-weight:700; font-size: 30px; line-height:1; }
  .total-left .l{ font-size: 12.5px; color: var(--ink-muted); margin-top: 4px; }
  .predikat{
    font-family:'Oswald'; font-weight:600; font-size: 14px;
    padding: 8px 16px; border-radius: 999px;
    background: var(--ink-faint); color:#fff;
  }
  .predikat.p4{ background: var(--score-4); }
  .predikat.p3{ background: var(--score-3); }
  .predikat.p2{ background: var(--score-2); }
  .predikat.p1{ background: var(--score-1); }

  /* ---------- Signature ---------- */
  .sig-section{ margin-top: 20px; padding: 18px; }
  .sig-section h2{ font-size: 17px; margin: 0 0 4px; }
  .sig-hint{ font-size: 13px; color: var(--ink-muted); margin: 0 0 12px; }
  .sig-pad-wrap{
    position: relative;
    border: 1.5px dashed var(--line);
    border-radius: var(--radius-s);
    background: #FCFAF9;
    height: 170px;
    overflow: hidden;
  }
  #sigPad{ width:100%; height:100%; display:block; touch-action:none; cursor: crosshair; }
  .sig-placeholder{
    position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
    color: var(--ink-faint); font-size: 13.5px; pointer-events:none;
  }
  .sig-actions{ display:flex; justify-content: flex-end; margin-top: 10px; }
  .btn-sm{ flex: 0 0 auto; padding: 8px 14px; font-size: 13px; }
  .sig-name-preview{
    margin-top: 14px; padding-top: 12px; border-top: 1px dashed var(--line);
    font-size: 13.5px; color: var(--ink-muted);
  }
  .sig-name-preview strong{ color: var(--ink); font-family:'Oswald'; font-weight:500; }

  /* ---------- Actions ---------- */
  .actions{ display:flex; gap: 10px; margin-top: 22px; flex-wrap: wrap; }
  .btn{
    flex: 1 1 140px;
    border-radius: var(--radius-s);
    padding: 13px 16px;
    font-weight: 600; font-size: 14.5px;
    border: 1.5px solid transparent;
    display:flex; align-items:center; justify-content:center; gap:8px;
  }
  .btn svg{ width:16px; height:16px; }
  .btn-primary{ background: var(--primary); color:#fff; }
  .btn-primary:hover{ background: var(--primary-dark); }
  .btn-primary:disabled{ background: var(--ink-faint); cursor: progress; }
  .btn-ghost{ background: var(--surface); color: var(--ink); border-color: var(--line); }
  .btn-ghost:hover{ border-color: var(--ink-faint); }
  .btn-danger{ background: var(--surface); color: var(--score-1); border-color: #E9C9C4; }

  footer{ text-align:center; color: var(--ink-faint); font-size: 12px; margin: 34px 0 10px; }

  /* ---------- Sticky progress bar ---------- */
  .progress-bar{
    position: fixed; left:0; right:0; bottom:0;
    background: var(--surface);
    border-top: 1px solid var(--line);
    padding: 10px 16px calc(10px + env(safe-area-inset-bottom));
    display:flex; align-items:center; gap: 12px;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    z-index: 20;
  }
  .progress-track{
    flex:1; height: 7px; border-radius: 999px; background: var(--line); overflow:hidden;
  }
  .progress-fill{
    height:100%; width:0%; background: var(--primary);
    border-radius: 999px; transition: width 0.25s ease;
  }
  .progress-label{ font-size: 12.5px; color: var(--ink-muted); white-space:nowrap; font-family:'Oswald'; font-weight:500; }
</style>
</head>
<body>

<div class="top">
  <div class="top-inner wrap" style="padding:0;">
    <div class="top-eyebrow">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
      Dinas Gulkarmat &mdash; DKI Jakarta
    </div>
    <h1>Formulir Penilaian Evakuasi Penghuni</h1>
    <p>Penilaian umum kesiapan dan pelaksanaan evakuasi saat simulasi atau kondisi darurat kebakaran.</p>
  </div>
</div>

<div class="wrap">

  <div class="meta card-shell">
    <div class="field">
      <label for="observer">Nama Observer</label>
      <input type="text" id="observer" placeholder="Nama lengkap petugas">
    </div>
    <div class="field">
      <label for="tanggal">Tanggal</label>
      <input type="date" id="tanggal">
    </div>
    <div class="field">
      <label for="lokasi">Lokasi Observasi</label>
      <input type="text" id="lokasi" placeholder="Mis. Titik kumpul lantai dasar">
    </div>
    <div class="field">
      <label for="gedung">Gedung</label>
      <input type="text" id="gedung" placeholder="Nama gedung / bangunan">
    </div>
  </div>

  <div class="section-head">
    <h2>Penilaian Umum Evakuasi</h2>
    <span id="filledCount">0/13 diisi</span>
  </div>

  <div class="items" id="items"></div>

  <div class="summary card-shell">
    <h2>Ringkasan Nilai</h2>
    <div class="summary-grid">
      <div class="sum-cell c4"><div class="n" id="cnt4">0</div><div class="l">Sangat Baik</div></div>
      <div class="sum-cell c3"><div class="n" id="cnt3">0</div><div class="l">Baik</div></div>
      <div class="sum-cell c2"><div class="n" id="cnt2">0</div><div class="l">Cukup</div></div>
      <div class="sum-cell c1"><div class="n" id="cnt1">0</div><div class="l">Kurang</div></div>
    </div>
    <div class="total-row">
      <div class="total-left">
        <div class="n"><span id="totalNilai">0</span> / 52</div>
        <div class="l">Total nilai &middot; rata-rata <span id="rataRata">0.0</span></div>
      </div>
      <div class="predikat" id="predikat">Belum dinilai</div>
    </div>
  </div>

  <div class="sig-section card-shell">
    <h2>Tanda Tangan Observer</h2>
    <p class="sig-hint">Bubuhkan tanda tangan elektronik menggunakan jari atau mouse.</p>
    <div class="sig-pad-wrap">
      <canvas id="sigPad"></canvas>
      <div class="sig-placeholder" id="sigPlaceholder">Tanda tangan di sini</div>
    </div>
    <div class="sig-actions">
      <button class="btn btn-ghost btn-sm" id="btnClearSig">Hapus Tanda Tangan</button>
    </div>
    <div class="sig-name-preview">
      Nama yang akan dicetak di bawah tanda tangan: <strong id="sigNamePreview">&mdash;</strong>
    </div>
  </div>

  <div class="actions">
    <button class="btn btn-primary" id="btnPdf">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Unduh Hasil (PDF)
    </button>
    <button class="btn btn-danger" id="btnReset">Reset Formulir</button>
  </div>

  <footer>Formulir Penilaian Evakuasi Penghuni &mdash; Satuan Tugas Kelurahan Sukapura</footer>
</div>

<div class="progress-bar">
  <span class="progress-label" id="progressLabel">0%</span>
  <div class="progress-track"><div class="progress-fill" id="progressFill"></div></div>
  <span class="progress-label" id="progressScore">0/52</span>
</div>

<script>
(function(){
  const INDICATORS = [
    { title: "Kecepatan Respons Alarm Kebakaran", desc: "Waktu antara alarm berbunyi hingga penghuni mulai bergerak menuju jalur evakuasi." },
    { title: "Kualitas Suara Alarm dan Pemberitahuan Umum", desc: "Suara alarm dan pemberitahuan umum terdengar jelas pada saat general alarm." },
    { title: "Keteraturan Evakuasi", desc: "Penghuni bergerak tenang, tidak panik, dan mengikuti arahan." },
    { title: "Penggunaan Jalur Evakuasi", desc: "Penghuni menggunakan jalur yang ditentukan dan tidak terhambat." },
    { title: "Kondisi Tangga Darurat", desc: "Nilai kebersihan, pencahayaan, railing tangga 2 sisi, pintu besi dengan penutup otomatis dan batang panik, penanda arah evakuasi dan lantai serta suara pengumuman." },
    { title: "Penggunaan Tangga Darurat", desc: "Penghuni menggunakan tangga darurat dengan benar (tidak menggunakan lift)." },
    { title: "Aksesibilitas Jalur Evakuasi", desc: "Jalur evakuasi bebas hambatan dan penerangan cukup." },
    { title: "Keberadaan dan Kefahaman Tanda Arah", desc: "Tanda arah evakuasi terlihat jelas dan dipahami penghuni." },
    { title: "Kefahaman Titik Kumpul", desc: "Penghuni mengetahui dan menuju titik kumpul yang benar." },
    { title: "Kerapihan di Titik Kumpul", desc: "Penghuni berkumpul dengan tertib dan memudahkan perhitungan/pendataan." },
    { title: "Ketersediaan dan Fungsi Alat Pemadam Api Ringan (APAR)", desc: "APAR mudah dijangkau dan dalam kondisi baik (jika ada simulasi pemadaman)." },
    { title: "Penanganan Penghuni Berkebutuhan Khusus/Disabilitas", desc: "Adanya perhatian khusus dan bantuan yang memadai." },
    { title: "Sistem General Alarm", desc: "Diikuti dengan kondisi lif homming, pintu akses terbuka, pressurized fan berfungsi, alarm dan sistem pemberitahuan aktif serta lif kebakaran berfungsi." }
  ];
  const SCALE = [
    { v:4, label:"Sangat Baik" },
    { v:3, label:"Baik" },
    { v:2, label:"Cukup" },
    { v:1, label:"Kurang" }
  ];
  const SCORE_RGB = { 4:[30,122,76], 3:[14,124,134], 2:[193,121,10], 1:[171,42,30] };

  const state = {
    scores: new Array(INDICATORS.length).fill(0),
    ket: new Array(INDICATORS.length).fill("")
  };

  const itemsEl = document.getElementById('items');

  INDICATORS.forEach((item, i) => {
    const card = document.createElement('div');
    card.className = 'item';
    card.id = 'item-' + i;

    const top = document.createElement('div');
    top.className = 'item-top';
    top.innerHTML = `
      <div class="item-no">${i+1}</div>
      <div>
        <p class="item-title">${item.title}</p>
        <p class="item-desc">${item.desc}</p>
      </div>`;
    card.appendChild(top);

    const scale = document.createElement('div');
    scale.className = 'scale';
    SCALE.forEach(s => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.dataset.v = s.v;
      btn.setAttribute('aria-pressed', 'false');
      btn.innerHTML = `<span class="num">${s.v}</span><span class="txt">${s.label}</span>`;
      btn.addEventListener('click', () => setScore(i, s.v, card));
      scale.appendChild(btn);
    });
    card.appendChild(scale);

    const ket = document.createElement('div');
    ket.className = 'ket';
    ket.innerHTML = `<input type="text" placeholder="Keterangan (opsional)" data-idx="${i}">`;
    ket.querySelector('input').addEventListener('input', (e) => {
      state.ket[i] = e.target.value;
    });
    card.appendChild(ket);

    itemsEl.appendChild(card);
  });

  function setScore(idx, value, card){
    state.scores[idx] = (state.scores[idx] === value) ? 0 : value;
    card.classList.toggle('is-filled', state.scores[idx] !== 0);
    card.querySelectorAll('.scale button').forEach(b => {
      const active = Number(b.dataset.v) === state.scores[idx];
      b.classList.toggle('active', active);
      b.setAttribute('aria-pressed', String(active));
    });
    render();
  }

  const els = {
    cnt4: document.getElementById('cnt4'),
    cnt3: document.getElementById('cnt3'),
    cnt2: document.getElementById('cnt2'),
    cnt1: document.getElementById('cnt1'),
    total: document.getElementById('totalNilai'),
    rata: document.getElementById('rataRata'),
    predikat: document.getElementById('predikat'),
    filled: document.getElementById('filledCount'),
    pFill: document.getElementById('progressFill'),
    pLabel: document.getElementById('progressLabel'),
    pScore: document.getElementById('progressScore')
  };

  let lastCounts = {4:0,3:0,2:0,1:0}, lastTotal = 0, lastRata = 0, lastPredikatText = 'Belum dinilai';

  function render(){
    const counts = {4:0,3:0,2:0,1:0};
    let total = 0, answered = 0;
    state.scores.forEach(v => {
      if (v > 0){ counts[v]++; total += v; answered++; }
    });
    els.cnt4.textContent = counts[4];
    els.cnt3.textContent = counts[3];
    els.cnt2.textContent = counts[2];
    els.cnt1.textContent = counts[1];
    els.total.textContent = total;
    const rata = answered ? (total/answered) : 0;
    els.rata.textContent = rata.toFixed(1);
    els.filled.textContent = `${answered}/13 diisi`;

    els.predikat.className = 'predikat';
    let predikatText = 'Belum dinilai';
    if (answered === 0){
      predikatText = 'Belum dinilai';
    } else if (rata >= 3.5){
      predikatText = 'Sangat Baik'; els.predikat.classList.add('p4');
    } else if (rata >= 2.5){
      predikatText = 'Baik'; els.predikat.classList.add('p3');
    } else if (rata >= 1.5){
      predikatText = 'Cukup'; els.predikat.classList.add('p2');
    } else {
      predikatText = 'Kurang'; els.predikat.classList.add('p1');
    }
    els.predikat.textContent = predikatText;

    const pct = Math.round((answered/13)*100);
    els.pFill.style.width = pct + '%';
    els.pLabel.textContent = pct + '%';
    els.pScore.textContent = total + '/52';

    lastCounts = counts; lastTotal = total; lastRata = rata; lastPredikatText = predikatText;
  }
  render();

  // ---- Signature pad ----
  const canvas = document.getElementById('sigPad');
  const ctx = canvas.getContext('2d');
  const placeholder = document.getElementById('sigPlaceholder');
  let drawing = false, hasSignature = false;
  let lastPt = null;

  function resizeCanvas(){
    const rect = canvas.parentElement.getBoundingClientRect();
    const ratio = window.devicePixelRatio || 1;
    const prevData = hasSignature ? canvas.toDataURL() : null;
    canvas.width = rect.width * ratio;
    canvas.height = rect.height * ratio;
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
    ctx.lineWidth = 2.2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = '#221C1A';
    if (prevData){
      const img = new Image();
      img.onload = () => ctx.drawImage(img, 0, 0, rect.width, rect.height);
      img.src = prevData;
    }
  }
  resizeCanvas();
  window.addEventListener('resize', resizeCanvas);

  function getPos(e){
    const rect = canvas.getBoundingClientRect();
    const t = (e.touches && e.touches[0]) || e;
    return { x: t.clientX - rect.left, y: t.clientY - rect.top };
  }
  function start(e){
    e.preventDefault();
    drawing = true; hasSignature = true;
    placeholder.style.display = 'none';
    lastPt = getPos(e);
  }
  function move(e){
    if (!drawing) return;
    e.preventDefault();
    const p = getPos(e);
    ctx.beginPath();
    ctx.moveTo(lastPt.x, lastPt.y);
    ctx.lineTo(p.x, p.y);
    ctx.stroke();
    lastPt = p;
  }
  function end(){ drawing = false; }

  canvas.addEventListener('mousedown', start);
  canvas.addEventListener('mousemove', move);
  window.addEventListener('mouseup', end);
  canvas.addEventListener('touchstart', start, { passive:false });
  canvas.addEventListener('touchmove', move, { passive:false });
  canvas.addEventListener('touchend', end);

  function clearSignature(){
    hasSignature = false;
    placeholder.style.display = 'flex';
    resizeCanvas();
  }
  document.getElementById('btnClearSig').addEventListener('click', clearSignature);

  const observerInput = document.getElementById('observer');
  const sigNamePreview = document.getElementById('sigNamePreview');
  observerInput.addEventListener('input', () => {
    sigNamePreview.textContent = observerInput.value.trim() || '\u2014';
  });

  // ---- Actions ----
  document.getElementById('btnReset').addEventListener('click', () => {
    if (!confirm('Kosongkan seluruh isian formulir ini?')) return;
    state.scores.fill(0);
    state.ket.fill("");
    document.querySelectorAll('.item').forEach(card => {
      card.classList.remove('is-filled');
      card.querySelectorAll('.scale button').forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-pressed', 'false');
      });
      const inp = card.querySelector('.ket input');
      if (inp) inp.value = '';
    });
    document.getElementById('observer').value = '';
    document.getElementById('tanggal').value = '';
    document.getElementById('lokasi').value = '';
    document.getElementById('gedung').value = '';
    sigNamePreview.textContent = '\u2014';
    clearSignature();
    render();
  });

  // ---- Direct PDF download ----
  function formatTanggal(val){
    if (!val) return '-';
    const [y,m,d] = val.split('-');
    const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    if (!y||!m||!d) return val;
    return `${parseInt(d,10)} ${bulan[parseInt(m,10)-1]} ${y}`;
  }

  document.getElementById('btnPdf').addEventListener('click', function(){
    const btn = this;

    const emptyIdx = state.scores.findIndex(v => v === 0);
    if (emptyIdx !== -1){
      const targetCard = document.getElementById('item-' + emptyIdx);
      targetCard.scrollIntoView({ behavior:'smooth', block:'center' });
      targetCard.style.borderColor = 'var(--score-1)';
      setTimeout(() => { targetCard.style.borderColor = ''; }, 1600);
      alert(`Poin nomor ${emptyIdx + 1} ("${INDICATORS[emptyIdx].title}") belum dinilai. Semua 13 indikator wajib diisi sebelum PDF bisa dibuat (kolom Keterangan boleh dikosongkan).`);
      return;
    }

    const originalLabel = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Membuat PDF...';

    try{
      const { jsPDF } = window.jspdf;
      
      const doc = new jsPDF({ unit:'mm', format:'a4', compress:true });
      const pageW = doc.internal.pageSize.getWidth();
      
      // MARGIN KIRI KANAN DIKECILKAN MENJADI 10mm
      const marginX = 10;
      
      // MULAI DARI SANGAT ATAS (10mm)
      let y = 10;

      const observer = document.getElementById('observer').value.trim() || '-';
      const tanggal = document.getElementById('tanggal').value;
      const lokasi = document.getElementById('lokasi').value.trim() || '-';
      const gedung = document.getElementById('gedung').value.trim() || '-';

      // Title & subtitle - Ukuran lebih kecil dan rapat
      doc.setFont('helvetica','bold');
      doc.setFontSize(13);
      doc.setTextColor(34,28,26);
      doc.text('Hasil Penilaian Simulasi Kebakaran', pageW/2, y, { align:'center' });
      y += 5; 
      doc.setFontSize(10);
      doc.setTextColor(171,42,30);
      doc.text('Penilaian Umum Evakuasi', pageW/2, y, { align:'center' });
      y += 3;
      doc.setDrawColor(230,220,214);
      doc.setLineWidth(0.4);
      doc.line(marginX, y, pageW-marginX, y);
      y += 5;

      // Meta info (2 columns) - Ukuran lebih kecil
      doc.setFont('helvetica','normal');
      doc.setFontSize(8.5);
      doc.setTextColor(34,28,26);
      const colW = (pageW - marginX*2)/2;
      const metaRow = (label, value, x, yy) => {
        doc.setFont('helvetica','bold');
        doc.text(label + ' :', x, yy);
        doc.setFont('helvetica','normal');
        doc.text(String(value), x + 30, yy);
      };
      metaRow('Nama Observer', observer, marginX, y);
      metaRow('Tanggal', formatTanggal(tanggal), marginX + colW, y);
      y += 5; 
      metaRow('Lokasi Observasi', lokasi, marginX, y);
      metaRow('Gedung', gedung, marginX + colW, y);
      y += 6;

      // Table - Modifikasi teks agar tidak terlalu panjang memakan baris
      const body = INDICATORS.map((item, i) => [
        String(i+1),
        item.title + '\n' + item.desc,
        state.scores[i] === 4 ? 'v' : '',
        state.scores[i] === 3 ? 'v' : '',
        state.scores[i] === 2 ? 'v' : '',
        state.scores[i] === 1 ? 'v' : '',
        state.ket[i] || ''
      ]);

      doc.autoTable({
        startY: y,
        margin: { left: marginX, right: marginX },
        head: [['No', 'Indikator Penilaian', 'Sangat\nBaik (4)', 'Baik\n(3)', 'Cukup\n(2)', 'Kurang\n(1)', 'Keterangan']],
        body: body,
        foot: [[
          '', 'Total Nilai',
          String(lastCounts[4] || ''), String(lastCounts[3] || ''),
          String(lastCounts[2] || ''), String(lastCounts[1] || ''), ''
        ]],
        // UKURAN FONT & PADDING TABEL DIKECILKAN DRASTIS
        styles: { font:'helvetica', fontSize: 7, cellPadding: 1.2, valign:'middle', overflow:'linebreak', textColor:[34,28,26], lineColor:[225,216,210], lineWidth:0.15 },
        headStyles: { fillColor:[171,42,30], textColor:255, fontStyle:'bold', halign:'center', fontSize: 7.5 },
        footStyles: { fillColor:[247,244,241], textColor:[34,28,26], fontStyle:'bold', halign:'center', fontSize: 7.5 },
        // LEBAR KOLOM INDIKATOR DIBESARKAN AGAR TEKS TIDAK BANYAK WRAP KE BAWAH
        columnStyles: {
          0: { cellWidth: 6, halign:'center' },
          1: { cellWidth: 85 }, // Lebar deskripsi indikator dibuat sangat dominan
          2: { cellWidth: 12, halign:'center' },
          3: { cellWidth: 12, halign:'center' },
          4: { cellWidth: 12, halign:'center' },
          5: { cellWidth: 12, halign:'center' },
          6: { cellWidth: 'auto' } // Sisa ruang untuk Keterangan
        },
        didParseCell: function(data){
          if (data.section === 'body' && data.column.index >= 2 && data.column.index <= 5){
            const scoreForCol = { 2:4, 3:3, 4:2, 5:1 }[data.column.index];
            if (state.scores[data.row.index] === scoreForCol){
              data.cell.styles.fontStyle = 'bold';
              data.cell.styles.textColor = SCORE_RGB[scoreForCol];
              data.cell.styles.fontSize = 9; 
            }
          }
        }
      });

      let afterTableY = doc.lastAutoTable.finalY + 4;

      doc.setFont('helvetica','bold');
      doc.setFontSize(8.5);
      doc.setTextColor(34,28,26);
      doc.text(`Total Nilai: ${lastTotal} / 52   |   Rata-rata: ${lastRata.toFixed(1)}   |   Predikat: ${lastPredikatText}`, marginX, afterTableY);

      // Signature block - lebih rapat dan pendek
      let sigY = afterTableY + 8; 
      const sigBoxW = 70;
      const sigBoxX = pageW - marginX - sigBoxW;
      
      doc.setFont('helvetica','normal');
      doc.setFontSize(9); 
      const todayLabel = tanggal ? formatTanggal(tanggal) : formatTanggal(new Date().toISOString().slice(0,10));
      doc.text(`Jakarta, ${todayLabel}`, sigBoxX + sigBoxW/2, sigY, { align:'center' });
      sigY += 4.5; 
      doc.text('Observer,', sigBoxX + sigBoxW/2, sigY, { align:'center' });

      const sigImgTopY = sigY + 2;
      let drawH = 0;
      if (hasSignature){
        const dataUrl = canvas.toDataURL('image/png');
        const natW = canvas.width, natH = canvas.height;
        const boxW = sigBoxW, boxH = 16; // Tanda tangan dibuat lebih pendek
        let drawW = boxW; drawH = (natH/natW) * boxW;
        if (drawH > boxH){ drawH = boxH; drawW = (natW/natH) * boxH; }
        doc.addImage(dataUrl, 'PNG', sigBoxX + (sigBoxW-drawW)/2, sigImgTopY, drawW, drawH);
      }

      const lineY = sigImgTopY + (drawH > 0 ? drawH + 3 : 12);
      doc.setDrawColor(34,28,26);
      doc.setLineWidth(0.25);
      doc.line(sigBoxX, lineY, sigBoxX + sigBoxW, lineY);
      doc.setFont('helvetica','bold');
      doc.setFontSize(9);
      doc.text(observer, sigBoxX + sigBoxW/2, lineY + 4, { align:'center' });

      const fileParts = ['hasil-penilaian-evakuasi'];
      if (gedung && gedung !== '-') fileParts.push(gedung.toLowerCase().replace(/[^a-z0-9]+/g,'-'));
      if (tanggal) fileParts.push(tanggal);
      doc.save(fileParts.join('_') + '.pdf');
    } catch(err){
      console.error(err);
      alert('Terjadi kesalahan saat membuat PDF. Coba lagi.');
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalLabel;
    }
  });
})();
</script>
</body>
</html>
