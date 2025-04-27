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
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);  // Menyesuaikan ke 'category_id'
        }

        // Stock filter
        if ($request->filled('availableOnly') && $request->availableOnly === 'true') {
            $query->whereRaw('stock > COALESCE((SELECT SUM(quantity) FROM book_loans WHERE book_id = books.id AND status = "dipinjam"), 0)');
        }

        // Ambil data buku dengan paginasi
        $books = $query->paginate(12)->withQueryString();

        // Ambil kategori untuk dropdown
        $categories = \App\Models\Category::pluck('name', 'id')->toArray();

        return view('books.index', compact('books', 'categories'));
    }


    public function show(Book $book)
    {
        // Get similar books based on category_id, bukan category
        $similarBooks = Book::where('category_id', $book->category_id) // Menggunakan category_id
            ->where('id', '!=', $book->id)
            ->take(3)
            ->get();

        return view('books.show', compact('book', 'similarBooks'));
    }
    // In BookController.php
    public function getSuggestions(Request $request)
    {
        try {
            $query = $request->input('query', '');
            $category = $request->input('category', '');

            $bookQuery = Book::query();

            // Cari berdasarkan input
            if (!empty($query)) {
                $bookQuery->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('author', 'like', "%{$query}%")
                        ->orWhere('isbn', 'like', "%{$query}%")
                        ->orWhere('publisher', 'like', "%{$query}%");
                });
            }

            // Filter berdasarkan kategori
            if (!empty($category)) {
                $bookQuery->where('category_id', $category);
            }

            // Ambil data buku dengan relasi category
            $books = $bookQuery->with('category')
                ->select('id', 'title', 'author', 'publication_year', 'category_id')
                ->orderBy('title')
                ->limit(5)
                ->get();

            // Format hasil sebagai array of objects
            $suggestions = $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'year' => $book->publication_year,
                    'category' => $book->category->name ?? 'Unknown'
                ];
            });

            return response()->json($suggestions);
        } catch (\Exception $e) {
            \Log::error('Search suggestion error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([], 500);
        }
    }

}