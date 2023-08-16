<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['id','nom', 'prenom', 'email', 'password', 'user_type', 'categorie_id','image'];

    // Relation avec la table "categories"
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    
    public function employmentHistory()
    {
        return $this->hasMany(EmploymentHistory::class, 'employeeId');
    }
}