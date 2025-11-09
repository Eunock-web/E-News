<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ArticlesRequestValidation;
use App\Models\Articles;

class NewsController extends Controller
{
    /**
     * Récupérer les articles selon les catégories favorites de l'utilisateur
     * Utilise Redis pour mettre en cache les catégories
     */
    public function listeCategories(Request $request)
    {
        // Clé de cache Redis pour les catégories
        $cacheKey = 'news:categories';
        
        // Tenter de récupérer depuis le cache Redis (durée: 1 heure)
        $categories = Cache::store('redis')->remember($cacheKey, 3600, function () {
            // Si pas en cache, récupérer depuis la base de données
            $categories = Articles::select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category')
                ->toArray();
            
            return $categories;
        });

        return response()->json([
            'data' => $categories,
            'message' => 'Categories retrieved successfully',
            'cached' => Cache::store('redis')->has($cacheKey)
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
                'data' => $articles->items(),
                'pagination' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                ],
                'message' => 'Articles retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les articles filtrés par les catégories favorites de l'utilisateur
     * Utilise Redis pour mettre en cache les résultats
     */
    public function articlesByUserCategories(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Récupérer les catégories de l'utilisateur
            $userCategories = $user->categories_user;
            
            if (empty($userCategories) || !is_array($userCategories)) {
                return response()->json([
                    'data' => [],
                    'message' => 'No categories selected by user'
                ], 200);
            }

            // Clé de cache Redis basée sur l'ID utilisateur et ses catégories
            $categoriesHash = md5(implode(',', $userCategories));
            $cacheKey = "news:articles:user:{$user->id}:categories:{$categoriesHash}";
            
            // Tenter de récupérer depuis le cache Redis (durée: 30 minutes)
            $articles = Cache::store('redis')->remember($cacheKey, 1800, function () use ($userCategories) {
                // Si pas en cache, récupérer depuis la base de données
                return Articles::whereIn('category', $userCategories)
                    ->orderBy('published_at', 'desc')
                    ->get()
                    ->toArray();
            });

            return response()->json([
                'data' => $articles,
                'categories' => $userCategories,
                'message' => 'Articles retrieved successfully',
                'cached' => Cache::store('redis')->has($cacheKey)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving articles',
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
                'data' => $article,
                'message' => 'Article retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Article not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
