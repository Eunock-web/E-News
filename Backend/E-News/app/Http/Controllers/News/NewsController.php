<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ArticlesRequestValidation;
use App\Models\Articles;

class NewsController extends Controller
{
    public function RetrieveArticle(ArticlesRequestValidation $request){
        //Recuperer la liste de categorie favoris du user
            $user_categorie = Auth::user()->categories_user;

            
    }

    public function ListeCategories(){
        //Recuperez la liste des categories
        $categories = Articles::all()->category();

        return reponse()->json([
            'data' => $categories,
            'message' => 'Categorie recuperer avec succes'
        ], 200);
    }

    public function ArticlesInfos(){
        //Recuperer la liste des articles
    }
}
