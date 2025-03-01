<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['gender_id', 'school_type_id'];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function schoolType()
    {
        return $this->belongsTo(SchoolType::class);
    }

    public function performanceRecords()
    {
        return $this->hasMany(PerformanceRecord::class);
    }
}
