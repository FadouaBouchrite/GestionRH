<?php

namespace App\Http\Controllers;
use Exception; 
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\Employee;
use DateTime;
class NotificationController extends Controller
{
    //
    public function index(){



    }
    public function getNotificationByEmpId($empId){
 
        $notifications = Notification::with('employee')->where('employee_id', $empId)->get();

        return response()->json(['data' => $notifications]);
        
    }
    public function getNotificationByEmpIdPaginate($empId){

        return Notification::with('employee')->where('employee_id',$empId)->paginate(10);
        
        
            }
    public function approveRead(Request $request){

        $notification = Notification::find($request->notId);

        $notification->update(['read' => 1]);
        
        if ($notification->save()) {
           return response()->json(['status' => 'success']);
        }


    }
    public function getNotificationById($notId){

        $notification = Notification::with('employee')->find($notId);
        return $notification;

    }






    public function checkHolidaysAndNotify()
    {
        try {
            $currentDate = new DateTime();
            $currentDay = $currentDate->format('d')+1;
            $currentMonth = $currentDate->format('m');

            // Récupérez tous les jours fériés stockés dans la base de données
            $holidays = Holiday::all();

            // Parcourez les jours fériés et comparez les dates
            foreach ($holidays as $holiday) {
                $holidayDate = new DateTime($holiday->date);
$holidayDay = $holidayDate->format('d');
$holidayMonth = $holidayDate->format('m');


                // Si le jour et le mois actuels correspondent au jour férié
                
                
                
                if ($currentDay == $holidayDay && $currentMonth === $holidayMonth) {
                    // Récupérez la liste des employés
                    $employees = Employee::all();

                    // Pour chaque employé, insérez une notification pour le jour férié
                    foreach ($employees as $employee) {
                        $notification = new Notification([
                            'employee_id' => $employee->id,
                            'object'=>'jour feriée',
                            'message' => "Demain c'est " . $holiday->description . ", un jour férié. bon fête",
                            'read' => 0,
                            'status'=>null,
                        ]);

                        $notification->save();
                    }
                }else{


                 
                        $notification = new Notification([
                            'employee_id' => 11,
                            'object'=>'jour feriée',
                            'message' => "C'est pas un jour férié.",
                            'read' => 0,
                            'status'=>null,
                        ]);

                        $notification->save();

                

                    
                }
            }
        } catch (Exception $e) {
            Log::error('Erreur lors de la vérification des jours fériés et de l\'insertion des notifications : ' . $e->getMessage());
        }
    }

public function holidayNotification(Request $request){

    $employees = Employee::all();

    // Pour chaque employé, insérez une notification pour le jour férié
    foreach ($employees as $employee) {
        $notification = new Notification([
            'employee_id' => $employee->id,
            'message' => "C'est " . $request->message. ", un jour férié.",
            'read' => false,
            'status'=>null,
            'object'=>'jor férié',
        ]);

        $notification->save();


    }


}




    





    
}
