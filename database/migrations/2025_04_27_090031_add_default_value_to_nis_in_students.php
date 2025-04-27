<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menambahkan nilai default pada kolom 'nis' di tabel 'students'
        Schema::table('students', function (Blueprint $table) {
            $table->string('nis')->default('NIS')->change(); // Menambahkan default value
        });
    }

    public function down(): void
    {
        // Menghapus default value jika rollback
        Schema::table('students', function (Blueprint $table) {
            $table->string('nis')->default(null)->change(); // Menghapus default value
        });
    }
};
