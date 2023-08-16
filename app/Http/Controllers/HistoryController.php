<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\employmentHistory;
use GuzzleHttp\Psr7\Message;

class HistoryController extends Controller
{
    
    public function index(){

return employmentHistory::with('employee')->paginate(2);

    }
    //
    public function createHistory(Request $request){

 // Vérification si une entrée similaire existe déjà
 $existingHistory = EmploymentHistory::where([
    'employeeId' => $request->input('id'),
    'jobTitle' => $request->input('categorie'),
    'startDate' => $request->input('dateDebut'),
    // Ajoutez d'autres conditions ici si nécessaire
])->first();

if ($existingHistory) {
    return response()->json(['status' => 'succes', 'message' => 'Ajout avec succès']);
}

// Création d'une nouvelle entrée
$history = new EmploymentHistory([
    'employeeId' => $request->input('id'),
    'jobTitle' => $request->input('categorie'),
    'emp_first' => $request->input('prenom'),
    'emp_familly' => $request->input('nom'),
    'startDate' => $request->input('dateDebut'),
    'endDate' => $request->input('dateFin'),
    'achievements' => $request->input('realisations'),
]);

if ($history->save()) {
    return response()->json(['status' => 'success', 'message' => 'Ajout avec succès']);
}

return response()->json(['status' => 'error', 'message' => 'Une erreur est survenue lors de l\'ajout.']);
}
public function getHistoryByterm($term){


    $histories = employmentHistory::with('employee')
    ->where(function ($query) use ($term) {
        $query->where('employeeId', 'like', '%' . $term . '%')
            ->orWhere('jobTitle', 'like', '%' . $term . '%')
            ->orWhere('emp_first', 'like', '%' . $term . '%')
            ->orWhere('emp_familly', 'like', '%' . $term . '%')
            ->orWhere('startDate', 'like', '%' . $term . '%')
            ->orWhere('endDate', 'like', '%' . $term . '%')
            ->orWhere('achievements', 'like', '%' . $term . '%');

            

    })->paginate(4);
return $histories;



}
public function getHistoryByEmploye($id){
    $history = employmentHistory::with('employee')->where('employeeId', $id)->paginate(4);
    return $history;


}
    
}