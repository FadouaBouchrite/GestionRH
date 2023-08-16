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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nom'); 
              $table->string('prenom'); 
           
             $table->string('email'); 
             $table->string('image'); 
             $table->string('password');
             $table->foreignId('categorie_id')->constraind()->onDelete('cascade');            
             $table->string('user_type')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};











