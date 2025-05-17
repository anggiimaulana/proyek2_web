<?php

namespace Database\Factories;

use App\Models\Kuwu;
use App\Models\Nik;
use Illuminate\Database\Eloquent\Factories\Factory;

class NikFactory extends Factory
{
    protected $model = Nik::class;

    public function definition(): array
    {
        return [
            'kk_id' => 1,
            'nomor_nik' => $this->faker->unique()->numerify('##########'),
            'name' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'hubungan' => 1,
            'jk' => 1,
            'status' => 1,
            'agama' => 1,
            'alamat' => $this->faker->address(),
            'pendidikan' => 1,
            'pekerjaan' => 1,
        ];
    }
}
