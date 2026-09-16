<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title>Kalkulator Rekapitulasi Penilaian</title>
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
    --primary: #12141D;
    --primary-dark: #000000;
    --accent: #E07A12;
    --radius-s: 6px;
    --radius-m: 10px;
    --shadow: 0 1px 2px rgba(34,28,26,0.06), 0 2px 10px rgba(34,28,26,0.05);
  }

  *{ box-sizing: border-box; }
  html{ -webkit-text-size-adjust: 100%; }
  body{
    margin:0; background: var(--bg); color: var(--ink);
    font-family: 'Inter', sans-serif; line-height: 1.5; padding-bottom: 96px;
  }
  h1,h2,h3, .label-font{ font-family:'Oswald', 'Inter', sans-serif; }
  button{ cursor: pointer; font-family: inherit; }

  /* ---------- Header ---------- */
  .top{
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color:#fff; padding: 22px 18px 34px; position: relative; overflow: hidden;
  }
  .top-inner{ max-width: 1000px; margin: 0 auto; position: relative; }
  .top-eyebrow{ display:flex; align-items:center; gap:8px; font-size: 12.5px; color: rgba(255,255,255,0.85); margin-bottom: 10px; }
  h1{ margin:0 0 6px; font-size: clamp(24px, 5vw, 32px); font-weight: 600; line-height:1.15; }
  .top p{ margin:0; color: rgba(255,255,255,0.82); font-size: 14.5px; max-width: 60ch; }

  /* ---------- Layout & Table ---------- */
  .wrap{ max-width: 1000px; margin: -20px auto 0; padding: 0 16px; position: relative; z-index: 10; }
  .card-shell{ background: var(--surface); border-radius: var(--radius-m); border: 1px solid var(--line); box-shadow: var(--shadow); padding: 20px; overflow-x: auto; }
  
  table{ width: 100%; border-collapse: collapse; min-width: 800px; }
  th, td{ border: 1px solid var(--line); padding: 10px 8px; text-align: center; vertical-align: middle; }
  th{ background: #F9F8F6; font-family: 'Oswald'; font-weight: 600; font-size: 14px; color: var(--ink); }
  td{ font-size: 13.5px; }
  td.text-left{ text-align: left; font-weight: 500; }
  
  .obs-input{
    width: 60px; padding: 8px; text-align: center; border: 1px solid var(--line);
    border-radius: 4px; font-family: 'Inter'; font-size: 14px; background: #FCFAF9;
  }
  .obs-input:focus{ border-color: #3E8EFF; background: #fff; outline: none; }
  
  .avg-cell{ font-family: 'Oswald'; font-weight: 600; font-size: 16px; color: #1E7A4C; }
  .ket-cell{ font-weight: 600; font-size: 13px; padding: 4px 8px; border-radius: 4px; display: inline-block; min-width: 80px;}
  
  /* Predikat Colors */
  .p-sangat-baik{ background: #E7F4EC; color: #1E7A4C; }
  .p-baik{ background: #E4F3F4; color: #0E7C86; }
  .p-cukup{ background: #FBF0DE; color: #C1790A; }
  .p-kurang{ background: #FBEAE7; color: #AB2A1E; }
  .p-kosong{ background: #F0F0F0; color: #9BA1AD; }

  /* ---------- Legend ---------- */
  .legend{ margin-top: 24px; padding: 16px; background: #F9F8F6; border-radius: var(--radius-s); font-size: 13px; color: var(--ink-muted); }
  .legend strong{ color: var(--ink); }
  .legend ul{ margin: 8px 0 0; padding-left: 20px; }

  /* ---------- Actions ---------- */
  .actions{ display:flex; gap: 10px; margin-top: 22px; flex-wrap: wrap; }
  .btn{
    flex: 1 1 200px; border-radius: var(--radius-s); padding: 13px 16px; font-weight: 600; font-size: 14.5px;
    border: 1.5px solid transparent; display:flex; align-items:center; justify-content:center; gap:8px;
  }
  .btn-primary{ background: #AB2A1E; color:#fff; }
  .btn-primary:hover{ background: #7C1E15; }
  .btn-danger{ background: var(--surface); color: #AB2A1E; border-color: #E9C9C4; }

</style>
</head>
<body>

<div class="top">
  <div class="top-inner">
    <a href="{{ route('home') }}" style="display:inline-block; margin-bottom:12px; color:rgba(255,255,255,0.8); font-size:13px; text-decoration:underline;">&larr; Kembali ke Menu Utama</a>
    <div class="top-eyebrow">Pusat Rekapitulasi Data</div>
    <h1>Kalkulator Rekapitulasi Penilaian</h1>
    <p>Masukkan total nilai dari masing-masing observer lapangan. Nilai rata-rata dan predikat akan dihitung secara otomatis.</p>
  </div>
</div>

<div class="wrap">
  <div class="card-shell">
    <table id="rekapTable">
      <thead>
        <tr>
          <th style="width: 5%;">No.</th>
          <th style="width: 25%;">Penilaian</th>
          <th>Obs. 1</th>
          <th>Obs. 2</th>
          <th>Obs. 3</th>
          <th>Obs. 4</th>
          <th>Obs. 5</th>
          <th style="width: 10%;">Nilai Rata</th>
          <th style="width: 15%;">Keterangan</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <!-- Baris akan dirender oleh JS -->
      </tbody>
    </table>

    <div class="legend">
      <strong>Catatan Rumus Predikat:</strong><br>
      • <strong>Penilaian Nomor 1:</strong> Sangat Baik (40-52), Baik (27-39), Cukup (14-26), Kurang (≤13)<br>
      • <strong>Penilaian Nomor 2 s.d 8:</strong> Sangat Baik (13-16), Baik (9-12), Cukup (5-8), Kurang (≤4)
    </div>
  </div>

  <div class="actions">
    <button class="btn btn-primary" id="btnPdf">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px; height:18px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Unduh Laporan Rekapitulasi (PDF)
    </button>
    <button class="btn btn-danger" id="btnReset">Reset Kalkulator</button>
  </div>
</div>

<script>
  const CATEGORIES = [
    "Penilaian Umum Evakuasi",
    "Penilaian Koordinator Lapangan / FSM",
    "Penilaian Tim Teknisi",
    "Penilaian Tim Evakuasi",
    "Penilaian Tim Penanganan Titik Kumpul",
    "Penilaian Tim Rescue dan P3K",
    "Penilaian Tim Pemadam Kebakaran Internal",
    "Penilaian Tim Pengamanan"
  ];

  const tbody = document.getElementById('tableBody');

  // Render Tabel
  CATEGORIES.forEach((cat, r) => {
    const tr = document.createElement('tr');
    
    // No & Kategori
    let html = `<td>${r + 1}</td><td class="text-left">${cat}</td>`;
    
    // 5 Input Observer
    for(let c=0; c<5; c++){
      html += `<td><input type="number" min="0" class="obs-input" id="in-${r}-${c}" data-row="${r}" placeholder="-"></td>`;
    }
    
    // Kolom Hasil
    html += `
      <td><span class="avg-cell" id="avg-${r}">0.0</span></td>
      <td><span class="ket-cell p-kosong" id="ket-${r}">-</span></td>
    `;
    
    tr.innerHTML = html;
    tbody.appendChild(tr);
  });

  // Logika Kalkulator
  function hitungBaris(r) {
    let sum = 0;
    let count = 0;
    
    // Ambil nilai dari Obs 1-5
    for(let c=0; c<5; c++){
      let val = document.getElementById(`in-${r}-${c}`).value;
      if(val !== "" && !isNaN(val)) {
        sum += parseFloat(val);
        count++;
      }
    }

    let avg = count === 0 ? 0 : (sum / count);
    document.getElementById(`avg-${r}`).innerText = avg > 0 ? avg.toFixed(1) : "0.0";

    // Tentukan Keterangan/Predikat
    let ketSpan = document.getElementById(`ket-${r}`);
    let predikatText = "-";
    let predikatClass = "p-kosong";

    if (count > 0) {
      if (r === 0) {
        // Aturan Penilaian No 1
        if (avg >= 40) { predikatText = "Sangat Baik"; predikatClass = "p-sangat-baik"; }
        else if (avg >= 27) { predikatText = "Baik"; predikatClass = "p-baik"; }
        else if (avg >= 14) { predikatText = "Cukup"; predikatClass = "p-cukup"; }
        else { predikatText = "Kurang"; predikatClass = "p-kurang"; }
      } else {
        // Aturan Penilaian No 2 - 8
        if (avg >= 13) { predikatText = "Sangat Baik"; predikatClass = "p-sangat-baik"; }
        else if (avg >= 9) { predikatText = "Baik"; predikatClass = "p-baik"; }
        else if (avg >= 5) { predikatText = "Cukup"; predikatClass = "p-cukup"; }
        else { predikatText = "Kurang"; predikatClass = "p-kurang"; }
      }
    }

    ketSpan.innerText = predikatText;
    ketSpan.className = `ket-cell ${predikatClass}`;
  }

  // Pasang Event Listener ke semua input
  document.querySelectorAll('.obs-input').forEach(input => {
    input.addEventListener('input', function() {
      hitungBaris(parseInt(this.getAttribute('data-row')));
    });
  });

  // Reset Button
  document.getElementById('btnReset').addEventListener('click', () => {
    if(!confirm("Hapus semua angka di kalkulator ini?")) return;
    document.querySelectorAll('.obs-input').forEach(inp => inp.value = '');
    for(let r=0; r<8; r++) hitungBaris(r);
  });

  // Export PDF
  document.getElementById('btnPdf').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ unit:'mm', format:'a4', orientation: 'landscape' });
    
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('REKAPITULASI PENILAIAN EVAKUASI', doc.internal.pageSize.getWidth()/2, 20, {align: 'center'});
    
    const tableData = [];
    for(let r=0; r<8; r++){
      let row = [ r+1, CATEGORIES[r] ];
      for(let c=0; c<5; c++){
        row.push(document.getElementById(`in-${r}-${c}`).value || '-');
      }
      row.push(document.getElementById(`avg-${r}`).innerText);
      row.push(document.getElementById(`ket-${r}`).innerText);
      tableData.push(row);
    }

    doc.autoTable({
      startY: 30,
      head: [['No.', 'Penilaian', 'Obs. 1', 'Obs. 2', 'Obs. 3', 'Obs. 4', 'Obs. 5', 'Nilai Rata', 'Keterangan']],
      body: tableData,
      styles: { font: 'helvetica', fontSize: 10, cellPadding: 3, valign: 'middle', lineColor: [200,200,200], lineWidth: 0.1 },
      headStyles: { fillColor: [171,42,30], textColor: 255, halign: 'center' },
      columnStyles: {
        0: { halign: 'center', cellWidth: 10 },
        1: { cellWidth: 70 },
        2: { halign: 'center' }, 3: { halign: 'center' }, 4: { halign: 'center' }, 5: { halign: 'center' }, 6: { halign: 'center' },
        7: { halign: 'center', fontStyle: 'bold' },
        8: { halign: 'center', fontStyle: 'bold' }
      }
    });

    // Tanggal cetak
    let finalY = doc.lastAutoTable.finalY + 10;
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    let today = new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
    doc.text(`Dicetak pada: ${today}`, 14, finalY);

    doc.save('Rekapitulasi_Penilaian.pdf');
  });

</script>
</body>
</html>