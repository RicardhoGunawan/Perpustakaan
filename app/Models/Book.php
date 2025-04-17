<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'publication_year',
        'description',
        'stock',
        'cover_image',
        'category',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }

    public function getAvailableStockAttribute()
    {
        $borrowedCount = $this->loans()
            ->where('status', 'dipinjam')
            ->sum('quantity');
            
        return $this->stock - $borrowedCount;
    }
}
