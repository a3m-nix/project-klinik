<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Daftar>
 */
class DaftarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $availablePasienIds = \App\Models\Pasien::pluck('id')->toArray();
        return [
            'pasien_id' => $this->faker->randomElement($availablePasienIds),
            'tanggal_daftar' => $this->faker->date(),
            'poli' => $this->faker->randomElement(['Poli Umum', 'Poli Gigi', 'Poli Mata']),
            'keluhan' => $this->faker->sentence(),
            'diagnosis' => $this->faker->optional()->sentence(),
            'tindakan' => $this->faker->optional()->sentence(),
        ];
    }
}
