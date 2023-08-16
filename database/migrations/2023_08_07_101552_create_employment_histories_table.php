<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employment_histories', function (Blueprint $table) {
            $table->id();
           
            $table->unsignedBigInteger('employeeId');
            $table->string('jobTitle'); 
            $table->string('emp_first');
             $table->string('emp_familly');
            $table->date('startDate'); // Colonne de type date
            $table->date('endDate')->nullable(); 
            $table->string('achievements');
 $table->timestamps();
          





        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_histories');
    }
};






