<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 8 buku terbaru
        $latestBooks = Book::latest()->take(8)->get();

        // Ambil kategori buku yang unik
        $categories = Book::distinct()->pluck('category')->toArray();

        // Hitung jumlah buku di setiap kategori
        $categoryCounts = [];
        foreach ($categories as $category) {
            $categoryCounts[$category] = Book::where('category', $category)->count();
        }

        // Kirim data ke view
        return view('home', compact('latestBooks', 'categories', 'categoryCounts'));
    }
}
