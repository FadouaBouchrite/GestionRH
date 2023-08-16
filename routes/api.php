<?php

use App\Http\Controllers\AssiduityController;
use App\Http\Controllers\AuthentificationController;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Route;
use App\Models\Todo;
use App\Models\User;
use App\Http\Controllers\EmployeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DemandeConge;
use App\Http\Controllers\DemandeCongeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SalaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/v1/todos', function(Request $request){
    $validator = Validator::make($request->all(), [
        'title' => 'required|unique:todos',
        'description' => 'required',
    ]);

    if($validator->fails())
    {
        return response()->json([
            'errors' => $validator->errors()->first()
        ], 422);
    }

    Todo::create($request->all());
    return response()->json(['success' => 'Todo Added'], 200);


});

Route::post('/v1/login',[AuthentificationController::class,'login']);

Route::post('/v1/register',[AuthentificationController::class,'register']);
Route::post('/v1/forgot',[AuthentificationController::class,'forgotPassword']);
Route::get('/v1/categoryName',[CategoryController::class,'getAllCategoriesName']);
Route::post('/v1/create',[CategoryController::class,'create']);
Route::get('/v1/employees', [EmployeController::class,'index']);
Route::get('v1/names',[CategoryController::class,'categoryNames']);
Route::get('v1/employees/{categorieNom}',[EmployeController::class,'getEmployeByCategory']);
Route::get('v1/order/{column}/{direction}/employees',[EmployeController::class,'getEmployeOrderBy']);
Route::get('v1/search/{searchTerm}/employees',[EmployeController::class,'getEmployeByTerm']);
Route::delete('v1/employees/{employee}',[EmployeController::class,'destroy']);
Route::get('/v1/employee/{empId}', [EmployeController::class,'empId']);
Route::get('/v1/categorie/{catId}', [CategoryController::class,'catId']);
Route::post('/v1/update/{employee}',[EmployeController::class,'update']);
Route::post('/v1/ajoutHistory',[HistoryController::class,'createHistory']);
Route::get('/v1/getHistory',[HistoryController::class,'index']);
Route::get('/v1/getHistoryByterm/{term}',[HistoryController::class,'getHistoryByterm']);
Route::get('/v1/getHistoryByEmploye/{id}',[HistoryController::class,'getHistoryByEmploye']);
Route::post('/v1/recordEntryTime',[AssiduityController::class,'recordEntryTime']);
Route::post('/v1/recordExitTime',[AssiduityController::class,'recordExitTime']);
Route::post('/v1/recordAbscence',[AssiduityController::class,'recordAbscence']);
Route::get('/v1/attendance/{empId}',[AssiduityController::class,'getEmployeeAttendance']);
Route::get('/v1/getAssiduite',[AssiduityController::class,'index']);
Route::get('/v1/getAttendenceById/{empId}',[AssiduityController::class,'getAttendenceById']);
Route::get('/v1/getAssiduiteByDate/{empId}/{date}',[AssiduityController::class,'getAssiduiteByDate']);
Route::get('/v1/getAssiduiteByDate2/{date}',[AssiduityController::class,'getAssiduiteByDate2']);
Route::post('/v1/createDemande',[DemandeCongeController::class,'createDemande']);
Route::get('/v1/getDemande',[DemandeCongeController::class,'index']);
Route::post('/v1/assiduityConge',[DemandeCongeController::class,'approveConge']);
Route::post('/v1/statusConge',[DemandeCongeController::class,'approveStatus']);
Route::post('/v1/ApproveRead',[NotificationController::class,'approveRead']);
Route::get('/v1/getNotificationByEmpId/{empId}',[NotificationController::class,'getNotificationByEmpId']);
Route::get('/v1/getNotification/{notId}',[NotificationController::class,'getNotificationById']);
Route::get('/v1/getNotificationByEmpIdPaginate/{empId}',[NotificationController::class,'getNotificationByEmpIdPaginate']);
Route::get('/v1/getEmployeeInfo',[SalaryController::class,'getEmployeeInfo']);
Route::post('/v1/updateRecords',[AssiduityController::class,'updateRecords']);









