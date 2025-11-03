<?php

namespace Database\Seeders;

use App\Models\Sources; // Le nom de la classe est Sources
use App\Models\Articles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. Préparation PostgreSQL : Désactiver temporairement les contraintes ---
        DB::unprepared('
            ALTER TABLE sources DISABLE TRIGGER ALL;
            ALTER TABLE articles DISABLE TRIGGER ALL;
            ALTER TABLE users DISABLE TRIGGER ALL;
        ');
        
        // --- 2. Suppression des anciennes données ---
        // Utilisation des noms de modèles PLURIELS pour toutes les suppressions
        Articles::truncate(); 
        Sources::truncate();
        User::truncate();

        // --- 3. Création des 10 Sources ---
        // Utilisation du modèle PLURIEL pour la factory
        $sources = Sources::factory(10)->create();

// --- 4. Création des 10 000 Articles (Volume pour les tests de perf.) ---
        
        // Rétablir l'appel FACTORY (qui est plus tolérant avec les dépendances)
        Articles::factory(10000)
            ->recycle($sources)
            ->create();

        // --- 5. Création de l'Utilisateur de Test ---
        User::factory()->create([
             'name' => 'testuser',
             'email' => 'test@example.com',
             'password' => Hash::make('password'), 
             'remember_token' => Str::random(10),
             'categories_user' => json_encode(['Tech', 'Sport', 'Finance']),
        ]);
        
        // --- 6. Réactivation des contraintes PostgreSQL ---
        DB::unprepared('
            ALTER TABLE sources ENABLE TRIGGER ALL;
            ALTER TABLE articles ENABLE TRIGGER ALL;
            ALTER TABLE users ENABLE TRIGGER ALL;
        ');

        // Affichage de la réussite
        echo "\n✅ Base de données initialisée avec succès :";
        echo "\n   - Utilisateur de test créé (test@example.com / password)";
        echo "\n   - 10 Sources générées";
        echo "\n   - 10 000 Articles générés pour les tests de performance (Redis)";
    }
}