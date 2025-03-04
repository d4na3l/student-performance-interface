<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // Se asume que en un futuro se agreguen más campos (nombre, etc.)
            $table->foreignId('gender_id')->constrained('genders');
            $table->foreignId('school_type_id')->constrained('school_types');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};
