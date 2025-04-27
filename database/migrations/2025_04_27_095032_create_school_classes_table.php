<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;  
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('grade'); // Tingkat (X, XI, XII)
            $table->string('class_name'); // Nama kelas (A, B, C, D, E)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Kombinasi grade dan class_name harus unik
            $table->unique(['grade', 'class_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};