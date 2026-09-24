<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
            foreach ($docTemplate as $doc) {
                $field = $doc['id'];
                if ($item->$field) {
                    $dokumen[] = [
                        'id' => $doc['id'],
                        'title' => $doc['title'],
                        'fileName' => basename($item->$field),
                        'checklist' => null,
                        'catatan' => ''
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
    public function updateStatus(Request $request, $id)
    {
        $apem = Apem::findOrFail($id);
        $apem->status = $request->status;
        $apem->save();

        // (Logika pengiriman email via Laravel Mail bisa diletakkan di sini nantinya)

        return response()->json([
            'message' => 'Keputusan berhasil disimpan! Notifikasi email telah diteruskan ke pendaftar.'
        ]);
    }
}
