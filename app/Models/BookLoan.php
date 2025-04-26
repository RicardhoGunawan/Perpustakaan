<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
        'admin_notes',
        'quantity',
        'borrowed_for',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    // Check if the loan is approved
    public function isApproved(): bool
    {
        return $this->status === 'dipinjam' && !is_null($this->approved_at);
    }
    
    // Check if the loan is pending approval
    public function isPending(): bool
    {
        return $this->status === 'menunggu_persetujuan';
    }
    
    // Check if the loan is rejected
    public function isRejected(): bool
    {
        return $this->status === 'ditolak';
    }
}