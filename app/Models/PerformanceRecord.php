<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'hours_studied',
        'attendance',
        'sleep_hours',
        'previous_scores',
        'tutoring_sessions',
        'physical_activity',
        'access_to_resources_id',
        'parental_involvement_id',
        'motivation_level_id',
        'family_income_id',
        'peer_influence_id',
        'extracurricular_activities_id',
        'internet_access_id',
        'learning_disabilities_id',
        'exam_score'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function accessToResources()
    {
        return $this->belongsTo(Level::class, 'access_to_resources_id');
    }

    public function parentalInvolvement()
    {
        return $this->belongsTo(Level::class, 'parental_involvement_id');
    }

    public function motivationLevel()
    {
        return $this->belongsTo(Level::class, 'motivation_level_id');
    }

    public function familyIncome()
    {
        return $this->belongsTo(Level::class, 'family_income_id');
    }

    public function peerInfluence()
    {
        return $this->belongsTo(PeerInfluence::class);
    }

    public function extracurricularActivities()
    {
        return $this->belongsTo(BinaryOption::class, 'extracurricular_activities_id');
    }

    public function internetAccess()
    {
        return $this->belongsTo(BinaryOption::class, 'internet_access_id');
    }

    public function learningDisabilities()
    {
        return $this->belongsTo(BinaryOption::class, 'learning_disabilities_id');
    }
}
