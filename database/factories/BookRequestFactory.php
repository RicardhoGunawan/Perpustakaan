<?php

namespace Database\Factories;

use App\Models\BookRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookRequestFactory extends Factory
{
    protected $model = BookRequest::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'publisher' => $this->faker->company(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'admin_notes' => $this->faker->optional()->sentence(),
            'processed_at' => $this->faker->optional()->dateTimeThisMonth(),
        ];
    }
}
