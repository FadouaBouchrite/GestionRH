<?php

namespace App\Http\Controllers;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Exception; 
use App\Models\Employee;
class SalaryController extends Controller
{
    //
    public function calculateSalary($employeeId)
    {
        // Supposons que le taux de salaire est de 12 $ par heure
        $hourlyRate = 12;

        // Récupérer les enregistrements d'assiduité pour l'employé donné
        $attendanceRecords = AttendanceRecord::where('employee_id', $employeeId)->get();

        $totalHoursWorked = 0;
        
        // Calculer le total des heures travaillées en parcourant les enregistrements
        foreach ($attendanceRecords as $record) {
            $entryTime = strtotime($record->entry_time);
            $exitTime = strtotime($record->exit_time);

            $hoursWorked = ($exitTime - $entryTime) / 3600; // Convertir en heures
            $totalHoursWorked += $hoursWorked;
        }

        // Calculer le salaire en fonction des heures travaillées
        $totalSalary = $totalHoursWorked * $hourlyRate;

        return response()->json([
            'employee_id' => $employeeId,
            'total_hours_worked' => $totalHoursWorked,
            'total_salary' => $totalSalary,
        ]);
    }

//     public function getEmployeeInfo()
// {
//     try {
//         $employees = Employee::all();

//         $employeeInfoList = [];

//         foreach ($employees as $employee) {
//             $attendanceRecords = AttendanceRecord::where('employee_id', $employee->id)
//                 ->whereMonth('date', now()->month) // Filtrer par le mois actuel
//                 ->get();

//             $totalHoursWorked = 0;

//             foreach ($attendanceRecords as $record) {
//                 if ($record->exit_time && $record->entry_time) {
//                     $exitTime = strtotime($record->exit_time);
//                     $entryTime = strtotime($record->entry_time);
//                     $totalHours = ($exitTime - $entryTime) / 3600; // Convertir en heures
//                     $totalHoursWorked += $totalHours;
//                 }
//             }

//             $currentSalary = $totalHoursWorked * 13; // Taux horaire de 13 $

//             $employeeInfo = [
//                 'id' => $employee->id,
//                 'name' => $employee->nom . ' ' . $employee->prenom,
//                 'totalHoursWorked' => number_format($totalHoursWorked, 2), // Formatage à deux décimales
//                 'currentSalary' => number_format($currentSalary, 2), // Formatage à deux décimales
//             ];

//             $employeeInfoList[] = $employeeInfo;
//         }

//         return response()->json($employeeInfoList);
//     } catch (Exception $e) {
//         return response()->json(['error' => 'Une erreur s\'est produite.'], 500);
//     }
// }
public function getEmployeeInfo()
{
    try {
        $employees = Employee::all();

        $employeeInfoList = [];

        foreach ($employees as $employee) {
            $attendanceRecords = AttendanceRecord::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month) // Filtrer par le mois actuel
                ->get();

            $totalHoursWorked = 0;

            foreach ($attendanceRecords as $record) {
                if ($record->exit_time !== null && $record->entry_time !== null && $record->exit_time != '00:00:00' ) {
                    $exitTime = strtotime($record->exit_time);
                    $entryTime = strtotime($record->entry_time);
                    $totalHours = ($exitTime - $entryTime) / 3600; // Convertir en heures
                    $totalHoursWorked += $totalHours;
                }
            }

            $currentSalary = $totalHoursWorked * 13; // Taux horaire de 13 $

            $employeeInfo = [
                'id' => $employee->id,
                'name' => $employee->nom . ' ' . $employee->prenom,
                'totalHoursWorked' => number_format($totalHoursWorked, 2), // Formatage à deux décimales
                'currentSalary' => number_format($currentSalary, 2), // Formatage à deux décimales
            ];

            $employeeInfoList[] = $employeeInfo;
        }

        return response()->json($employeeInfoList);
    } catch (Exception $e) {
        return response()->json(['error' => 'Une erreur s\'est produite.'], 500);
    }
}

}
