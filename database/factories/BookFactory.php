<?php
namespace Database\Factories;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'isbn' => $this->faker->isbn13(),
            'publisher' => $this->faker->company(),
            'publication_year' => $this->faker->year(),
            'description' => $this->faker->paragraph(),
            'stock' => $this->faker->numberBetween(5, 20),
            'cover_image' => $this->faker->imageUrl(300, 400, 'books', true),
            'category' => $this->faker->word(),
        ];
    }
}
