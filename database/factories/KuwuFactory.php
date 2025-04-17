<?php

namespace Database\Factories;

use App\Models\Kuwu;
use Illuminate\Database\Eloquent\Factories\Factory;

class KuwuFactory extends Factory
{
    protected $model = Kuwu::class;

    public function definition(): array
    {
        return [
            'nip' => $this->faker->numerify('##########'),
            'nama' => $this->faker->name,
            'jk' => $this->faker->randomElement([1, 2]), // 1 untuk Laki-laki, 2 untuk Perempuan
            'status' => $this->faker->randomElement([1, 2]), // Misalnya status: 1 untuk menikah, 2 untuk belum
            'agama' => $this->faker->randomElement([1, 2, 3, 4]), // Asumsi ada 4 agama
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Password yang sudah di-hash
        ];
    }
}
