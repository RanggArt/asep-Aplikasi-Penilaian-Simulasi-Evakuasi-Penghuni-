<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard User</h2>
    </x-slot>

    <main class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <section class="bg-white shadow-sm rounded-lg p-6">
                <p class="text-sm text-gray-500">Selamat datang,</p>
                <h1 class="text-2xl font-semibold text-gray-900">{{ auth()->user()->name }}</h1>
                <p class="mt-1 text-gray-600">Pantau pengajuan pengesahan MKKG Anda dari dashboard ini.</p>
            </section>

            <div class="grid gap-6 md:grid-cols-2">
                <a href="{{ route('apem.index') }}" class="block bg-white shadow-sm rounded-lg p-6 hover:ring-2 hover:ring-red-500">
                    <p class="text-sm font-medium text-red-700">MENU PENGESAHAN</p>
                    <h2 class="mt-2 text-xl font-semibold text-gray-900">Ajukan Pengesahan MKKG</h2>
                    <p class="mt-2 text-gray-600">Isi data gedung dan unggah dokumen persyaratan pengesahan.</p>
                    <span class="inline-block mt-4 text-red-700 font-semibold">Buka formulir →</span>
                </a>

                <section class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm font-medium text-gray-500">STATUS PENGESAHAN</p>
                    @if ($pengajuan)
                        @php
                            $statusLabel = [
                                'pending' => 'Menunggu pemeriksaan',
                                'proses' => 'Sedang diproses',
                                'approved' => 'Disetujui',
                                'rejected' => 'Perlu perbaikan',
                            ][$pengajuan->status] ?? ucfirst($pengajuan->status);
                        @endphp
                        <h2 class="mt-2 text-xl font-semibold text-gray-900">{{ $statusLabel }}</h2>
                        <p class="mt-2 text-gray-600">Gedung: {{ $pengajuan->nama_gedung }}</p>
                        <p class="mt-1 text-sm text-gray-500">Pengajuan terakhir {{ $pengajuan->created_at->format('d/m/Y') }}</p>
                    @else
                        <h2 class="mt-2 text-xl font-semibold text-gray-900">Belum ada pengajuan</h2>
                        <p class="mt-2 text-gray-600">Status pengesahan akan muncul setelah Anda mengirim formulir.</p>
                    @endif
                </section>
            </div>
        </div>
    </main>
</x-app-layout>
