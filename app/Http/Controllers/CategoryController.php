<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\CategoryName;

class CategoryController extends Controller
{
    //
    public function getAllCategoriesName()
{
    // Récupérer toutes les catégories
    $categories = Categorie::all();
    // Extraire les noms de catégorie distincts dans un tableau
    $categoryNames = $categories->pluck('nom')->unique()->toArray();
    
    return response()->json(['status' => 'success', 'categories' => $categoryNames]);
    

}
public function create(Request $request){

    $request->validate([
        'nom' => 'required', // Assurez-vous que le nom est unique dans la table des catégories
        'competences'=>'required',
        'qualifications'=>'required',
        'experiences' =>'required',
        // Autres règles de validation pour les autres champs si nécessaire
    ]);

    // Créer une nouvelle catégorie avec les données fournies
    $category = new Categorie();
    $category->nom = $request->input('nom');
    $category->competences = $request->input('competences');
    $category->qualifications = $request->input('qualifications');
    $category->experiences= $request->input('experiences');
    
    $category->save();

    return response()->json(['message' => 'Catégorie ajoutée avec succès', 'category' => $category], 201);
}

public function categoryNames(){

return CategoryName::all();

}
public function catId ($catId){

    return Categorie::find($catId);




}


}

