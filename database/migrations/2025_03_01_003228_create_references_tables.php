<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabla para niveles (Low, Medium, High)
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        // Tabla para influencia de compañeros (Negative, Neutral, Positive)
        Schema::create('peer_influences', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        // Tabla para opciones binarias (Yes, No)
        Schema::create('binary_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        // Tabla para género (Male, Female)
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        // Tabla para tipo de escuela (Public, Private)
        Schema::create('school_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_types');
        Schema::dropIfExists('genders');
        Schema::dropIfExists('binary_options');
        Schema::dropIfExists('peer_influences');
        Schema::dropIfExists('levels');
    }

};