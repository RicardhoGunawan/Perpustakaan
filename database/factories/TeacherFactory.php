<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'nip' => $this->faker->unique()->numerify('NIP####'),
            'full_name' => $this->faker->name(),
            'subject' => $this->faker->randomElement(['Math', 'Science', 'History', 'English']),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'profile_photo' => $this->faker->imageUrl(200, 200, 'people'),
        ];
    }
}
