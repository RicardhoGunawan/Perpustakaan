<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookLoan;
use App\Models\BookRequest;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = BookLoan::where('user_id', Auth::id());

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('loan_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('loan_date', '<=', $request->date_to);
        }

        $loans = $query->with('book')->latest('loan_date')->paginate(10)->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
            'borrowed_for' => 'required_if:is_teacher,true|nullable|string',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Check if book is available
        $availableStock = $book->availableStock;
        if ($availableStock < $request->quantity) {
            return back()->with('error', 'Jumlah buku yang tersedia tidak mencukupi.');
        }

        // Check if user already has an active loan for this book
        $existingLoan = BookLoan::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->where('status', 'dipinjam')
            ->first();

        if ($existingLoan) {
            return back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
        }

        // Create loan
        $loan = new BookLoan();
        $loan->user_id = Auth::id();
        $loan->book_id = $request->book_id;
        $loan->quantity = $request->quantity;
        $loan->loan_date = now();
        $loan->due_date = $request->due_date;
        $loan->status = 'dipinjam';
        $loan->notes = $request->notes;

        // Add borrowed_for field if user is a teacher
        if (Auth::user()->hasRole('guru')) {
            $loan->borrowed_for = $request->borrowed_for;
        }

        $loan->save();

        return redirect()->route('loans.index')->with('success', 'Buku berhasil dipinjam. Silakan ambil buku Anda di perpustakaan.');
    }

    public function extend(Request $request, BookLoan $loan)
    {
        $request->validate([
            'new_due_date' => 'required|date|after:today',
            'notes' => 'required|string',
        ]);

        // Check if loan belongs to current user
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if loan can be extended (still active and not already extended)
        if ($loan->status !== 'dipinjam' || $loan->extended) {
            return back()->with('error', 'Peminjaman ini tidak dapat diperpanjang.');
        }

        // Update loan
        $loan->due_date = $request->new_due_date;
        $loan->extended = true;
        $loan->extension_notes = $request->notes;
        $loan->save();

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil diperpanjang.');
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Only teachers can request books
        if (!Auth::user()->hasRole('guru')) {
            abort(403);
        }

        $bookRequest = new BookRequest();
        $bookRequest->user_id = Auth::id();
        $bookRequest->title = $request->title;
        $bookRequest->author = $request->author;
        $bookRequest->publisher = $request->publisher;
        $bookRequest->description = $request->description;
        $bookRequest->status = 'pending';
        $bookRequest->save();

        return redirect()->route('book-requests.index')->with('success', 'Request buku berhasil diajukan.');
    }

    public function bookRequestsIndex()
    {
        // Only fetch requests made by the currently logged-in user
        $bookRequests = BookRequest::where('user_id', Auth::id())->get();

        return view('bookrequests.index', compact('bookRequests'));
    }

    public function createRequest()
    {
        // Only teachers can create book requests
        if (!Auth::user()->hasRole('guru')) {
            abort(403);
        }

        return view('bookrequests.create');
    }
}
