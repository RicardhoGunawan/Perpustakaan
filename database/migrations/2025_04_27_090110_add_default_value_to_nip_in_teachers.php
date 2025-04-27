<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menambahkan nilai default pada kolom 'nip' di tabel 'teachers'
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('nip')->default('NIP')->change(); // Menambahkan default value
        });
    }

    public function down(): void
    {
        // Menghapus default value jika rollback
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('nip')->default(null)->change(); // Menghapus default value
        });
    }
};
