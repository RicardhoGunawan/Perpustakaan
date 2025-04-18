<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'nis' => $this->faker->unique()->numerify('NIS###'),
            'full_name' => $this->faker->name(),
            'class' => $this->faker->randomElement(['10A', '10B', '11A', '12C']),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'date_of_birth' => $this->faker->date('Y-m-d', '-15 years'),
            'profile_photo' => $this->faker->imageUrl(200, 200, 'people'),
        ];
    }
}
