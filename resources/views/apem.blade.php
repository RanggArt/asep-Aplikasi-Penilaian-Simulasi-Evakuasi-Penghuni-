<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>APEM — Aplikasi Pengesahan MKKG</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    --ok: #1E7A4C;
    --ok-tint: #E7F4EC;
    --err: #B3261E;
    --err-tint: #FBEAE7;
    --radius-s: 6px;
    --radius-m: 10px;
    --shadow: 0 1px 2px rgba(34,28,26,0.06), 0 2px 10px rgba(34,28,26,0.05);
  }
  *{ box-sizing: border-box; }
  html{ -webkit-text-size-adjust: 100%; }
  body{
    margin:0; background: var(--bg); color: var(--ink);
    font-family: 'Inter', -apple-system, sans-serif; line-height: 1.5;
    padding-bottom: 96px;
  }
  h1,h2,h3{ font-family:'Oswald','Inter',sans-serif; }
  button{ cursor:pointer; font-family:inherit; }
  :focus-visible{ outline: 2px solid var(--accent); outline-offset: 2px; }
  @media (prefers-reduced-motion: reduce){ *{ animation-duration:0.001ms !important; transition-duration:0.001ms !important; } }

  /* ---------- Header ---------- */
  .top{
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color:#fff; padding: 22px 18px 34px; position: relative; overflow: hidden;
  }
  .top::after{ content:""; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background: rgba(255,255,255,0.06); }
  .top::before{ content:""; position:absolute; left:-30px; bottom:-60px; width:140px; height:140px; border-radius:50%; background: rgba(255,255,255,0.05); }
  .top-inner{ max-width: 760px; margin:0 auto; position: relative; opacity:0; transform: translateY(8px); animation: rise 0.55s ease forwards; }
  @keyframes rise{ to{ opacity:1; transform: translateY(0); } }
  .top-eyebrow{ display:flex; align-items:center; gap:8px; font-size:12.5px; color: rgba(255,255,255,0.85); margin-bottom:10px; }
  .top-eyebrow svg{ width:16px; height:16px; flex-shrink:0; }
  h1{ margin:0 0 6px; font-size: clamp(24px,6vw,32px); font-weight:600; letter-spacing:0.01em; line-height:1.15; }
  .top p{ margin:0; color: rgba(255,255,255,0.82); font-size:14.5px; max-width:52ch; }

  /* ---------- Layout ---------- */
  .wrap{ max-width: 760px; margin:0 auto; padding: 0 16px; }
  .card-shell{ background: var(--surface); border-radius: var(--radius-m); border:1px solid var(--line); box-shadow: var(--shadow); }

  .section-head{ display:flex; align-items:baseline; justify-content: space-between; margin: 30px 0 12px; padding: 0 2px; }
  .section-head h2{ font-size:18px; font-weight:600; margin:0; }
  .section-head span{ font-size:13px; color: var(--ink-faint); }
  .section-sub{ font-size: 13px; color: var(--ink-muted); margin: -6px 2px 14px; }

  .meta{ padding:18px; display:grid; gap:14px; grid-template-columns: 1fr; }
  @media (min-width:620px){ .meta{ grid-template-columns: 1fr 1fr; } }
  .meta.cols-1{ grid-template-columns: 1fr; }
  .field{ display:flex; flex-direction:column; gap:6px; }
  .field.span-2{ grid-column: 1 / -1; }
  .field label{ font-size:12.5px; font-weight:600; color: var(--ink-muted); }
  .field label .req{ color: var(--primary); }
  .field input, .field select{
    border:1px solid var(--line); border-radius: var(--radius-s); padding: 11px 12px;
    font-size:15px; color: var(--ink); background: var(--surface); font-family:'Inter',sans-serif;
    width:100%;
  }
  .field input:focus, .field select:focus{ border-color: var(--primary); }
  .field select{ appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23756762' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position: right 12px center; padding-right: 34px; }
  .field-error{ font-size:12px; color: var(--err); display:none; }
  .field.has-error input, .field.has-error select{ border-color: var(--err); }
  .field.has-error .field-error{ display:block; }

  /* ---------- File cards ---------- */
  .file-grid{ display:flex; flex-direction:column; gap:12px; }
  .file-card{
    display:flex; align-items:center; gap:14px;
    background: var(--surface); border: 1.5px dashed var(--line);
    border-radius: var(--radius-m); padding:14px; cursor:pointer;
    transition: border-color 0.15s ease, background 0.15s ease;
  }
  .file-card:hover{ border-color: var(--primary); background: #FDFBFA; }
  .file-card.is-dragover{ border-color: var(--primary); background: var(--primary-tint); }
  .file-card.is-filled{ border-style: solid; border-color: var(--ok); background: var(--ok-tint); }
  .file-card.is-error{ border-style: solid; border-color: var(--err); background: var(--err-tint); }

  .file-icon{
    flex-shrink:0; width:42px; height:42px; border-radius:10px;
    background: var(--primary-tint); color: var(--primary-dark);
    display:flex; align-items:center; justify-content:center;
  }
  .file-card.is-filled .file-icon{ background: var(--ok-tint); color: var(--ok); }
  .file-card.is-error .file-icon{ background: var(--err-tint); color: var(--err); }
  .file-icon svg{ width:20px; height:20px; }

  .file-body{ flex:1; min-width:0; }
  .file-title{ font-weight:600; font-size:14px; margin:0 0 2px; color: var(--ink); }
  .file-title .req{ color: var(--primary); }
  .file-hint{ font-size:11.5px; color: var(--ink-faint); margin:0; }
  .file-status{ font-size:12.5px; color: var(--ink-muted); margin: 4px 0 0; word-break: break-word; }
  .file-card.is-filled .file-status{ color: var(--ok); font-weight:500; }
  .file-card.is-error .file-status{ color: var(--err); font-weight:500; }

  .file-action{
    flex-shrink:0; font-size:12.5px; font-weight:600; color: var(--primary);
    border: 1.5px solid var(--primary); border-radius: 999px; padding: 7px 14px;
    background: var(--surface); white-space:nowrap;
  }
  .file-input-hidden{ position:absolute; width:1px; height:1px; opacity:0; overflow:hidden; }

  /* ---------- Summary / submit ---------- */
  .size-summary{
    margin-top: 18px; padding: 14px 16px; display:flex; align-items:center; justify-content:space-between; gap:12px;
  }
  .size-summary .l{ font-size:13px; color: var(--ink-muted); }
  .size-summary .n{ font-family:'Oswald'; font-weight:600; font-size:16px; color: var(--ink); }

  .actions{ display:flex; gap:10px; margin-top:22px; flex-wrap:wrap; }
  .btn{ flex:1 1 140px; border-radius: var(--radius-s); padding:13px 16px; font-weight:600; font-size:14.5px; border:1.5px solid transparent; display:flex; align-items:center; justify-content:center; gap:8px; }
  .btn svg{ width:16px; height:16px; }
  .btn-primary{ background: var(--primary); color:#fff; }
  .btn-primary:hover{ background: var(--primary-dark); }
  .btn-primary:disabled{ background: var(--ink-faint); cursor: progress; }
  .btn-ghost{ background: var(--surface); color: var(--ink); border-color: var(--line); }
  .btn-ghost:hover{ border-color: var(--ink-faint); }

  .success-panel{ display:none; padding:22px; text-align:center; }
  .success-panel.is-visible{ display:block; }
  .success-icon{ width:56px; height:56px; border-radius:50%; background: var(--ok-tint); color: var(--ok); display:flex; align-items:center; justify-content:center; margin: 0 auto 14px; }
  .success-icon svg{ width:28px; height:28px; }
  .success-panel h2{ margin:0 0 6px; font-size:18px; }
  .success-panel p{ font-size:13.5px; color: var(--ink-muted); margin:0 0 16px; }
  .success-list{ text-align:left; font-size:12.5px; color: var(--ink-muted); background: var(--bg); border-radius: var(--radius-s); padding:14px; margin: 0 0 16px; }
  .success-list div{ display:flex; justify-content:space-between; gap:10px; padding:4px 0; border-bottom:1px dashed var(--line); }
  .success-list div:last-child{ border-bottom:none; }
  .success-list b{ color: var(--ink); font-weight:600; }

  footer{ text-align:center; color: var(--ink-faint); font-size:12px; margin:34px 0 10px; }

  /* ---------- Sticky progress bar ---------- */
  .progress-bar{
    position: fixed; left:0; right:0; bottom:0; background: var(--surface); border-top:1px solid var(--line);
    padding: 10px 16px calc(10px + env(safe-area-inset-bottom)); display:flex; align-items:center; gap:12px;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.05); z-index:20;
  }
  .progress-track{ flex:1; height:7px; border-radius:999px; background: var(--line); overflow:hidden; }
  .progress-fill{ height:100%; width:0%; background: var(--primary); border-radius:999px; transition: width 0.25s ease; }
  .progress-label{ font-size:12.5px; color: var(--ink-muted); white-space:nowrap; font-family:'Oswald'; font-weight:500; }
</style>
</head>
<body>

<div class="top">
  <div class="top-inner wrap" style="padding:0;">
    <div class="top-eyebrow">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>
      Sudin Penanggulangan Kebakaran dan Penyelamatan Kota Administrasi Jakarta Utara
    </div>
    <h1>APEM &mdash; Aplikasi Pengesahan MKKG</h1>
    <p>Tahapan dan persyaratan pengesahan Manajemen Keselamatan Kebakaran Gedung (MKKG). Lengkapi data dan unggah seluruh dokumen persyaratan di bawah ini.</p>
  </div>
</div>

<div class="wrap">

  <form id="apemForm">

    <div class="section-head"><h2>Data Pendaftar</h2></div>
    <div class="meta card-shell">
      <div class="field" id="f-nama_pendaftar">
        <label for="nama_pendaftar">Nama <span class="req">*</span></label>
        <input type="text" id="nama_pendaftar" placeholder="Nama lengkap pendaftar" required>
        <span class="field-error">Nama wajib diisi.</span>
      </div>
      <div class="field" id="f-jabatan">
        <label for="jabatan">Bagian / Jabatan <span class="req">*</span></label>
        <input type="text" id="jabatan" placeholder="Mis. FSM / Building Manager" required>
        <span class="field-error">Bagian/Jabatan wajib diisi.</span>
      </div>
      <div class="field" id="f-email">
        <label for="email">Email <span class="req">*</span></label>
        <input type="email" id="email" value="{{ auth()->user()->email }}" readonly required>
        <span class="field-error">Email yang valid wajib diisi.</span>
      </div>
      <div class="field" id="f-tanggal">
        <label for="tanggal">Tanggal <span class="req">*</span></label>
        <input type="date" id="tanggal" required>
        <span class="field-error">Tanggal wajib diisi.</span>
      </div>
    </div>

    <div class="section-head"><h2>Data Bangunan Gedung</h2></div>
    <div class="meta card-shell">
      <div class="field span-2" id="f-nama_gedung">
        <label for="nama_gedung">Nama Bangunan Gedung <span class="req">*</span></label>
        <input type="text" id="nama_gedung" placeholder="Nama gedung / bangunan" required>
        <span class="field-error">Nama Bangunan Gedung wajib diisi.</span>
      </div>
      <div class="field span-2" id="f-alamat">
        <label for="alamat">Alamat <span class="req">*</span></label>
        <input type="text" id="alamat" placeholder="Alamat lengkap bangunan" required>
        <span class="field-error">Alamat wajib diisi.</span>
      </div>
      <div class="field" id="f-telepon">
        <label for="telepon">No Tlp / HP <span class="req">*</span></label>
        <input type="tel" id="telepon" placeholder="08xxxxxxxxxx" required>
        <span class="field-error">No Tlp/HP wajib diisi.</span>
      </div>
      <div class="field" id="f-kota">
        <label for="kota">Kota Administrasi <span class="req">*</span></label>
        <select id="kota" required>
          <option value="" disabled selected>Pilih kota administrasi</option>
          <option>Jakarta Pusat</option>
          <option>Jakarta Utara</option>
          <option>Jakarta Barat</option>
          <option>Jakarta Selatan</option>
          <option>Jakarta Timur</option>
        </select>
        <span class="field-error">Kota Administrasi wajib dipilih.</span>
      </div>
    </div>

    <div class="section-head"><h2>Kelengkapan Dokumen</h2><span id="fileFilledCount">0/10 diisi</span></div>
    <p class="section-sub">Setiap dokumen maksimal 10&nbsp;MB. Ukuran dan jenis file akan divalidasi otomatis agar tidak membebani penyimpanan.</p>
    <div class="file-grid" id="fileGrid"></div>

    <div class="size-summary card-shell">
      <span class="l">Total ukuran seluruh lampiran</span>
      <span class="n" id="totalSize">0 MB</span>
    </div>

    <div class="actions">
      <button type="submit" class="btn btn-primary" id="btnSubmit">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        Kirim Permohonan
      </button>
      <button type="button" class="btn btn-ghost" id="btnReset">Reset Formulir</button>
    </div>
  </form>

  <div class="card-shell success-panel" id="successPanel">
    <div class="success-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <h2>Permohonan Berhasil Dikirim</h2>
    <p>Data dan dokumen sudah tersimpan. Anda dapat memantau statusnya melalui dashboard.</p>
    <div class="success-list" id="successList"></div>
    <a href="{{ route('dashboard') }}" class="btn btn-ghost" style="max-width:260px;margin:0 auto;">Kembali ke Dashboard</a>
  </div>

  <footer>APEM &mdash; Aplikasi Pengesahan MKKG &mdash; Sudin Penanggulangan Kebakaran dan Penyelamatan Kota Administrasi Jakarta Utara</footer>
</div>

<div class="progress-bar">
  <span class="progress-label" id="progressLabel">0%</span>
  <div class="progress-track"><div class="progress-fill" id="progressFill"></div></div>
  <span class="progress-label" id="progressCount">0/17</span>
</div>

<script>
(function(){
  const MAX_SIZE = 10 * 1024 * 1024; // 10 MB per file — keeps future DB/storage payloads small

  const FILES = [
    { id:"sertifikat_fsm", title:"Sertifikat FSM", types:[".pdf"], typeLabel:"PDF" },
    { id:"surat_penunjukan_fsm", title:"Surat Penunjukan FSM", types:[".pdf"], typeLabel:"PDF" },
    { id:"surat_permohonan", title:"Surat Permohonan Pengesahan MKKG", types:[".pdf"], typeLabel:"PDF" },
    { id:"program_kerja", title:"Program Kerja MKKG", types:[".pdf"], typeLabel:"PDF" },
    { id:"struktur_organisasi", title:"Struktur Organisasi MKKG", types:[".pdf",".dwg",".jpg",".jpeg",".png"], typeLabel:"PDF atau Drawing" },
    { id:"tugas_fungsi", title:"Tugas dan Fungsi MKKG", types:[".pdf"], typeLabel:"PDF" },
    { id:"koordinasi", title:"Koordinasi", types:[".pdf",".dwg",".jpg",".jpeg",".png"], typeLabel:"PDF, Drawing, atau Image" },
    { id:"sarana_prasarana", title:"Sarana dan Prasarana MKKG", types:[".pdf",".dwg",".jpg",".jpeg",".png"], typeLabel:"PDF, Drawing, atau Image" },
    { id:"sop_rdtk", title:"Standar Operasional Prosedur dan RDTK", types:[".pdf"], typeLabel:"PDF" },
    { id:"pelatihan_simulasi", title:"Pelatihan dan Simulasi Evakuasi Kebakaran", types:[".pdf"], typeLabel:"PDF" }
  ];

  const ICON_DOC = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
  const ICON_CHECK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
  const ICON_ALERT = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';

  const fileState = {}; // id -> { file, valid }

  function formatSize(bytes){
    if (bytes < 1024*1024) return (bytes/1024).toFixed(0) + ' KB';
    return (bytes/(1024*1024)).toFixed(2) + ' MB';
  }

  const grid = document.getElementById('fileGrid');
  FILES.forEach(f => {
    const card = document.createElement('label');
    card.className = 'file-card';
    card.id = 'card-' + f.id;
    card.setAttribute('for', 'input-' + f.id);
    card.innerHTML = `
      <div class="file-icon">${ICON_DOC}</div>
      <div class="file-body">
        <p class="file-title">${f.title} <span class="req">*</span></p>
        <p class="file-hint">${f.typeLabel} &middot; Maks 10 MB</p>
        <p class="file-status" id="status-${f.id}">Belum ada file dipilih</p>
      </div>
      <span class="file-action">Pilih File</span>
      <input type="file" id="input-${f.id}" class="file-input-hidden" accept="${f.types.join(',')}">
    `;
    grid.appendChild(card);

    const input = card.querySelector('input');

    function handleFiles(fileList){
      const file = fileList && fileList[0];
      const statusEl = document.getElementById('status-' + f.id);
      if (!file) return;
      const ext = '.' + file.name.split('.').pop().toLowerCase();
      const okType = f.types.includes(ext);
      const okSize = file.size <= MAX_SIZE;

      if (!okType){
        fileState[f.id] = { file: null, valid: false };
        card.classList.remove('is-filled'); card.classList.add('is-error');
        statusEl.textContent = `Jenis file tidak didukung. Gunakan ${f.typeLabel}.`;
        input.value = '';
      } else if (!okSize){
        fileState[f.id] = { file: null, valid: false };
        card.classList.remove('is-filled'); card.classList.add('is-error');
        statusEl.textContent = `Ukuran file ${formatSize(file.size)} melebihi batas 10 MB.`;
        input.value = '';
      } else {
        fileState[f.id] = { file: file, valid: true };
        card.classList.remove('is-error'); card.classList.add('is-filled');
        statusEl.textContent = `${file.name} (${formatSize(file.size)})`;
      }
      updateProgress();
    }

    input.addEventListener('change', () => handleFiles(input.files));

    card.addEventListener('dragover', (e) => { e.preventDefault(); card.classList.add('is-dragover'); });
    card.addEventListener('dragleave', () => card.classList.remove('is-dragover'));
    card.addEventListener('drop', (e) => {
      e.preventDefault();
      card.classList.remove('is-dragover');
      if (e.dataTransfer.files && e.dataTransfer.files.length){
        input.files = e.dataTransfer.files;
        handleFiles(input.files);
      }
    });
  });

  // ---- Text/select fields ----
  const TEXT_FIELDS = ['nama_pendaftar','jabatan','email','tanggal','nama_gedung','alamat','telepon','kota'];
  TEXT_FIELDS.forEach(id => {
    document.getElementById(id).addEventListener('input', updateProgress);
    document.getElementById(id).addEventListener('change', updateProgress);
  });
  document.getElementById('tanggal').valueAsDate = new Date();

  const els = {
    fileFilled: document.getElementById('fileFilledCount'),
    pFill: document.getElementById('progressFill'),
    pLabel: document.getElementById('progressLabel'),
    pCount: document.getElementById('progressCount'),
    totalSize: document.getElementById('totalSize')
  };

  function updateProgress(){
    let filled = 0;
    const total = TEXT_FIELDS.length + FILES.length;
    TEXT_FIELDS.forEach(id => { if (document.getElementById(id).value.trim() !== '') filled++; });
    let filesFilled = 0, totalBytes = 0;
    FILES.forEach(f => {
      const st = fileState[f.id];
      if (st && st.valid){ filesFilled++; filled++; totalBytes += st.file.size; }
    });
    els.fileFilled.textContent = `${filesFilled}/${FILES.length} diisi`;
    els.totalSize.textContent = formatSize(totalBytes);
    const pct = Math.round((filled/total)*100);
    els.pFill.style.width = pct + '%';
    els.pLabel.textContent = pct + '%';
    els.pCount.textContent = `${filled}/${total}`;
  }
  updateProgress();

  // ---- Validation + submit ----
  function clearFieldError(id){
    document.getElementById('f-' + id)?.classList.remove('has-error');
  }
  function setFieldError(id){
    document.getElementById('f-' + id)?.classList.add('has-error');
  }

  document.getElementById('apemForm').addEventListener('submit', function(e){
    e.preventDefault();
    let firstInvalid = null;

    const textMap = [
      ['nama_pendaftar','nama_pendaftar'], ['jabatan','jabatan'], ['email','email'], ['tanggal','tanggal'],
      ['nama_gedung','nama_gedung'], ['alamat','alamat'], ['telepon','telepon'], ['kota','kota']
    ];
    textMap.forEach(([fieldKey, elId]) => {
      const el = document.getElementById(elId);
      if (el.value.trim() === ''){
        setFieldError(fieldKey);
        if (!firstInvalid) firstInvalid = document.getElementById('f-' + fieldKey);
      } else {
        clearFieldError(fieldKey);
      }
    });

    let firstInvalidFileCard = null;
    FILES.forEach(f => {
      const st = fileState[f.id];
      const card = document.getElementById('card-' + f.id);
      if (!st || !st.valid){
        card.classList.add('is-error');
        if (!firstInvalidFileCard) firstInvalidFileCard = card;
      }
    });

    const target = firstInvalid || firstInvalidFileCard;
    if (target){
      target.scrollIntoView({ behavior:'smooth', block:'center' });
      return;
    }

    // Semua data & dokumen valid — siapkan FormData untuk dikirim ke backend/API.
    const formData = new FormData();
    formData.append('nama_pendaftar', document.getElementById('nama_pendaftar').value.trim());
    formData.append('jabatan', document.getElementById('jabatan').value.trim());
    formData.append('email', document.getElementById('email').value.trim());
    formData.append('tanggal', document.getElementById('tanggal').value);
    formData.append('nama_gedung', document.getElementById('nama_gedung').value.trim());
    formData.append('alamat', document.getElementById('alamat').value.trim());
    formData.append('telepon', document.getElementById('telepon').value.trim());
    formData.append('kota', document.getElementById('kota').value);
    FILES.forEach(f => formData.append(f.id, fileState[f.id].file, fileState[f.id].file.name));

    const submitButton = document.getElementById('btnSubmit');
    submitButton.disabled = true;

    fetch('{{ route('apem.store') }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      body: formData
    })
      .then(async response => {
        const data = await response.json();
        if (!response.ok) {
          throw new Error(Object.values(data.errors || {}).flat().join('\n') || data.message || 'Permohonan gagal dikirim.');
        }
        showSuccess();
      })
      .catch(error => alert(error.message || 'Permohonan gagal dikirim. Silakan coba lagi.'))
      .finally(() => { submitButton.disabled = false; });
  });

  function showSuccess(){
    const rows = [
      ['Nama', document.getElementById('nama_pendaftar').value.trim()],
      ['Bagian / Jabatan', document.getElementById('jabatan').value.trim()],
      ['Email', document.getElementById('email').value.trim()],
      ['Tanggal', document.getElementById('tanggal').value],
      ['Nama Bangunan Gedung', document.getElementById('nama_gedung').value.trim()],
      ['Kota Administrasi', document.getElementById('kota').value],
      ['Jumlah Dokumen', `${FILES.length}/${FILES.length} terlampir`],
      ['Total Ukuran', els.totalSize.textContent]
    ];
    document.getElementById('successList').innerHTML = rows.map(([l,v]) =>
      `<div><span>${l}</span><b>${v}</b></div>`
    ).join('');
    document.getElementById('apemForm').style.display = 'none';
    document.getElementById('successPanel').classList.add('is-visible');
    window.scrollTo({ top:0, behavior:'smooth' });
  }

  document.getElementById('btnReset').addEventListener('click', () => {
    if (!confirm('Kosongkan seluruh isian formulir ini?')) return;
    document.getElementById('apemForm').reset();
    document.getElementById('tanggal').valueAsDate = new Date();
    Object.keys(fileState).forEach(id => delete fileState[id]);
    FILES.forEach(f => {
      const card = document.getElementById('card-' + f.id);
      card.classList.remove('is-filled','is-error');
      document.getElementById('status-' + f.id).textContent = 'Belum ada file dipilih';
      clearFieldError(f.id);
    });
    TEXT_FIELDS.forEach(clearFieldError);
    updateProgress();
  });
})();
</script>
</body>
</html>
