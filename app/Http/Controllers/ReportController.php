<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\PerformanceRecord;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    // Muestra la lista de estudiantes con sus registros
    public function index()
    {
        $students = Student::with(['performanceRecords', 'gender', 'schoolType'])->get();
        return view('reports.index', compact('students'));
    }

    // Método para predicción individual: recibe student_id y record_id en el body, arma el payload y llama al endpoint FastAPI
    public function predictRecord(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'record_id'  => 'required|integer',
        ]);

        $student = Student::with(['gender', 'schoolType'])->findOrFail($request->student_id);
        $record = PerformanceRecord::with([
            'accessToResources',
            'parentalInvolvement',
            'motivationLevel',
            'familyIncome',
            'peerInfluence',
            'extracurricularActivities',
            'internetAccess',
            'learningDisabilities'
        ])->findOrFail($request->record_id);

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

        $apiUrl = env('API_BASE_URL') . '/performance/predictions';
        $response = Http::post($apiUrl, $payload);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Error al consumir el servicio de predicción'], $response->status());
        }
    }

    // Método para procesamiento en lote y generación del reporte PDF
    public function batchPredict(Request $request)
    {
        // Recibimos del formulario un arreglo con los IDs de registros seleccionados
        $selectedRecords = $request->input('record_ids');
        if (empty($selectedRecords)) {
            return redirect()->back()->with('error', 'No se seleccionaron registros para el lote.');
        }
        
        $studentsPayload = [];
        foreach ($selectedRecords as $recordId) {
            $record = PerformanceRecord::with([
                'accessToResources',
                'parentalInvolvement',
                'motivationLevel',
                'familyIncome',
                'peerInfluence',
                'extracurricularActivities',
                'internetAccess',
                'learningDisabilities',
                'student' => function($query){
                    $query->with(['gender', 'schoolType']);
                }
            ])->findOrFail($recordId);
            
            $student = $record->student;
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
            $studentsPayload[] = $payload;
        }
        
        $payloadBody = [
            "students" => $studentsPayload
        ];
        
        // Llamar al endpoint batch con los parámetros solicitados
        $apiUrl = env('API_BASE_URL') . '/performance/predictions/batch?detailed_results=true&include_categories=true';
        $response = Http::post($apiUrl, $payloadBody);
        
        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Error al consumir la API de predicciones en lote.');
        }
        
        $data = $response->json();
        
        // Generar PDF usando la librería instalada (por ejemplo, barryvdh/laravel-dompdf)
        $pdf = \PDF::loadView('reports.batch_report', compact('data'));
        return $pdf->download('batch_report.pdf');
    }

    public function viewDetails($studentId, $recordId)
    {
        // Recupera el estudiante con relaciones necesarias
        $student = Student::with(['gender', 'schoolType'])->findOrFail($studentId);

        // Recupera el registro con las relaciones de las tablas de referencia
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

        // Arma el payload para el endpoint de explicación
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

        // Llama al endpoint de explicación en FastAPI
        $apiUrl = env('API_BASE_URL') . '/performance/predictions/explain';
        $response = Http::post($apiUrl, $payload);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Error al obtener la explicación de la predicción.');
        }

        $explanationData = $response->json();

        return view('reports.view_details', compact('student', 'record', 'explanationData'));
    }
}
