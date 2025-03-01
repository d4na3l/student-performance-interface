<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\PerformanceRecord;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    /**
     * Muestra el perfil del estudiante con sus registros.
     * Para cada registro sin exam_score, se carga automáticamente la predicción
     * usando el endpoint /performance/predictions/by-id/{student_id}.
     */
    public function show($id)
    {
        // Cargar el estudiante con sus registros y relaciones básicas
        $student = Student::with([
            'gender',
            'schoolType',
            'performanceRecords' => function($query) {
                $query->orderBy('id', 'asc');
            }
        ])->findOrFail($id);
        
        $predictions = [];
        
        // Para cada registro sin exam_score, se invoca el endpoint de predicción
        foreach ($student->performanceRecords as $record) {
            if (is_null($record->exam_score)) {
                // Cargar relaciones necesarias para armar el payload
                $record->load([
                    'accessToResources',
                    'parentalInvolvement',
                    'motivationLevel',
                    'familyIncome',
                    'peerInfluence',
                    'extracurricularActivities',
                    'internetAccess',
                    'learningDisabilities',
                    'student' // Relación definida en PerformanceRecord
                ]);
                
                $payload = [
                    "Hours_Studied"              => $record->hours_studied,
                    "Attendance"                 => $record->attendance,
                    "Sleep_Hours"                => $record->sleep_hours,
                    "Previous_Scores"            => $record->previous_scores,
                    "Tutoring_Sessions"          => $record->tutoring_sessions,
                    "Physical_Activity"          => $record->physical_activity,
                    "Access_to_Resources"        => $record->accessToResources->name,
                    "Parental_Involvement"       => $record->parentalInvolvement->name,
                    "Motivation_Level"           => $record->motivationLevel->name,
                    "Family_Income"              => $record->familyIncome->name,
                    "Peer_Influence"             => $record->peerInfluence->name,
                    "Extracurricular_Activities" => $record->extracurricularActivities->name,
                    "Internet_Access"            => $record->internetAccess->name,
                    "Learning_Disabilities"      => $record->learningDisabilities->name,
                    "School_Type"                => $record->student->schoolType->name,
                    "Gender"                     => $record->student->gender->name,
                ];
                
                // Llamada al endpoint de FastAPI para obtener la predicción por ID
                $apiUrl = env('API_BASE_URL') . '/performance/predictions/by-id/' . $student->id;
                $response = Http::post($apiUrl, $payload);
                
                if ($response->successful()) {
                    $predictions[$record->id] = $response->json();
                } else {
                    $predictions[$record->id] = ['error' => 'Error al obtener la predicción'];
                }
            }
        }
        
        return view('student.profile', compact('student', 'predictions'));
    }
    
    /**
     * Permite al estudiante actualizar los datos de un registro,
     * siempre que no se haya cargado el exam_score final.
     */
    public function updateRecord(Request $request, $recordId)
    {
        $record = PerformanceRecord::findOrFail($recordId);
        
        if (!is_null($record->exam_score)) {
            return redirect()->back()->with('error', 'No se puede actualizar el registro, ya se ha cargado el exam_score final.');
        }
        
        // Se permiten actualizar todos los campos del registro (excepto exam_score)
        $data = $request->validate([
            'hours_studied'      => 'required|numeric',
            'attendance'         => 'required|numeric',
            'sleep_hours'        => 'required|numeric',
            'previous_scores'    => 'required|numeric',
            'tutoring_sessions'  => 'required|numeric',
            'physical_activity'  => 'required|numeric',
            // Se podrían agregar validaciones para los campos categóricos, si se actualizan manualmente.
        ]);
        
        $record->update($data);
        
        return redirect()->back()->with('success', 'Registro actualizado correctamente.');
    }
    
    /**
     * Permite al estudiante agregar un nuevo registro.
     * Los nuevos registros se crearán copiando los datos del último registro del estudiante,
     * usando los mismos parámetros previamente cargados, excepto:
     * - "Hours_Studied" y "Attendance" que se ingresan nuevos valores.
     * - "Previous_Scores" se actualizará con el exam_score del registro anterior.
     */
    public function storeRecord(Request $request, $studentId)
    {
        // Validar los nuevos valores ingresados
        $data = $request->validate([
            'hours_studied' => 'required|numeric',
            'attendance'    => 'required|numeric',
            // Si se desean actualizar otros campos de forma manual, se agregan aquí.
        ]);
        
        $student = Student::findOrFail($studentId);
        
        // Obtener el último registro del estudiante
        $lastRecord = $student->performanceRecords()->orderBy('id', 'desc')->first();
        
        if (!$lastRecord) {
            return redirect()->back()->with('error', 'No existe registro previo para basar el nuevo record.');
        }
        
        // Copiar todos los datos del último registro
        $newRecordData = $lastRecord->toArray();
        unset($newRecordData['id'], $newRecordData['created_at'], $newRecordData['updated_at'], $newRecordData['exam_score']);
        
        // Actualizar los campos que deben cambiar:
        // "Hours_Studied" y "Attendance" vienen del formulario.
        $newRecordData['hours_studied'] = $data['hours_studied'];
        $newRecordData['attendance'] = $data['attendance'];
        
        // "Previous_Scores" se establece como el exam_score del último registro.
        $newRecordData['previous_scores'] = $lastRecord->exam_score;
        
        // Asegurar que el nuevo registro no tenga exam_score cargado.
        $newRecordData['exam_score'] = null;
        
        // Crear el nuevo registro
        $newRecord = PerformanceRecord::create(array_merge([
            'student_id' => $student->id
        ], $newRecordData));
        
        return redirect()->back()->with('success', 'Nuevo registro creado correctamente.');
    }

    /**
     * Obtiene recomendaciones llamando al endpoint
     * /performance/predictions/recommend para un registro específico.
     */
    public function recommend($studentId, $recordId)
    {
        // Cargar el estudiante con relaciones básicas
        $student = Student::with(['gender', 'schoolType'])->findOrFail($studentId);

        // Cargar el registro con las relaciones necesarias
        $record = PerformanceRecord::with([
            'accessToResources',
            'parentalInvolvement',
            'motivationLevel',
            'familyIncome',
            'peerInfluence',
            'extracurricularActivities',
            'internetAccess',
            'learningDisabilities'
        ])->findOrFail($recordId);

        // Armar el payload con los datos del registro y del estudiante
        $payload = [
            "Hours_Studied"              => $record->hours_studied,
            "Attendance"                 => $record->attendance,
            "Sleep_Hours"                => $record->sleep_hours,
            "Previous_Scores"            => $record->previous_scores,
            "Tutoring_Sessions"          => $record->tutoring_sessions,
            "Physical_Activity"          => $record->physical_activity,
            "Access_to_Resources"        => $record->accessToResources->name,
            "Parental_Involvement"       => $record->parentalInvolvement->name,
            "Motivation_Level"           => $record->motivationLevel->name,
            "Family_Income"              => $record->familyIncome->name,
            "Peer_Influence"             => $record->peerInfluence->name,
            "Extracurricular_Activities" => $record->extracurricularActivities->name,
            "Internet_Access"            => $record->internetAccess->name,
            "Learning_Disabilities"      => $record->learningDisabilities->name,
            "School_Type"                => $student->schoolType->name,
            "Gender"                     => $student->gender->name,
        ];

        // Llamar al endpoint de recomendaciones en FastAPI
        $apiUrl = env('API_BASE_URL') . '/performance/predictions/recommend';
        $response = Http::post($apiUrl, $payload);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Error al obtener las recomendaciones.');
        }

        $recommendations = $response->json();

        // Redirigir a la vista de recomendaciones
        return view('student.recommendations', compact('student', 'record', 'recommendations'));
    }
    
}
