<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category; // Import model Category
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 buku terbaru beserta kategori terkait
        $latestBooks = Book::latest()->take(4)->with('category')->get();

        // Ambil semua kategori
        $categories = Category::all();

        // Hitung jumlah buku di setiap kategori
        $categoryCounts = [];
        foreach ($categories as $category) {
            $categoryCounts[$category->name] = Book::where('category_id', $category->id)->count();
        }

        // Kirim data ke view
        return view('home', compact('latestBooks', 'categories', 'categoryCounts'));
    }



}
