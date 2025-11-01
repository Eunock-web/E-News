<?php

namespace Database\Factories;

use App\Models\Sources;
use App\Enums\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ArticlesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Choisit une catégorie au hasard parmi les 15
        $categories = ArticleCategory::all();
        $randomCategory = fake()->randomElement($categories);

        // Date de publication aléatoire dans les 6 derniers mois
        $publishedAt = fake()->dateTimeBetween('-6 months', 'now');

        return [
            // L'ID de la source sera défini par le Seeder (voir étape 4)
            'source_id' => Sources::factory(),
            'category' => $randomCategory, 
            'title' => fake()->sentence(6),
            'summary' => fake()->sentence(20),  
            // Contenu long pour simuler un article réel
            'content' => fake()->paragraphs(10, true), 
            // URL d'image de couverture aléatoire
            'url_image' => fake()->imageUrl(800, 600, $randomCategory, true),
            'published_at' => $publishedAt,
        ];
    }
}
