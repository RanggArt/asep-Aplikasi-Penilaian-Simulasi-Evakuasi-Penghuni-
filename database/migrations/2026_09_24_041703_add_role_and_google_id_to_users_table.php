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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom role dan google_id
            $table->string('role')->default('user')->after('password');
            $table->string('google_id')->nullable()->after('role');

            // Agar bisa login dengan Google, kolom password kita buat boleh kosong (nullable)
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'google_id']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
