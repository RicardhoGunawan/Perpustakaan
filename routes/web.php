<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberCardController;
use App\Http\Middleware\CheckMembership;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', function () {
    $latestBooks = \App\Models\Book::latest()->take(8)->get();
    $categories = \App\Models\Book::distinct()->pluck('category')->toArray();
    $categoryCounts = [];

    foreach ($categories as $category) {
        $categoryCounts[$category] = \App\Models\Book::where('category', $category)->count();
    }

    return view('home', compact('latestBooks', 'categories', 'categoryCounts'));
})->name('home');

// Authentication
Auth::routes();

// Book browsing (public)
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/{book}', [BookController::class, 'show'])->name('show');
});

// Routes for authenticated users only
Route::middleware('auth')->group(function () {

    // Loan routes
    Route::prefix('loans')->name('loans.')->middleware(CheckMembership::class)->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('index'); // 👈 INI YANG HILANG
        Route::post('/', [LoanController::class, 'store'])->name('store');
        Route::put('/{loan}/extend', [LoanController::class, 'extend'])->name('extend');

    });

    // Book request (for teachers)
    Route::post('/book-requests', [LoanController::class, 'storeRequest'])->name('book-requests.store');
    // Route untuk menampilkan daftar permintaan buku
    Route::get('/book-requests', [LoanController::class, 'bookRequestsIndex'])->name('book-requests.index');

    // Halaman untuk membuat permintaan buku baru (untuk guru)
    Route::get('/book-requests/create', [LoanController::class, 'createRequest'])->name('book-requests.create');



    // User profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::put('/student', [ProfileController::class, 'updateStudent'])->name('student.update');
        Route::put('/teacher', [ProfileController::class, 'updateTeacher'])->name('teacher.update');
    });

    // Member card
    Route::prefix('member-card')->name('member.card.')->group(function () {
        Route::get('/{id}', [MemberCardController::class, 'show'])->name('show');
        Route::get('/my-card', [MemberCardController::class, 'myCard'])->name('my');
        Route::get('/download-card', [MemberCardController::class, 'downloadCard'])->name('download');
    });

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
