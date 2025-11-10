<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ArticlesRequestValidation;
use App\Models\Articles;

class NewsController extends Controller
{
    /**
     * Récupérer la liste des catégories disponibles
     * Utilise Predis (Redis) pour mettre en cache les catégories
     * Fallback sur la base de données si Redis n'est pas disponible
     */
    public function listeCategories(Request $request)
    {
        // Clé de cache Redis pour les catégories
        $cacheKey = 'news:categories';
        $isCached = false;
        
        try {
            // Tenter de récupérer depuis le cache Redis avec Predis (durée: 1 heure)
            $categories = Cache::store('redis')->remember($cacheKey, 3600, function () {
                // Si pas en cache, récupérer depuis la base de données
                return Articles::select('category')
                    ->distinct()
                    ->orderBy('category')
                    ->pluck('category')
                    ->toArray();
            });
            
            // Vérifier si les données sont en cache
            $isCached = Cache::store('redis')->has($cacheKey);
        } catch (\Exception $e) {
            // Si Redis n'est pas disponible, récupérer directement depuis la base de données
            Log::warning('Redis cache unavailable, falling back to database', [
                'error' => $e->getMessage(),
                'cache_key' => $cacheKey
            ]);
            
            $categories = Articles::select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category')
                ->toArray();
        }

        return response()->json([
            'success' => true,
            'data' => $categories,
            'response' => 'Categories retrieved successfully',
            'cached' => $isCached
        ], 200);
    }

    /**
     * Récupérer tous les articles avec pagination
     */
    public function articlesInfos(Request $request)
    {
        try {
            // Pagination par défaut: 15 articles par page
            $perPage = $request->input('per_page', 15);
            $page = $request->input('page', 1);
            
            // Récupérer les articles avec pagination, triés par date de publication
            $articles = Articles::orderBy('published_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $articles->items(),
                'pagination' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                ],
                'response' => 'Articles retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Error retrieving articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les articles filtrés par les catégories favorites de l'utilisateur
     * Utilise Predis (Redis) pour mettre en cache les résultats
     * Fallback sur la base de données si Redis n'est pas disponible
     */
    public function articlesByUserCategories(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'response' => 'User not authenticated'
                ], 401);
            }

            // Récupérer les catégories de l'utilisateur
            $userCategories = $user->categories_user;
            
            if (empty($userCategories) || !is_array($userCategories)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'response' => 'No categories selected by user'
                ], 200);
            }

            // Clé de cache Redis basée sur l'ID utilisateur et ses catégories
            // Trier les catégories pour garantir la même clé de cache pour les mêmes catégories
            $sortedCategories = $userCategories;
            sort($sortedCategories);
            $categoriesHash = md5(implode(',', $sortedCategories));
            $cacheKey = "news:articles:user:{$user->id}:categories:{$categoriesHash}";
            $isCached = false;
            
            try {
                // Tenter de récupérer depuis le cache Redis avec Predis (durée: 30 minutes)
                $articles = Cache::store('redis')->remember($cacheKey, 1800, function () use ($userCategories) {
                    // Si pas en cache, récupérer depuis la base de données
                    return Articles::whereIn('category', $userCategories)
                        ->orderBy('published_at', 'desc')
                        ->get()
                        ->toArray();
                });
                
                // Vérifier si les données sont en cache
                $isCached = Cache::store('redis')->has($cacheKey);
            } catch (\Exception $redisException) {
                // Si Redis n'est pas disponible, récupérer directement depuis la base de données
                Log::warning('Redis cache unavailable, falling back to database', [
                    'error' => $redisException->getMessage(),
                    'cache_key' => $cacheKey,
                    'user_id' => $user->id
                ]);
                
                $articles = Articles::whereIn('category', $userCategories)
                    ->orderBy('published_at', 'desc')
                    ->get()
                    ->toArray();
            }

            return response()->json([
                'success' => true,
                'data' => $articles,
                'categories' => $userCategories,
                'response' => 'Articles retrieved successfully',
                'cached' => $isCached
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Error retrieving articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer un article spécifique par son ID
     */
    public function retrieveArticle($id)
    {
        try {
            $article = Articles::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $article,
                'response' => 'Article retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response' => 'Article not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
