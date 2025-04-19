<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Stock filter
        if ($request->filled('availableOnly') && $request->availableOnly === 'true') {
            $query->whereRaw('stock > COALESCE((SELECT SUM(quantity) FROM book_loans WHERE book_id = books.id AND status = "dipinjam"), 0)');
        }
        
        $books = $query->paginate(12)->withQueryString();
        $categories = Book::distinct()->pluck('category')->toArray();
        
        return view('books.index', compact('books', 'categories'));
    }
    
    public function show(Book $book)
    {
        // Get similar books based on category
        $similarBooks = Book::where('category', $book->category)
                         ->where('id', '!=', $book->id)
                         ->take(3)
                         ->get();
        
        return view('books.show', compact('book', 'similarBooks'));
    }
}