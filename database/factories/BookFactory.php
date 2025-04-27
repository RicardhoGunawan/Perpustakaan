<?php

namespace Database\Factories;

use App\Models\Category; // Pastikan mengimpor model Category
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        // Ambil kategori acak dari tabel categories
        $category = Category::inRandomOrder()->first(); // Ambil kategori acak

        // Ambil data lainnya seperti sebelumnya
        $kelas = ['X', 'XI', 'XII'];
        $publisherList = ['Erlangga', 'Yudhistira', 'Gramedia', 'Quadra', 'Intan Pariwara', 'Ganeca Exact'];
        $class = $this->faker->randomElement($kelas);
        $title = "{$category->name} SMA Kelas {$class}"; // Gunakan nama kategori untuk judul buku

        return [
            'title' => $title,
            'author' => $this->faker->name,
            'isbn' => $this->faker->isbn13,
            'publisher' => $this->faker->randomElement($publisherList),
            'publication_year' => $this->faker->numberBetween(2015, 2023),
            'description' => "Buku pelajaran {$category->name} untuk siswa SMA kelas {$class}.",
            'stock' => $this->faker->numberBetween(5, 30),
            'cover_image' => $this->faker->imageUrl(300, 400, 'education', true),
            'category_id' => $category->id, // Simpan category_id
        ];
    }
}
