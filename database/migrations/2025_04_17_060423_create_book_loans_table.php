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
            $table->enum('status', ['menunggu_persetujuan', 'ditolak', 'dipinjam', 'dikembalikan', 'terlambat'])->default('menunggu_persetujuan');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable(); // Admin's notes for approval or rejection
            $table->integer('quantity')->default(1);
            $table->string('borrowed_for')->nullable(); // Untuk guru: kelas yang diajar
            $table->timestamp('approved_at')->nullable(); // When the loan was approved
            $table->foreignId('approved_by')->nullable()->constrained('users'); // Who approved the loan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};