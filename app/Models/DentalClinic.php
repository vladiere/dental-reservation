<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DentalClinic extends Model
{
    use HasFactory;
    protected $table = "dental_clinic";
    protected $fillable = [
        "user_id",
        "clinic_name",
        "clinic_address",
        "map_link",
        "long",
        "lat",
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(App\Models\Schedule::class);
    }
}
