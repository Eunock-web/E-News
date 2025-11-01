<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SourcesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Utilise un nom unique pour simuler les 10 sources
        $name = fake()->company() . ' News';

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            // Utilise l'API de Faker pour une URL de logo aléatoire
            'url_logo' => fake()->imageUrl(64, 64, 'logo', true, null, true, 'png'),
            'is_active' => true, // Toutes les sources sont actives par défaut
        ];
    }
}
