<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employeeId','emp_first','emp_familly','jobTitle','startDate','endDate','achievements'  
        
    ];
    protected $collection = 'employment_history';

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeId');
    }
}