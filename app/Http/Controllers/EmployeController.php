<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Models\Employee;
class EmployeController extends Controller
{
    //

    public function index()
     {
 

return Employee::with('categorie')->paginate(10);


 }



 public function empId($empId){
    return Employee::find($empId);


 }

public function update(Request $request,Employee $employee){

    if($request->file('image')){

 $imageFile = $request->file('image');
    $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
    $imageFile->move(public_path('images/employees'), $imageName);

    $employee->update([
    'nom'=>$request->input('nom'),
    'prenom'=>$request->input('prenom'),
'email'=>$request->input('email'),
'image'=>$imageName,



]);
DB::table('categories')
    ->join('employees', 'categories.id', '=', 'employees.categorie_id')
    ->update([
        'categories.qualifications' => $request->input('qualification'),
        'categories.experiences' => $request->input('experience'),
        'categories.competences' => $request->input('competence'),
    ]);




    return response()->json(['status'=>1,'message' => 'employe mit à jour avec succès']);


}else{

    $employee->update([
    'nom'=>$request->input('nom'),
    'prenom'=>$request->input('prenom'),
'email'=>$request->input('email'),




]);



DB::table('categories')
    ->join('employees', 'categories.id', '=', 'employees.categorie_id')
    ->update([
        'categories.qualifications' => $request->input('qualification'),
        'categories.experiences' => $request->input('experience'),
        'categories.competences' => $request->input('competence'),
    ]);




    return response()->json(['status'=>1,'message' => 'employe mit à jour avec succès']);

}


    


   



}

public function destroy(Employee $employee){


    $employee->delete();
    return ['message'=>'employe supprimer'];
}
public function getEmployeByCategory($categorieNom){
if ($categorieNom===null) {
    return Employee::with('categorie')->paginate(10);

}else{
    return Employee::whereHas('categorie', function ($query) use ($categorieNom) {
        $query->where('nom', $categorieNom);
    })->with('categorie')->paginate(10);  


}


}
public function getEmployeOrderBy($column,$direction){


return Employee::with('categorie')->orderBy($column,$direction)->paginate(4);




}

public function getEmployeByTerm($term){

    $employees = Employee::with('categorie')
    ->where(function ($query) use ($term) {
        $query->where('id', 'like', '%' . $term . '%')
            ->orWhere('nom', 'like', '%' . $term . '%')
            ->orWhere('prenom', 'like', '%' . $term . '%')
            ->orWhere('email', 'like', '%' . $term . '%')
            ->orWhereHas('categorie', function ($query) use ($term) {
                $query->where('nom', 'like', '%' . $term . '%');
            });
    })->paginate(4);
return $employees;

}


}
