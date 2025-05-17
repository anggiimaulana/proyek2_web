<?php 
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kk_id' => 1,
            'nama_kepala_keluarga' => $this->faker->name(),
            'nomor_telepon' => $this->faker->phoneNumber(),
            'password' => bcrypt('password'),
        ];
    }
}
