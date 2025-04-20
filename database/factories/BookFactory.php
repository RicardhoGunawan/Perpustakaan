<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Bahasa Indonesia', 'Bahasa Inggris', 'Sejarah', 'Ekonomi', 'Geografi', 'Sosiologi'];
        $kelas = ['10', '11', '12'];
        $publisherList = ['Erlangga', 'Yudhistira', 'Gramedia', 'Quadra', 'Intan Pariwara', 'Ganeca Exact'];

        $category = $this->faker->randomElement($categories);
        $class = $this->faker->randomElement($kelas);
        $title = "{$category} SMA Kelas {$class}";

        return [
            'title' => $title,
            'author' => $this->faker->name,
            'isbn' => $this->faker->isbn13,
            'publisher' => $this->faker->randomElement($publisherList),
            'publication_year' => $this->faker->numberBetween(2015, 2023),
            'description' => "Buku pelajaran {$category} untuk siswa SMA kelas {$class}.",
            'stock' => $this->faker->numberBetween(5, 30),
            'cover_image' => $this->faker->imageUrl(300, 400, 'education', true),
            'category' => $category,
        ];
    }
}
