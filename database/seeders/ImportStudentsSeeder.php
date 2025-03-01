<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportStudentsSeeder extends Seeder
{
    public function run()
    {
        $csvPath = database_path('StudentPerformanceFactors.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("No se encontró el archivo CSV en: {$csvPath}");
            return;
        }

        if (($handle = fopen($csvPath, 'r')) === false) {
            $this->command->error("No se pudo abrir el archivo CSV.");
            return;
        }

        // Leer la primera línea para obtener los encabezados
        $header = fgetcsv($handle, 1000, ',');
        $count = 0;

        while (($data = fgetcsv($handle, 1000, ',')) !== false && $count < 50) {
            $row = array_combine($header, $data);

            // Obtener IDs de las tablas de referencia
            $accessToResources     = DB::table('levels')->where('name', $row['Access_to_Resources'])->value('id');
            $parentalInvolvement   = DB::table('levels')->where('name', $row['Parental_Involvement'])->value('id');
            $motivationLevel       = DB::table('levels')->where('name', $row['Motivation_Level'])->value('id');
            $familyIncome          = DB::table('levels')->where('name', $row['Family_Income'])->value('id');

            $peerInfluence         = DB::table('peer_influences')->where('name', $row['Peer_Influence'])->value('id');

            $extracurricularActivities = DB::table('binary_options')->where('name', $row['Extracurricular_Activities'])->value('id');
            $internetAccess        = DB::table('binary_options')->where('name', $row['Internet_Access'])->value('id');
            $learningDisabilities  = DB::table('binary_options')->where('name', $row['Learning_Disabilities'])->value('id');

            $schoolType            = DB::table('school_types')->where('name', $row['School_Type'])->value('id');
            $gender                = DB::table('genders')->where('name', $row['Gender'])->value('id');

            // Insertar un nuevo estudiante (se asume cada fila como un estudiante único)
            $studentId = DB::table('students')->insertGetId([
                'gender_id'       => $gender,
                'school_type_id'  => $schoolType,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Insertar el registro de desempeño asociado
            DB::table('performance_records')->insert([
                'student_id'                   => $studentId,
                'hours_studied'                => $row['Hours_Studied'],
                'attendance'                   => $row['Attendance'],
                'sleep_hours'                  => $row['Sleep_Hours'],
                'previous_scores'              => $row['Previous_Scores'],
                'tutoring_sessions'            => $row['Tutoring_Sessions'],
                'physical_activity'            => $row['Physical_Activity'],
                'access_to_resources_id'       => $accessToResources,
                'parental_involvement_id'      => $parentalInvolvement,
                'motivation_level_id'          => $motivationLevel,
                'family_income_id'             => $familyIncome,
                'peer_influence_id'            => $peerInfluence,
                'extracurricular_activities_id'=> $extracurricularActivities,
                'internet_access_id'           => $internetAccess,
                'learning_disabilities_id'     => $learningDisabilities,
                'exam_score'                   => isset($row['Exam_Score']) ? $row['Exam_Score'] : null,
                'created_at'                   => now(),
                'updated_at'                   => now(),
            ]);

            $count++;
        }

        fclose($handle);

        $this->command->info("Se importaron {$count} registros desde el CSV.");
    }
}
