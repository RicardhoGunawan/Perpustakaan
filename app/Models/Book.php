<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


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
        'category_id', // <-- ini yang baru
        'slug', // tambahkan slug ke fillable juga!


    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


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
    // Tambahan untuk slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
    protected static function booted()
    {
        static::saving(function ($book) {
            // Jika slug kosong, buat slug dari judul
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->title);
            }

            // Cek apakah slug sudah ada, jika ada tambahkan angka di belakangnya
            $originalSlug = $book->slug;
            $counter = 1;

            // Jika slug sudah ada, tambahkan angka
            while (Book::where('slug', $book->slug)->exists()) {
                $book->slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        });
    }

}
