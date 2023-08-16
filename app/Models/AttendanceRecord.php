<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'entry_time', 'exit_time', 'date',  'absence', 'raison'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }



}
