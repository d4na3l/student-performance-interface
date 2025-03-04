<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('performance_records', function (Blueprint $table) {
            $table->id();
            // Relación con el estudiante
            $table->foreignId('student_id')->constrained('students');

            // Variables numéricas
            $table->float('hours_studied')->unsigned();
            $table->float('attendance')->unsigned();
            $table->float('sleep_hours')->unsigned();
            $table->float('previous_scores')->unsigned();
            $table->float('tutoring_sessions')->unsigned();
            $table->float('physical_activity')->unsigned();

            // Variables referenciadas (ordinales y nominales)
            $table->foreignId('access_to_resources_id')->constrained('levels');
            $table->foreignId('parental_involvement_id')->constrained('levels');
            $table->foreignId('motivation_level_id')->constrained('levels');
            $table->foreignId('family_income_id')->constrained('levels');
            $table->foreignId('peer_influence_id')->constrained('peer_influences');
            $table->foreignId('extracurricular_activities_id')->constrained('binary_options');
            $table->foreignId('internet_access_id')->constrained('binary_options');
            $table->foreignId('learning_disabilities_id')->constrained('binary_options');

            // La puntuación del examen; se almacena cuando el registro está completo
            $table->float('exam_score')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('performance_records');
    }
};
