<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->text('notes')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('borrowed_for')->nullable(); // Untuk guru: kelas yang diajar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};