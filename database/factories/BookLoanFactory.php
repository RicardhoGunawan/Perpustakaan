<?php

namespace Database\Factories;

use App\Models\BookLoan;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookLoanFactory extends Factory
{
    protected $model = BookLoan::class;

    public function definition(): array
    {
        $loanDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $loanDate)->modify('+7 days');

        return [
            'user_id' => null, // Diisi dari seeder
            'book_id' => null, // Diisi dari seeder
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'return_date' => rand(0, 1) ? $dueDate : null,
            'status' => $this->faker->randomElement(['dipinjam', 'dikembalikan']),
            'notes' => $this->faker->optional()->sentence(),
            'quantity' => $this->faker->numberBetween(1, 2),
            'borrowed_for' => $this->faker->word(),
        ];
    }
}

