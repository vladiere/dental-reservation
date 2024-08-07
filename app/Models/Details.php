<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Details extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "last_name",
        "contact_no",
        "gender",
        "address",
        "dental_clinic_name",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "id");
    }
}
