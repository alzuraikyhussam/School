<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'name',
    ];

    public function class()
    {
        return $this->belongsTo(Class::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}