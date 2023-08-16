<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;

class AssiduityController extends Controller
{
    //
    public function index(){

return AttendanceRecord::with('employee')->paginate(3);

    }
    public function recordEntryTime(Request $request) {
        $employeeId = $request->input('empId'); // ID de l'employé actuellement connecté
        $currentDate = Carbon::now(); // Date actuelle
        $entryTime =$request->input('entryTime'); // Heure d'entrée
    
        // Créer un nouvel enregistrement d'assiduité avec l'heure d'entrée
        $attendanceRecord = new AttendanceRecord([
            'employee_id' => $employeeId,
            'date' => $currentDate->toDateString(),
            'entry_time' => $entryTime
            // Autres champs si nécessaire
        ]);
    
        // Enregistrer l'enregistrement d'assiduité dans la base de données
        $attendanceRecord->save();
    
        return response()->json(['message' => 'Heure d\'entrée enregistrée avec succès.']);
    }

    public function recordExitTime(Request $request) {
        $employeeId =$request->input('empId'); // ID de l'employé actuellement connecté
        $currentDateTime = Carbon::now(); // Date et heure actuelles
    
        // Rechercher l'enregistrement d'assiduité correspondant à l'heure d'entrée pour la date actuelle
        $attendanceRecord = AttendanceRecord::where('employee_id', $employeeId)
            ->where('date', $currentDateTime->toDateString()) // Format de date
            ->first();
    
        if ($attendanceRecord) {
            // Mettre à jour l'heure de sortie
            $attendanceRecord->exit_time = $request->input('exitTime');
            $attendanceRecord->raison = null;
            $attendanceRecord->absence=false;
            // Format d'heure
            $attendanceRecord->save();
            return response()->json(['message' => 'Heure de sortie enregistrée avec succès.']);
        } else {
            return response()->json(['error' => 'Aucune entrée d\'assiduité trouvée pour la date actuelle.']);
        }
    }

    public function recordAbscence(Request $request){
        $employeeId =$request->input('empId'); // ID de l'employé actuellement connecté
        $absence = new AttendanceRecord([
            'employee_id' => $employeeId,
            'raison' => $request->input('raison'),
            'date' => $request->input('date'),
            'entry_time'=>null,
            'exit_time'=>null,
            'absence'=>true,
        ]);
    
        $absence->save();
return response()->json(['status'=>'success']);

    }


    // public function getEmployeeAttendance($empId)
    // {
    //     // Récupérer les données d'assiduité pour l'employé spécifique
    //     $attendanceData = AttendanceRecord::select(DB::raw('DATE(date) as date'), DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(exit_time, entry_time))) as total_seconds'))
    //         ->where('employee_id', $empId)
    //         ->groupBy('date')
    //         ->get();

    //     // Convertir les secondes en heures
    //     foreach ($attendanceData as $record) {
    //         $record->total_hours = gmdate('H:i:s', $record->total_seconds);
    //         unset($record->total_seconds);
    //     }

    //     return response()->json($attendanceData);
    // }


    public function getEmployeeAttendance($empId)
{
    // Récupérer les données d'assiduité pour l'employé spécifique
    $attendanceData = AttendanceRecord::select(DB::raw('DATE(date) as date'), 
        DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(exit_time, entry_time))) as total_seconds'))
        ->where('employee_id', $empId)
        ->groupBy('date')
        ->get();

    // Convertir les secondes en heures et gérer les valeurs nulles
    foreach ($attendanceData as $record) {
        if ($record->total_seconds !== null) {
            $record->total_hours = gmdate('H:i:s', $record->total_seconds);
        } else {
            $record->total_hours = '00:00:00'; // Heures totales définies à zéro
        }
        unset($record->total_seconds);
    }

    return response()->json($attendanceData);
}




public function getAttendenceById($empId){


    return  $attendance = AttendanceRecord::with('employee')
    ->where(function ($query) use ($empId) {
        $query->where('employee_id', $empId)
            ->orWhereHas('employee', function ($subQuery) use ($empId) {
                $subQuery->where('nom', 'like', '%' . $empId . '%')
                         ->orWhere('prenom', 'like', '%' . $empId . '%');
            });
    })
    ->paginate(3);


}
public function getAssiduiteByDate($empId,$date){
    $attendance = AttendanceRecord::with('employee')
        ->where(function ($query) use ($empId, $date) {
            $query->where('employee_id', $empId)
                  ->where('date', $date);
        })
        ->paginate(3);

    return response()->json($attendance);



}
public function getAssiduiteByDate2($date){
    $attendance = AttendanceRecord::with('employee')
    ->where(function ($query) use ( $date) {
        $query->where('date', $date);
    })
    ->paginate(3);

return response()->json($attendance);


}




public function updateRecords(Request $request)
{
    $modifiedRecords = $request->input('modifiedRecords'); // Données modifiées depuis la requête

   
        foreach ($modifiedRecords as $record) {
            $assiduite = AttendanceRecord::find($record['id']); // Recherche de l'enregistrement par ID

            if ($assiduite) {
                // Mise à jour des champs modifiés
                if (isset($record['date'])) {
                    $assiduite->date = $record['date'];
                }
                
                if (isset($record['entry_time'])) {
                    $assiduite->entry_time = $record['entry_time'];
                }
                
                if (isset($record['exit_time'])) {
                    $assiduite->exit_time = $record['exit_time'];
                }
                // Mettez à jour d'autres champs si nécessaire

                // Sauvegarde des modifications
                $assiduite->save();
            }
        }

        return response()->json(['message' => 'Modifications enregistrées avec succès']);
    
}

}
