<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use App\Models\DemandeConge;
use App\Models\Notification;

class DemandeCongeController extends Controller
{
    //
public function index(){
return DemandeConge::with('employee')->where('status','en cours')->paginate(3);


}

    public function createDemande(Request $request){

        $demandeConge = new DemandeConge();
        $demandeConge->employee_id = $request->input('id');
        $demandeConge->date_debut =$request->input('startDate') ;
        $demandeConge->date_fin = $request->input('endDate');
        $demandeConge->type_conges = $request->input('congeType');
        $demandeConge->raison = $request->input('reason');
        $demandeConge->date_demande = $request->input('date');
        
        // Optionnel : Vous pouvez également définir d'autres attributs si nécessaire
    
        // Enregistrer la demande de congé dans la base de données
        $demandeConge->save();
        return response()->json(['message' => 'Demande de congé créée avec succès'], 201);

    }
public function approveConge(Request $request){

    $demandeConge = DemandeConge::findOrFail($request->id);
    $dateDebut = $demandeConge->date_debut;
    $dateFin = $demandeConge->date_fin;
    $employeeId = $demandeConge->employee_id;

    // Calculez la liste de dates entre la date de début et la date de fin du congé
    $datesConge = $this->generateDateRange($dateDebut, $dateFin);

    // Insérez les enregistrements dans la table "AttendanceRecord" pour chaque date de congé
    foreach ($datesConge as $date) {
        AttendanceRecord::create([
            'employee_id' => $employeeId,
            'absence' => true,
            'raison' => 'congés',
            'date' => $date,
            'entry_time'=>null,
            'exit_time'=>null,
            // Ajoutez les autres valeurs nécessaires ici
        ]);
    }
return response()->json(['status'=>'success']);
}

private function generateDateRange($startDate, $endDate) {
    $dates = [];
    $currentDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    while ($currentDate <= $endDate) {
        $dates[] = date('Y-m-d', $currentDate);
        $currentDate = strtotime('+1 day', $currentDate);
    }

    return $dates;
}
public function approveStatus( Request $request){
    try {
        $demandeConge = DemandeConge::find($request->id);

        if (!$demandeConge) {
            return response()->json(['message' => 'Demande de congé non trouvée'], 404);
        }

        $demandeConge->update(['status' => $request->newStatus]);
        $notification = Notification::create([
            'employee_id' => $demandeConge->employee_id,
            'message' => $request->commentaire,
            'status' => $request->newStatus,
            'read' => false,
            'object'=>'reponse de demmande de congée' 
        ]);
        if ($notification->save()) {
            # code...
            return response()->json(['message' => 'la réponse est envoyer au employée avec succes'], 200);

        }
        ;
        return response()->json(['message' => 'Statut de la demande de congé mis à jour avec succès'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => $e], 500);
    }


}

}
