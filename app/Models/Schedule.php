<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "dental_clinic_id",
        "available_day",
        "time_from",
        "time_to",
        "doctor_status",
        "clinic_status",
    ];
}
