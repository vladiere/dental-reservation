<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailsModel extends Model
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

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
