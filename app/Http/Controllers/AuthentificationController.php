<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
// ...


class AuthentificationController extends Controller
{
    //
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Récupérer les informations d'identification de l'utilisateur depuis la requête
        $email = $request->input('email');
        $password = $request->input('password');

        // Rechercher l'employé dans la table "employes" en fonction de l'adresse e-mail
        $employe = Employee::where('email', $email)->first();

        // Vérifier si l'employé existe et si le mot de passe correspond
        if ($employe && password_verify($password, $employe->password)) {
            // L'employé existe et les informations d'identification sont correctes
            return response()->json([
                'code' => 'succes',
                'status' => 1,
                'user' => $employe,
            ]);
        } elseif (!$employe) {
            // L'employé n'existe pas ou les informations d'identification sont incorrectes




            return response()->json(['status'=>0,'message' => 'email incorrecte']);
        } elseif (!password_verify($password, $employe->password)) {
            return response()->json(['status'=>0,'message' => 'mot de passe incorrecte',
            'user' => null,]);
        } else {

            return response()->json(['status'=>0,'message' => 'password et mot de passe incorrecte',
            'user' => null,]);
        }
    }





    public function register(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:6',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        try{
    
        //Récupérer le fichier image à partir de la requête
//$imageName = $request->file('image')->store('employees', 'public');

         $imageFile = $request->file('image');
         $imageName = time() . '.' . $imageFile->getClientOriginalExtension();
    
        // Déplacer l'image vers le dossier de destination
        $imageFile->move(public_path('images/employees'), $imageName);
    
        // Créer un nouvel employé avec les données fournies
        $employe = new Employee([
            'id' => $request->input('id'),
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'categorie_id' => $request->input('categorieId'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'image' => $imageName,
        ]);
    
        // Enregistrer l'employé dans la base de données
        if ($employe->save()) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to save employee'], 500);
        }
    }
    
    
    catch (\Illuminate\Database\QueryException $e) {
        // Si une exception est levée lors de l'enregistrement
        if ($e->getCode() === '23000') {
            // SQLSTATE 23000 correspond à une violation de contrainte d'intégrité
            return response()->json(['status' => 'error','code'=>0, 'message' => 'Vous avez violé la clé primaire.'], 500);
        }

        // Autres types d'exceptions non gérées spécifiquement
        return $e->getMessage();    }
    }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Rechercher l'employé dans la table "employes" en fonction de l'adresse e-mail
        $employe = Employee::where('email', $email)->first();

        if (!$employe) {
            return response()->json(['code'=>0,'status' => 'failed', 'message' => 'Email not found']);
        }

        // Générer un mot de passe aléatoire
        $newPassword = $employe->nom.Str::random(5);

        // Définir le nouveau mot de passe pour l'employé
        $employe->password = Hash::make($newPassword);
        $employe->save();

        // Envoyer l'e-mail contenant le nouveau mot de passe à l'employé
        $subject='Reset password';
        $mssg="votre nouveau code est ".$newPassword;
        
            mail($email, $subject, $mssg);
          
            
        
        return response()->json(['code'=>1,'status' => 'success', 'message' => 'Password reset email sent']);
    }



    


}
   