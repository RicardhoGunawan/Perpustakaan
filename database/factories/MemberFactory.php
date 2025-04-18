<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'member_number' => $this->faker->unique()->numerify('MBR####'),
            'valid_until' => $this->faker->dateTimeBetween('now', '+1 year'),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}

