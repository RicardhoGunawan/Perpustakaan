<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom temporary dulu
        Schema::table('students', function (Blueprint $table) {
            $table->string('class_temp')->nullable();
        });

        // 2. Salin data dari kolom class ke class_temp
        DB::statement('UPDATE students SET class_temp = class');

        // 3. Hapus kolom lama dan tambahkan kolom baru
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('class');
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes');
        });
    }

    public function down(): void
    {
        // 1. Tambahkan kembali kolom class
        Schema::table('students', function (Blueprint $table) {
            $table->string('class')->nullable();
        });

        // 2. Salin data kembali dari class_temp ke class
        DB::statement('UPDATE students SET class = class_temp');

        // 3. Hapus kolom temporary dan foreign key
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('class_temp');
            $table->dropConstrainedForeignId('school_class_id');
        });
    }
};