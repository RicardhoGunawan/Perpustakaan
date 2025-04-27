<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
        'class_name',
        'is_active'
    ];

    // Relasi dengan siswa
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
    
    // Accessor untuk mendapatkan nama lengkap kelas (X A, XI B, dll)
    public function getFullNameAttribute()
    {
        return "{$this->grade} {$this->class_name}";
    }
}