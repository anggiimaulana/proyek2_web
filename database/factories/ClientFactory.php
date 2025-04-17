<?php 
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('##########'),
            'name' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'jk' => 1,
            'status' => 1,
            'agama' => 1,
            'alamat' => $this->faker->address(),
            'pendidikan' => 1,
            'pekerjaan' => 1,
            'nomor_telepon' => $this->faker->phoneNumber(),
            'password' => bcrypt('password'),
        ];
    }
}
