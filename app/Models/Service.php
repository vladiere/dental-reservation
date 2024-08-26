<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Service extends Model
{
    use HasFactory;

    protected $table = "services";

    protected $fillable = [
        "dental_clinic_id",
        "service_name",
        "service_price",
        "service_description",
    ];

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("m/d/Y") : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format("m/d/Y") : null;
    }
}
