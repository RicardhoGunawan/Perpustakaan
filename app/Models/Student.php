<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nis',
        'full_name',
        'class',
        'phone_number',
        'address',
        'date_of_birth',
        'profile_photo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
