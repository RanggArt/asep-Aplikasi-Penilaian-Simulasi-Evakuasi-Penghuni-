<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Aplikasi — Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-2xl px-4 py-10">
        <a href="{{ route('admin.apem.index') }}" class="text-sm font-medium text-blue-700">&larr; Kembali ke panel admin</a>
        <section class="mt-5 rounded-2xl bg-white p-6 shadow-sm sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-wider text-slate-500">Super Admin</p>
            <h1 class="mt-1 text-2xl font-bold">Pengaturan Aplikasi</h1>
            <p class="mt-2 text-slate-600">Atur apakah tombol ASEP dapat digunakan dan apakah halaman ASEP dapat dibuka.</p>

            @if (session('status'))
                <div role="status" class="mt-5 rounded-lg bg-green-50 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <div class="mt-6 rounded-xl border border-slate-200 p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="font-semibold">Aplikasi ASEP</h2>
                        <p class="mt-1 text-sm {{ $asepEnabled ? 'text-green-700' : 'text-amber-700' }}">
                            Saat ini: {{ $asepEnabled ? 'Aktif' : 'Nonaktif' }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('super-admin.settings.asep') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="enabled" value="{{ $asepEnabled ? '0' : '1' }}">
                        <button type="submit" class="rounded-lg px-5 py-3 font-semibold text-white {{ $asepEnabled ? 'bg-red-700 hover:bg-red-800' : 'bg-green-700 hover:bg-green-800' }}">
                            {{ $asepEnabled ? 'Nonaktifkan ASEP' : 'Aktifkan ASEP' }}
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
