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
        Schema::create('demande_conges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('employee_id');

            $table->date('date_demande');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('type_conges');
            $table->string('raison');
            $table->string('commentaire')->nullable();
            $table->string('status')->default('en cours');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

       









        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_conges');
    }
};

