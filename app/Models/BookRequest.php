<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'publisher',
        'description',
        'status',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
