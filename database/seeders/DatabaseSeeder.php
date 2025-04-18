<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookLoan;
use App\Models\BookRequest;
use App\Models\Member;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user
        $users = User::factory(20)->create();

        // Buat books
        $books = Book::factory(10)->create();

        // Assign beberapa user sebagai member
        $users->take(10)->each(function ($user) {
            Member::factory()->create(['user_id' => $user->id]);
        });

        // Assign beberapa user sebagai student
        $users->slice(10, 5)->each(function ($user) {
            Student::factory()->create(['user_id' => $user->id]);
        });

        // Assign beberapa user sebagai teacher
        $users->slice(15, 5)->each(function ($user) {
            Teacher::factory()->create(['user_id' => $user->id]);
        });

        // Buat book loans (pinjaman)
        foreach ($users as $user) {
            BookLoan::factory(2)->create([
                'user_id' => $user->id,
                'book_id' => $books->random()->id,
            ]);
        }

        // Buat book request
        foreach ($users as $user) {
            BookRequest::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
