<!DOCTYPE html>
<html lang="id">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title>APEM Admin — Panel Pengesahan MKKG</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #F7F4F1; --surface: #FFFFFF; --ink: #221C1A; --ink-muted: #756762; --ink-faint: #A79A95;
    --line: #E7DFDA; --primary: #AB2A1E; --primary-dark: #7C1E15; --primary-tint: #FBEAE7;
    --ok: #1E7A4C; --ok-tint: #E7F4EC; --err: #B3261E; --err-tint: #FBEAE7;
    --warn: #C1790A; --warn-tint: #FBF0DE;
    --radius-s: 6px; --radius-m: 10px; --shadow: 0 1px 2px rgba(34,28,26,0.06), 0 2px 10px rgba(34,28,26,0.05);
  }
  *{ box-sizing: border-box; }
  body{ margin:0; background: var(--bg); color: var(--ink); font-family:'Inter',-apple-system,sans-serif; line-height:1.5; }
  h1,h2,h3{ font-family:'Oswald','Inter',sans-serif; }
  button{ cursor:pointer; font-family:inherit; }
  :focus-visible{ outline: 2px solid var(--primary); outline-offset:2px; }
  @media (prefers-reduced-motion: reduce){ *{ animation-duration:0.001ms !important; transition-duration:0.001ms !important; } }

  .top{ background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color:#fff; padding: 20px 18px 26px; }
  .top-inner{ max-width: 1040px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap; }
  .top-eyebrow{ font-size:12px; color: rgba(255,255,255,0.8); font-family:'JetBrains Mono',monospace; margin-bottom:6px; }
  .top h1{ margin:0; font-size: clamp(20px,4vw,26px); font-weight:600; }
  .admin-account{ display:flex; align-items:center; gap:14px; padding:10px 12px; border:1px solid rgba(255,255,255,0.24); border-radius:10px; background:rgba(255,255,255,0.08); }
  .admin-account-info{ display:grid; gap:3px; font-size:12px; }
  .admin-account-name{ font-size:14px; font-weight:600; }
  .admin-account-meta{ color:rgba(255,255,255,0.82); }
  .admin-account-badges{ display:flex; gap:6px; flex-wrap:wrap; }
  .account-badge{ display:inline-block; padding:2px 8px; border-radius:999px; background:rgba(255,255,255,0.16); font-size:10px; font-weight:600; }
  .account-badge.active{ background:#DDF5E5; color:#17613A; }
  .logout-button{ border:1px solid rgba(255,255,255,0.55); border-radius:7px; padding:8px 11px; color:#fff; background:transparent; font-size:12px; font-weight:600; white-space:nowrap; }
  .logout-button:hover{ background:rgba(255,255,255,0.14); }

  .wrap{ max-width: 1040px; margin: -12px auto 40px; padding: 0 16px; }

  .demo-notice{
    background: var(--ok-tint); border:1px solid #1E7A4C; color:#1E7A4C;
    border-radius: var(--radius-s); padding: 10px 14px; font-size:12.5px; margin-bottom:18px; font-weight: 500;
  }

  .stats{ display:grid; grid-template-columns: repeat(2,1fr); gap:10px; margin-bottom:20px; }
  @media (min-width:640px){ .stats{ grid-template-columns: repeat(4,1fr); } }
  .stat-card{ background: var(--surface); border:1px solid var(--line); border-radius: var(--radius-m); padding:14px; box-shadow: var(--shadow); }
  .stat-card .n{ font-family:'Oswald'; font-weight:600; font-size:24px; }
  .stat-card .l{ font-size:12px; color: var(--ink-muted); margin-top:2px; }
  .stat-card.pending .n{ color: var(--warn); }
  .stat-card.approved .n{ color: var(--ok); }
  .stat-card.rejected .n{ color: var(--err); }

  .toolbar{ display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
  .toolbar input, .toolbar select{
    border:1px solid var(--line); border-radius: var(--radius-s); padding:9px 12px; font-size:13.5px;
    background: var(--surface); font-family:'Inter',sans-serif;
  }
  .toolbar input{ flex:1 1 220px; }

  .list-card{ background: var(--surface); border:1px solid var(--line); border-radius: var(--radius-m); box-shadow: var(--shadow); overflow:hidden; }
  .row{
    display:grid; grid-template-columns: 1fr auto; gap:10px; align-items:center;
    padding:14px 16px; border-bottom:1px solid var(--line); cursor:pointer;
    transition: background 0.15s ease;
  }
  .row:last-child{ border-bottom:none; }
  .row:hover{ background:#FDFBFA; }
  .row-main .gedung{ font-weight:600; font-size:14.5px; margin:0 0 3px; }
  .row-main .sub{ font-size:12.5px; color: var(--ink-muted); }
  .row-right{ display:flex; align-items:center; gap:10px; }

  .status-badge{ font-size:11px; font-weight:600; padding:4px 10px; border-radius:999px; white-space:nowrap; }
  .status-badge.pending{ background: var(--warn-tint); color: var(--warn); }
  .status-badge.approved{ background: var(--ok-tint); color: var(--ok); }
  .status-badge.rejected{ background: var(--err-tint); color: var(--err); }

  .empty-state{ padding: 40px 16px; text-align:center; color: var(--ink-faint); font-size:13.5px; }

  /* ---------- Detail view ---------- */
  #detailView{ display:none; }
  .back-btn{
    display:inline-flex; align-items:center; gap:6px; background:none; border:none; color: var(--ink-muted);
    font-size:13px; padding:0; margin-bottom:14px;
  }
  .back-btn svg{ width:16px; height:16px; }

  .detail-head{ display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:18px; flex-wrap:wrap; }
  .detail-head h2{ margin:0 0 4px; font-size:20px; }
  .detail-head .sub{ font-size:13px; color: var(--ink-muted); }

  .card-shell{ background: var(--surface); border:1px solid var(--line); border-radius: var(--radius-m); box-shadow: var(--shadow); }
  .info-grid{ padding:16px; display:grid; grid-template-columns: 1fr; gap:10px; margin-bottom:20px; }
  @media (min-width:620px){ .info-grid{ grid-template-columns: 1fr 1fr 1fr; } }
  .info-item .l{ font-size:11.5px; color: var(--ink-faint); text-transform:uppercase; letter-spacing:0.02em; }
  .info-item .v{ font-size:13.5px; font-weight:500; margin-top:2px; }

  .section-head{ margin: 22px 0 12px; display:flex; align-items:baseline; justify-content:space-between; }
  .section-head h3{ font-size:15.5px; margin:0; }
  .section-head span{ font-size:12.5px; color: var(--ink-faint); }

  .doc-row{
    display:flex; align-items:center; gap:12px; padding:12px 14px;
    border:1px solid var(--line); border-radius: var(--radius-s); margin-bottom:10px; background: var(--surface);
  }
  .doc-row.checked-ok{ border-color: var(--ok); background: var(--ok-tint); }
  .doc-row.checked-bad{ border-color: var(--err); background: var(--err-tint); }
  .doc-icon{ width:34px; height:34px; border-radius:8px; background: var(--primary-tint); color: var(--primary-dark); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  .doc-icon svg{ width:17px; height:17px; }
  .doc-body{ flex:1; min-width:0; }
  .doc-title{ font-weight:600; font-size:13.5px; margin:0; }
  .doc-file{ font-size:12px; color: var(--ink-muted); margin:2px 0 0; }
  .doc-file a{ color: var(--primary); }
  .doc-toggle{ display:flex; gap:6px; flex-shrink:0; }
  .toggle-btn{
    font-size:11.5px; font-weight:600; padding:6px 10px; border-radius:999px; border:1.5px solid var(--line);
    background: var(--surface); color: var(--ink-muted);
  }
  .toggle-btn.ok.is-active{ background: var(--ok); border-color: var(--ok); color:#fff; }
  .toggle-btn.bad.is-active{ background: var(--err); border-color: var(--err); color:#fff; }
  .doc-note{ grid-column: 1/-1; width:100%; margin-top:8px; }
  .doc-note textarea{
    width:100%; border:1px solid var(--err); border-radius: var(--radius-s); padding:8px 10px;
    font-size:12.5px; font-family:'Inter'; resize:vertical; min-height:44px;
  }

  .decision-panel{ padding:18px; margin-top:8px; }
  .decision-status{ font-size:13.5px; margin-bottom:14px; }
  .decision-status b{ font-weight:600; }
  .decision-actions{ display:flex; gap:10px; flex-wrap:wrap; }
  .btn{ border-radius: var(--radius-s); padding:12px 18px; font-weight:600; font-size:14px; border:1.5px solid transparent; flex:1 1 180px; }
  .btn-approve{ background: var(--ok); color:#fff; }
  .btn-approve:disabled{ background: var(--ink-faint); cursor:not-allowed; }
  .btn-reject{ background: var(--surface); color: var(--err); border-color: var(--err); }
  .btn-reject:disabled{ opacity:0.4; cursor:not-allowed; border-color: var(--line); color: var(--ink-faint); }

  .email-preview{ display:none; margin-top:20px; padding:18px; border:1.5px dashed var(--primary); border-radius: var(--radius-m); background: var(--primary-tint); }
  .email-preview.is-visible{ display:block; }
  .email-preview h4{ margin:0 0 10px; font-size:13.5px; }
  .email-field{ font-size:12.5px; margin-bottom:4px; }
  .email-field b{ color: var(--ink-muted); font-weight:600; }
  .email-body{ background: var(--surface); border-radius: var(--radius-s); padding:12px; margin-top:8px; font-size:13px; white-space:pre-wrap; }

  footer{ text-align:center; color: var(--ink-faint); font-size:11.5px; margin: 30px 0 10px; font-family:'JetBrains Mono',monospace; }
</style>
</head>
<body>

<div class="top">
  <div class="top-inner">
    <div>
      <div class="top-eyebrow">Sudin Penanggulangan Kebakaran dan Penyelamatan Kota Administrasi Jakarta Utara</div>
      <h1>APEM Admin &mdash; Panel Pengesahan MKKG</h1>
    </div>
    <div class="admin-account">
      <div class="admin-account-info">
        <span class="admin-account-name">{{ auth()->user()->name }}</span>
        <span class="admin-account-meta">ID: {{ \Illuminate\Support\Str::before(auth()->user()->email, '@admin.local') }}</span>
        <span class="admin-account-badges">
          <span class="account-badge">{{ auth()->user()->role === 'super_admin' ? 'Super Admin' : 'Admin' }}</span>
          <span class="account-badge active">Aktif</span>
        </span>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @if (auth()->user()->role === 'super_admin')
          <a href="{{ route('super-admin.settings') }}" class="logout-button" style="text-decoration:none;">Pengaturan</a>
        @endif
        @csrf
        <button type="submit" class="logout-button">Keluar</button>
      </form>
    </div>
  </div>
</div>

<div class="wrap">
  <div class="demo-notice" id="demoNotice">Aplikasi ini dirancang dan dibangun oleh Rangga Ganteng, Satgas Jakarta Utara.</div>

  <div id="listView">
    <div class="stats" id="statsRow"></div>
    <div class="toolbar">
      <input type="text" id="searchInput" placeholder="Cari nama gedung atau pendaftar...">
      <select id="statusFilter">
        <option value="">Semua Status</option>
        <option value="pending">Menunggu</option>
        <option value="approved">Disetujui</option>
        <option value="rejected">Ditolak</option>
      </select>
    </div>
    <div class="list-card" id="listCard"></div>
  </div>

  <div id="detailView">
    <button class="back-btn" id="btnBack">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Kembali ke daftar
    </button>
    <div class="detail-head">
      <div>
        <h2 id="dGedung"></h2>
        <div class="sub" id="dSub"></div>
      </div>
      <span class="status-badge" id="dStatusBadge"></span>
    </div>

    <div class="card-shell info-grid" id="dInfoGrid"></div>

    <div class="section-head"><h3>Checklist Dokumen</h3><span id="dDocProgress">0/10 diperiksa</span></div>
    <div id="dDocList"></div>

    <div class="card-shell decision-panel">
      <div class="decision-status" id="dDecisionStatus"></div>
      <div class="decision-actions">
        <button class="btn btn-approve" id="btnApprove" disabled>Setujui &amp; Kirim Notifikasi</button>
        <button class="btn btn-reject" id="btnReject" disabled>Tolak &amp; Kirim Notifikasi</button>
      </div>

      <div class="email-preview" id="emailPreview">
        <h4>Pratinjau Notifikasi Email</h4>
        <div class="email-field"><b>Kepada:</b> <span id="eTo"></span></div>
        <div class="email-field"><b>Subjek:</b> <span id="eSubject"></span></div>
        <div class="email-body" id="eBody"></div>
      </div>
    </div>
  </div>

  <footer>APEM Admin Panel &mdash; Sistem Informasi Bidang Pencegahan Kebakaran</footer>
</div>

<script>
(function(){
  const ICON_DOC = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
  const STATUS_LABEL = { pending:'Menunggu', approved:'Disetujui', rejected:'Ditolak' };

  // Menarik Data dari Database (Controller)
  let PENDAFTAR = {!! json_encode($pendaftars) !!};

  const listCard = document.getElementById('listCard');
  const statsRow = document.getElementById('statsRow');
  const searchInput = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');

  function renderStats(){
    const total = PENDAFTAR.length;
    const pending = PENDAFTAR.filter(p => p.status === 'pending').length;
    const approved = PENDAFTAR.filter(p => p.status === 'approved').length;
    const rejected = PENDAFTAR.filter(p => p.status === 'rejected').length;
    statsRow.innerHTML = `
      <div class="stat-card"><div class="n">${total}</div><div class="l">Total Pendaftar</div></div>
      <div class="stat-card pending"><div class="n">${pending}</div><div class="l">Menunggu Review</div></div>
      <div class="stat-card approved"><div class="n">${approved}</div><div class="l">Disetujui</div></div>
      <div class="stat-card rejected"><div class="n">${rejected}</div><div class="l">Ditolak</div></div>`;
  }

  function renderList(){
    renderStats();
    const q = searchInput.value.trim().toLowerCase();
    const statusQ = statusFilter.value;
    const filtered = PENDAFTAR.filter(p => {
      const matchQ = !q || p.nama_gedung.toLowerCase().includes(q) || p.nama_pendaftar.toLowerCase().includes(q);
      const matchStatus = !statusQ || p.status === statusQ;
      return matchQ && matchStatus;
    });
    if (filtered.length === 0){
      listCard.innerHTML = '<div class="empty-state">Tidak ada pendaftar yang cocok atau belum ada data.</div>';
      return;
    }
    listCard.innerHTML = filtered.map(p => `
      <div class="row" data-id="${p.id}">
        <div class="row-main">
          <p class="gedung">${p.nama_gedung}</p>
          <p class="sub">${p.nama_pendaftar} &middot; ${p.kota} &middot; ${p.tanggal}</p>
        </div>
        <div class="row-right">
          <span class="status-badge ${p.status}">${STATUS_LABEL[p.status]}</span>
        </div>
      </div>`).join('');
    listCard.querySelectorAll('.row').forEach(row => {
      row.addEventListener('click', () => openDetail(Number(row.dataset.id)));
    });
  }
  searchInput.addEventListener('input', renderList);
  statusFilter.addEventListener('change', renderList);
  renderList();

  // ---- Detail view ----
  const listView = document.getElementById('listView');
  const detailView = document.getElementById('detailView');
  let currentId = null;

  function openDetail(id){
    currentId = id;
    listView.style.display = 'none';
    detailView.style.display = 'block';
    renderDetail();
    window.scrollTo({ top:0, behavior:'smooth' });
  }
  document.getElementById('btnBack').addEventListener('click', () => {
    detailView.style.display = 'none';
    listView.style.display = 'block';
    renderList();
  });

  function currentItem(){ return PENDAFTAR.find(p => p.id === currentId); }

  function renderDetail(){
    const item = currentItem();
    document.getElementById('dGedung').textContent = item.nama_gedung;
    document.getElementById('dSub').textContent = `${item.nama_pendaftar} \u00b7 ${item.jabatan} \u00b7 diajukan ${item.tanggal}`;
    const badge = document.getElementById('dStatusBadge');
    badge.className = 'status-badge ' + item.status;
    badge.textContent = STATUS_LABEL[item.status];

    document.getElementById('dInfoGrid').innerHTML = `
      <div class="info-item"><div class="l">Email</div><div class="v">${item.email}</div></div>
      <div class="info-item"><div class="l">No Tlp / HP</div><div class="v">${item.telepon}</div></div>
      <div class="info-item"><div class="l">Kota Administrasi</div><div class="v">${item.kota}</div></div>
      <div class="info-item" style="grid-column:1/-1"><div class="l">Alamat</div><div class="v">${item.alamat}</div></div>`;

    renderDocList(item);
    renderDecisionPanel(item);
    document.getElementById('emailPreview').classList.remove('is-visible');
  }

  function renderDocList(item){
    const wrap = document.getElementById('dDocList');
    wrap.innerHTML = '';
    item.dokumen.forEach(doc => {
      const row = document.createElement('div');
      row.className = 'doc-row' + (doc.checklist === 'ok' ? ' checked-ok' : doc.checklist === 'bad' ? ' checked-bad' : '');
      
      const fileUrl = doc.url;

      row.innerHTML = `
        <div class="doc-icon">${ICON_DOC}</div>
        <div class="doc-body">
          <p class="doc-title">${doc.title}</p>
          <p class="doc-file">
            <a href="${fileUrl}" target="_blank" rel="noopener" title="Lihat lampiran di tab baru">${doc.fileName}</a>
            <span aria-hidden="true"> · </span>
            <a href="${doc.downloadUrl}" title="Unduh lampiran">Unduh</a>
          </p>
          ${doc.checklist === 'bad' ? `<div class="doc-note"><textarea placeholder="Catatan ketidaksesuaian (wajib diisi)...">${doc.catatan}</textarea></div>` : ''}
        </div>
        <div class="doc-toggle">
          <button type="button" class="toggle-btn ok ${doc.checklist==='ok' ? 'is-active':''}" data-doc="${doc.id}" data-val="ok">Sesuai</button>
          <button type="button" class="toggle-btn bad ${doc.checklist==='bad' ? 'is-active':''}" data-doc="${doc.id}" data-val="bad">Tidak Sesuai</button>
        </div>`;
      wrap.appendChild(row);

      if (doc.checklist === 'bad'){
        row.querySelector('textarea').addEventListener('input', (e) => {
          doc.catatan = e.target.value;
          renderDecisionPanel(item);
        });
      }
      row.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          doc.checklist = btn.dataset.val;
          renderDocList(item);
          renderDecisionPanel(item);
        });
      });
    });
    document.getElementById('dDocProgress').textContent =
      `${item.dokumen.filter(d => d.checklist).length}/${item.dokumen.length} diperiksa`;
  }

  function renderDecisionPanel(item){
    const allChecked = item.dokumen.every(d => d.checklist);
    const allOk = item.dokumen.every(d => d.checklist === 'ok');
    const anyBad = item.dokumen.some(d => d.checklist === 'bad');
    const badWithoutNote = item.dokumen.some(d => d.checklist === 'bad' && d.catatan.trim() === '');

    const statusEl = document.getElementById('dDecisionStatus');
    if (!allChecked){
      statusEl.innerHTML = `Periksa seluruh dokumen terlebih dahulu (<b>${item.dokumen.filter(d=>d.checklist).length}/${item.dokumen.length}</b> selesai).`;
    } else if (allOk){
      statusEl.innerHTML = `Rekomendasi: <b style="color:var(--ok)">Setujui Permohonan</b> — seluruh dokumen dinyatakan sesuai.`;
    } else {
      statusEl.innerHTML = `Rekomendasi: <b style="color:var(--err)">Perlu Revisi / Ditolak</b> — ada dokumen yang tidak sesuai.`;
    }

    document.getElementById('btnApprove').disabled = !(allChecked && allOk);
    document.getElementById('btnReject').disabled = !(anyBad && !badWithoutNote);
  }

  document.getElementById('btnApprove').addEventListener('click', () => decide('approved'));
  document.getElementById('btnReject').addEventListener('click', () => decide('rejected'));

  function decide(status){
    const item = currentItem();
    item.status = status;

    // 1. Tampilkan Pratinjau di layar (visual)
    const badge = document.getElementById('dStatusBadge');
    badge.className = 'status-badge ' + item.status;
    badge.textContent = STATUS_LABEL[item.status];

    const badDocs = item.dokumen.filter(d => d.checklist === 'bad');
    let subject, body;
    if (status === 'approved'){
      subject = `Permohonan Pengesahan MKKG ${item.nama_gedung} — Disetujui`;
      body = `Yth. ${item.nama_pendaftar},\n\nPermohonan pengesahan MKKG untuk bangunan gedung "${item.nama_gedung}" telah kami periksa dan dinyatakan SESUAI dengan seluruh persyaratan.\n\nStatus: DISETUJUI\n\nTerima kasih.\n\nHormat kami,\nSudin Gulkarmat Jakarta Utara`;
    } else {
      const list = badDocs.map(d => `- ${d.title}: ${d.catatan}`).join('\n');
      subject = `Permohonan Pengesahan MKKG ${item.nama_gedung} — Perlu Revisi`;
      body = `Yth. ${item.nama_pendaftar},\n\nPermohonan pengesahan MKKG untuk bangunan gedung "${item.nama_gedung}" telah kami periksa. Terdapat dokumen yang perlu diperbaiki:\n\n${list}\n\nMohon unggah ulang melalui formulir APEM.\n\nHormat kami,\nSudin Gulkarmat Jakarta Utara`;
    }

    document.getElementById('eTo').textContent = item.email;
    document.getElementById('eSubject').textContent = subject;
    document.getElementById('eBody').textContent = body;
    document.getElementById('emailPreview').classList.add('is-visible');

    // 2. Kirim data ke Database Laravel & Tampilkan Alert Sukses
    fetch(`/admin/apem/${item.id}/keputusan`, {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        status: status,
        dokumen: item.dokumen.map(doc => ({
          id: doc.id,
          checklist: doc.checklist,
          catatan: doc.catatan || ''
        }))
      })
    })
    .then(async res => {
      const data = await res.json();
      if (!res.ok) throw new Error(data.message || 'Keputusan gagal disimpan.');
      return data;
    })
    .then(data => {
      // Munculkan kotak pesan (alert) sukses
      alert(data.message);
      // Refresh halaman agar tabel data terbarui
      location.reload();
    })
    .catch(err => {
      console.error(err);
      alert(err.message || 'Terjadi kesalahan saat memproses keputusan.');
    });
  }
})();
</script>
</body>
</html>
