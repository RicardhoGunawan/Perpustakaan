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
        'school_class_id', // Ganti class menjadi school_class_id
        'phone_number',
        'address',
        'date_of_birth',
        'profile_photo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Tambahkan relasi dengan SchoolClass
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }
    
    // Accessor untuk mendapatkan nama kelas (untuk kompatibilitas dengan kode lama)
    public function getClassAttribute()
    {
        return $this->schoolClass?->full_name;
    }
}