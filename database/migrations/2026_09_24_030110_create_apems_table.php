<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apems', function (Blueprint $table) {
            $table->id();
            // Data Teks
            $table->string('nama_pendaftar');
            $table->string('jabatan');
            $table->date('tanggal');
            $table->string('nama_gedung');
            $table->text('alamat');
            $table->string('telepon');
            $table->string('kota');

            // Data File (Menyimpan path lokasi file)
            $table->string('sertifikat_fsm')->nullable();
            $table->string('surat_penunjukan_fsm')->nullable();
            $table->string('surat_permohonan')->nullable();
            $table->string('program_kerja')->nullable();
            $table->string('struktur_organisasi')->nullable();
            $table->string('tugas_fungsi')->nullable();
            $table->string('koordinasi')->nullable();
            $table->string('sarana_prasarana')->nullable();
            $table->string('sop_rdtk')->nullable();
            $table->string('pelatihan_simulasi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apems');
    }
};
