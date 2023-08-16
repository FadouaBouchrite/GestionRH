<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeConge extends Model
{
    use HasFactory;
    protected $fillable = [
        'employeeId','date_demande','date_debut','date_fin','type_conges','raison','status','commentaire'  
        
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
