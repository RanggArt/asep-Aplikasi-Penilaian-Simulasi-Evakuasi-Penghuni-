<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Pendaftar — APEM</title>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #F7F4F1; --surface: #FFFFFF; --ink: #221C1A; --ink-muted: #756762;
    --primary: #AB2A1E; --primary-dark: #7C1E15; --line: #E7DFDA;
    --ok: #1E7A4C; --ok-tint: #E7F4EC; --err: #B3261E; --err-tint: #FBEAE7; --warn: #C1790A; --warn-tint: #FBF0DE;
  }
  body{ margin:0; background: var(--bg); color: var(--ink); font-family:'Inter', sans-serif; line-height:1.5; }
  .top{ background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color:#fff; padding: 24px 20px; }
  .top-inner{ max-width: 900px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
  .top h1{ margin:0; font-family:'Oswald', sans-serif; font-size:24px; }
  .top .user-info{ font-size:14px; opacity:0.9; }
  
  .wrap{ max-width: 900px; margin: 30px auto; padding: 0 20px; }
  .menu-card{ background: var(--surface); border:1px solid var(--line); border-radius:12px; padding:24px; text-align:center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
  .menu-card h2{ margin:0 0 10px; font-family:'Oswald', sans-serif; }
  .menu-card p{ color: var(--ink-muted); font-size:14px; margin:0 0 20px; }
  .btn{ display:inline-block; background: var(--primary); color:#fff; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px; transition: 0.2s; }
  .btn:hover{ background: var(--primary-dark); }

  .history-section h3{ font-family:'Oswald', sans-serif; margin-bottom: 16px; border-bottom: 2px solid var(--line); padding-bottom:10px; }
  .card{ background: var(--surface); border:1px solid var(--line); border-radius:10px; padding:16px; margin-bottom:12px; display:grid; grid-template-columns:minmax(0,1fr) auto; gap:16px; align-items:start; }
  .card-info h4{ margin:0 0 4px; font-size:16px; }
  .card-info p{ margin:0; font-size:13px; color:var(--ink-muted); }
  .status-badge{ font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px; }
  .status-pending{ background: var(--warn-tint); color: var(--warn); }
  .status-approved{ background: var(--ok-tint); color: var(--ok); }
  .status-rejected{ background: var(--err-tint); color: var(--err); }
  .card-actions{ display:flex; flex-direction:column; align-items:flex-end; gap:10px; }
  .detail-btn{ border:0; border-radius:999px; padding:6px 12px; background:#087A50; color:#fff; font:inherit; font-size:12px; font-weight:600; cursor:pointer; }
  .detail-btn:hover{ background:#06613F; }
  .review-panel{ grid-column:1/-1; border-top:1px solid var(--line); padding-top:14px; }
  .review-panel[hidden]{ display:none; }
  .review-panel h4{ margin:0 0 10px; font-size:15px; }
  .review-list{ margin:12px 0 0; padding:0; list-style:none; }
  .review-list li{ margin:0 0 10px; padding:12px; border:1px solid #E8B4AE; border-radius:8px; background:var(--err-tint); font-size:13px; }
  .review-list .file-name{ margin-top:3px; color:var(--ink-muted); font-size:12px; overflow-wrap:anywhere; }
  .review-list .reason{ margin-top:6px; white-space:pre-wrap; }
  @media(max-width:560px){ .card{ grid-template-columns:1fr; } .card-actions{ flex-direction:row; justify-content:space-between; align-items:center; } }
  .empty{ text-align:center; padding:30px; color:var(--ink-muted); font-size:14px; }
</style>
</head>
<body>

<div class="top">
  <div class="top-inner">
    <h1>APEM Dashboard</h1>
    <div class="user-info">
      Halo, <b>{{ Auth::user()->name }}</b><br>
      <form method="POST" action="{{ route('logout') }}" style="display:inline; margin-top:5px;">
          @csrf
          <button type="submit" style="background:none; border:none; color:#fff; text-decoration:underline; cursor:pointer; padding:0; font-size:12px;">Keluar</button>
      </form>
    </div>
  </div>
</div>

<div class="wrap">
  <div class="menu-card">
    <h2>Pengajuan Pengesahan MKKG</h2>
    <p>Mulai isi formulir dan unggah dokumen persyaratan MKKG untuk gedung Anda.</p>
    <a href="{{ route('apem.index') }}" class="btn">+ Buat Pengesahan Baru</a>
  </div>

  <div class="history-section">
    <h3>Status & Riwayat Pengesahan Saya</h3>
    
    @if(count($pengajuan) > 0)
        @foreach($pengajuan as $item)
        <div class="card">
            <div class="card-info">
                <h4>{{ $item->nama_gedung }}</h4>
                <p>Diajukan: {{ date('d F Y', strtotime($item->tanggal)) }} &bull; {{ $item->kota }}</p>
                <p style="margin-top:6px;">
                    @if($item->status == 'pending')
                        Berkas sudah diterima dan menunggu pemeriksaan admin.
                    @elseif($item->status == 'approved')
                        Permohonan pengesahan gedung ini sudah disetujui.
                    @else
                        Permohonan memerlukan perbaikan. Klik tombol Detail untuk melihat dokumen yang ditolak dan catatan admin.
                    @endif
                </p>
            </div>
            <div class="card-actions">
                @if($item->status == 'pending')
                    <span class="status-badge status-pending">Menunggu Diperiksa</span>
                @elseif($item->status == 'approved')
                    <span class="status-badge status-approved">Disetujui</span>
                @else
                    <span class="status-badge status-rejected">Perlu Revisi / Ditolak</span>
                @endif
                <button type="button" class="detail-btn" data-detail-toggle aria-expanded="false" aria-controls="detail-{{ $item->id }}">Detail</button>
            </div>
            <div class="review-panel" id="detail-{{ $item->id }}" hidden>
                @if($item->status == 'rejected')
                    @php($rejectedReviews = collect($item->review_details ?? [])->where('checklist', 'bad'))
                    <h4>Dokumen yang perlu diperbaiki</h4>
                    @if($rejectedReviews->isNotEmpty())
                        <ul class="review-list">
                            @foreach($rejectedReviews as $review)
                                <li>
                                    <strong>{{ $review['title'] ?? 'Dokumen' }}</strong>
                                    @if(!empty($review['fileName']))
                                        <div class="file-name">File: {{ $review['fileName'] }}</div>
                                    @endif
                                    <div class="reason"><strong>Alasan admin:</strong> {{ $review['catatan'] ?? 'Admin belum memberikan keterangan.' }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="margin-top:10px;">Rincian penolakan belum tersedia. Minta admin meninjau ulang dan menyimpan catatan untuk setiap dokumen yang ditolak.</p>
                    @endif
                @elseif($item->status == 'approved')
                    <p><strong>Status:</strong> Permohonan disetujui admin. Seluruh dokumen dinyatakan sesuai.</p>
                @else
                    <p>Berkas sudah diterima dan masih menunggu pemeriksaan admin. Belum ada catatan penolakan.</p>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div class="empty">
            Belum ada data pengajuan pengesahan.
        </div>
    @endif
  </div>
</div>

<script>
document.querySelectorAll('[data-detail-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const panel = document.getElementById(button.getAttribute('aria-controls'));
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        panel.hidden = isExpanded;
        button.setAttribute('aria-expanded', String(!isExpanded));
        button.textContent = isExpanded ? 'Detail' : 'Tutup Detail';
    });
});
</script>
</body>
</html>
