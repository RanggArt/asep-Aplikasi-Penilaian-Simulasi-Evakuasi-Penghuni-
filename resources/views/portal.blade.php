<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BECEK - Sistem Informasi Bidang Pencegahan Kebakaran</title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-red-950 to-slate-900 min-h-screen text-slate-100 flex flex-col justify-between font-sans antialiased">

    <!-- Efek Pattern Latar Belakang -->
    <div class="fixed inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#radial-pattern)] bg-[size:16px_16px]"></div>

    <!-- Bagian Header (Judul BECEK) -->
    <header class="relative z-10 pt-12 pb-8 px-4 text-center">
        <div class="max-w-4xl mx-auto flex flex-col items-center">
            <!-- Logo Ikon Damkar -->
            <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-600/50 border border-red-400/30 mb-5">
                <i class="fa-solid fa-fire-extinguisher text-3xl text-white"></i>
            </div>
            
            <!-- Judul Utama -->
            <h1 class="text-5xl sm:text-7xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-red-400 via-amber-300 to-red-500 mb-3">
                BECEK
            </h1>
            <p class="text-xl sm:text-2xl font-bold text-slate-200 max-w-2xl mb-1">
                Sistem Informasi Bidang Pencegahan Kebakaran
            </p>
            <p class="text-sm text-red-400/90 font-semibold tracking-widest uppercase mt-2">
                Suku Dinas Gulkarmat Jakarta Utara
            </p>
        </div>
    </header>

    <!-- Bagian Tombol Aplikasi -->
    <main class="relative z-10 max-w-4xl mx-auto px-4 py-8 flex-grow flex items-center justify-center w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
            
            <!-- TOMBOL 1: Aplikasi ASEP -->
            <div class="group relative bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 border border-slate-700/60 hover:border-red-500/80 transition-all duration-300 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center justify-center mb-5 text-red-400 group-hover:scale-110 group-hover:bg-red-500 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-clipboard-check text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-1 group-hover:text-red-400 transition-colors">ASEP</h2>
                    <h3 class="text-sm font-medium text-amber-400 mb-3">Aplikasi Penilaian Simulasi Evakuasi Penghuni</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Instrumen penilaian simulasi evakuasi gedung mencakup form tim tanggap darurat dan kalkulator rekapitulasi otomatis.
                    </p>
                </div>
                <!-- Link menuju rute ASEP -->
                @if ($asepEnabled)
                    <a href="{{ route('asep.index') }}" class="w-full py-3 px-5 rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-semibold text-sm flex items-center justify-center space-x-2 transition-all shadow-lg shadow-red-600/30">
                        <span>Buka Aplikasi ASEP</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                @else
                    <span aria-disabled="true" class="w-full py-3 px-5 rounded-xl bg-slate-700 text-slate-400 font-semibold text-sm flex items-center justify-center space-x-2 cursor-not-allowed border border-slate-600">
                        <i class="fa-solid fa-ban" aria-hidden="true"></i>
                        <span>ASEP sedang dinonaktifkan</span>
                    </span>
                @endif
            </div>

            <!-- TOMBOL 2: Aplikasi APEM -->
            <div class="group relative bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 border border-slate-700/60 hover:border-amber-500/80 transition-all duration-300 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="w-14 h-14 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center justify-center mb-5 text-amber-400 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-slate-900 transition-all duration-300">
                        <i class="fa-solid fa-building-shield text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-1 group-hover:text-amber-400 transition-colors">APEM</h2>
                    <h3 class="text-sm font-medium text-amber-400 mb-3">Aplikasi Persyaratan MKKG</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Sistem verifikasi kelengkapan persyaratan Manajemen Keselamatan Kebakaran Gedung (MKKG) di wilayah Jakarta Utara.
                    </p>
                </div>
                <!-- Tombol APEM (Kosong untuk sementara) -->
                <!-- Link menuju rute APEM -->
<a href="{{ route('apem.index') }}" class="w-full py-3 px-5 rounded-xl bg-slate-700/80 hover:bg-slate-700 text-slate-300 hover:text-white font-semibold text-sm flex items-center justify-center space-x-2 transition-all border border-slate-600">
    <span>Buka Aplikasi APEM</span>
    <i class="fa-solid fa-arrow-right text-xs"></i>
</a>
            </div>

        </div>
    </main>

    @if (session('asep_disabled'))
        <div role="status" class="relative z-10 mx-auto mb-5 rounded-lg border border-amber-500/40 bg-amber-500/10 px-4 py-3 text-center text-amber-200">
            {{ session('asep_disabled') }}
        </div>
    @endif

    <!-- Footer -->
    <footer class="relative z-10 py-6 text-center text-xs text-slate-500 border-t border-slate-800/80 mt-auto">
        <p>&copy; {{ date('Y') }} Bidang Pencegahan Kebakaran — Suku Dinas Gulkarmat Jakarta Utara</p>
    </footer>

</body>
</html>
