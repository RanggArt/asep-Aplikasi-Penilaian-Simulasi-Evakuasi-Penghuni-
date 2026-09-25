<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Apem;

class ApemController extends Controller
{
    // Fungsi menampilkan halaman Admin
    public function indexAdmin()
    {
        $data = Apem::orderBy('created_at', 'desc')->get()->map(function ($item) {
            $docTemplate = [
                ['id' => 'sertifikat_fsm', 'title' => 'Sertifikat FSM'],
                ['id' => 'surat_penunjukan_fsm', 'title' => 'Surat Penunjukan FSM'],
                ['id' => 'surat_permohonan', 'title' => 'Surat Permohonan Pengesahan MKKG'],
                ['id' => 'program_kerja', 'title' => 'Program Kerja MKKG'],
                ['id' => 'struktur_organisasi', 'title' => 'Struktur Organisasi MKKG'],
                ['id' => 'tugas_fungsi', 'title' => 'Tugas dan Fungsi MKKG'],
                ['id' => 'koordinasi', 'title' => 'Koordinasi'],
                ['id' => 'sarana_prasarana', 'title' => 'Sarana dan Prasarana MKKG'],
                ['id' => 'sop_rdtk', 'title' => 'Standar Operasional Prosedur dan RDTK'],
                ['id' => 'pelatihan_simulasi', 'title' => 'Pelatihan dan Simulasi Evakuasi Kebakaran']
            ];

            $dokumen = [];
            $reviewById = collect($item->review_details ?? [])->keyBy('id');
            foreach ($docTemplate as $doc) {
                $field = $doc['id'];
                if ($item->$field) {
                    $review = $reviewById->get($field, []);
                    $dokumen[] = [
                        'id' => $doc['id'],
                        'title' => $doc['title'],
                        'fileName' => basename($item->$field),
                        'url' => route('admin.apem.document', ['id' => $item->id, 'field' => $field]),
                        'checklist' => $review['checklist'] ?? null,
                        'catatan' => $review['catatan'] ?? ''
                    ];
                }
            }

            return [
                'id' => $item->id,
                'nama_pendaftar' => $item->nama_pendaftar,
                'jabatan' => $item->jabatan,
                'email' => $item->email ?? 'belum-diisi@email.com',
                'tanggal' => $item->tanggal,
                'nama_gedung' => $item->nama_gedung,
                'alamat' => $item->alamat,
                'telepon' => $item->telepon,
                'kota' => $item->kota,
                'status' => $item->status,
                'dokumen' => $dokumen
            ];
        });

        return view('admin-apem', ['pendaftars' => $data]);
    }

    // Tampilkan lampiran inline bagi admin agar dapat dibuka di tab baru.
    public function showDocument(int $id, string $field)
    {
        $allowedFields = [
            'sertifikat_fsm', 'surat_penunjukan_fsm', 'surat_permohonan', 'program_kerja',
            'struktur_organisasi', 'tugas_fungsi', 'koordinasi', 'sarana_prasarana',
            'sop_rdtk', 'pelatihan_simulasi',
        ];

        abort_unless(in_array($field, $allowedFields, true), 404);

        $apem = Apem::findOrFail($id);
        $path = $apem->{$field};
        abort_unless(is_string($path) && $path !== '', 404);

        // File lama mungkin menyimpan nama saja; unggahan baru menyimpan apem_docs/nama-file.
        $path = str_contains($path, '/') ? $path : 'apem_docs/'.$path;
        abort_unless(Str::startsWith($path, 'apem_docs/'), 404);
        abort_unless(Storage::disk('public')->exists($path), 404, 'Lampiran tidak ditemukan di penyimpanan server.');

        return response()->file(storage_path('app/public/'.$path), [
            'Content-Disposition' => 'inline; filename="'.str_replace('"', '', basename($path)).'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    // Fungsi menyimpan data dari Pendaftar
    public function store(Request $request)
    {
        $fileFields = [
            'sertifikat_fsm',
            'surat_penunjukan_fsm',
            'surat_permohonan',
            'program_kerja',
            'struktur_organisasi',
            'tugas_fungsi',
            'koordinasi',
            'sarana_prasarana',
            'sop_rdtk',
            'pelatihan_simulasi'
        ];

        $validated = $request->validate([
            'nama_pendaftar' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'nama_gedung' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:2000'],
            'telepon' => ['required', 'string', 'max:50'],
            'kota' => ['required', 'string', 'max:100'],
            'sertifikat_fsm' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'surat_penunjukan_fsm' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'surat_permohonan' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'program_kerja' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'struktur_organisasi' => ['required', 'file', 'mimes:pdf,dwg,jpg,jpeg,png', 'max:10240'],
            'tugas_fungsi' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'koordinasi' => ['required', 'file', 'mimes:pdf,dwg,jpg,jpeg,png', 'max:10240'],
            'sarana_prasarana' => ['required', 'file', 'mimes:pdf,dwg,jpg,jpeg,png', 'max:10240'],
            'sop_rdtk' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'pelatihan_simulasi' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $data = collect($validated)->except($fileFields)->all();
        $data['email'] = Auth::user()->email;

        foreach ($fileFields as $field) {
            $data[$field] = $request->file($field)->store('apem_docs', 'public');
        }

        Apem::create($data);
        return response()->json(['message' => 'Permohonan berhasil dikirim.'], 201);
    }
    // Fungsi untuk menyimpan keputusan Admin
    public function updateStatus(Request $request, int $id)
    {
        $apem = Apem::findOrFail($id);

        $allowedFields = [
            'sertifikat_fsm', 'surat_penunjukan_fsm', 'surat_permohonan', 'program_kerja',
            'struktur_organisasi', 'tugas_fungsi', 'koordinasi', 'sarana_prasarana',
            'sop_rdtk', 'pelatihan_simulasi',
        ];
        $titles = [
            'sertifikat_fsm' => 'Sertifikat FSM',
            'surat_penunjukan_fsm' => 'Surat Penunjukan FSM',
            'surat_permohonan' => 'Surat Permohonan Pengesahan MKKG',
            'program_kerja' => 'Program Kerja MKKG',
            'struktur_organisasi' => 'Struktur Organisasi MKKG',
            'tugas_fungsi' => 'Tugas dan Fungsi MKKG',
            'koordinasi' => 'Koordinasi',
            'sarana_prasarana' => 'Sarana dan Prasarana MKKG',
            'sop_rdtk' => 'Standar Operasional Prosedur dan RDTK',
            'pelatihan_simulasi' => 'Pelatihan dan Simulasi Evakuasi Kebakaran',
        ];

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'dokumen' => ['required', 'array', 'min:1'],
            'dokumen.*.id' => ['required', 'string', 'in:'.implode(',', $allowedFields)],
            'dokumen.*.checklist' => ['required', 'in:ok,bad'],
            'dokumen.*.catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        $uploadedFields = collect($allowedFields)
            ->filter(fn ($field) => filled($apem->{$field}))
            ->values()
            ->all();
        $reviews = collect($validated['dokumen']);
        $reviewedFields = $reviews->pluck('id')->all();

        if (count($reviewedFields) !== count(array_unique($reviewedFields))
            || count($uploadedFields) !== count($reviewedFields)
            || array_diff($uploadedFields, $reviewedFields)
            || array_diff($reviewedFields, $uploadedFields)) {
            return response()->json(['message' => 'Checklist harus memuat setiap dokumen yang diunggah tepat satu kali.'], 422);
        }

        $hasRejectedDocument = $reviews->contains(fn ($review) => $review['checklist'] === 'bad');
        $hasMissingReason = $reviews->contains(fn ($review) =>
            $review['checklist'] === 'bad' && trim($review['catatan'] ?? '') === ''
        );

        if ($validated['status'] === 'approved' && $hasRejectedDocument) {
            return response()->json(['message' => 'Permohonan hanya dapat disetujui jika semua dokumen dinyatakan sesuai.'], 422);
        }

        if ($validated['status'] === 'rejected' && ! $hasRejectedDocument) {
            return response()->json(['message' => 'Tandai minimal satu dokumen sebagai tidak sesuai sebelum menolak permohonan.'], 422);
        }

        if ($validated['status'] === 'rejected' && $hasMissingReason) {
            return response()->json(['message' => 'Setiap dokumen yang ditolak harus memiliki catatan alasan.'], 422);
        }

        $apem->status = $validated['status'];
        $apem->review_details = $reviews->map(fn ($review) => [
            'id' => $review['id'],
            'title' => $titles[$review['id']],
            'fileName' => basename($apem->{$review['id']}),
            'checklist' => $review['checklist'],
            'catatan' => trim($review['catatan'] ?? ''),
        ])->values()->all();
        $apem->save();

        // (Logika pengiriman email via Laravel Mail bisa diletakkan di sini nantinya)

        return response()->json([
            'message' => 'Keputusan berhasil disimpan! Notifikasi email telah diteruskan ke pendaftar.'
        ]);
    }
}
