<?php

use App\Models\Appointment;
use App\Models\Details;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("patient_records", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Details::class)->cascadeOnDelete();
            $table
                ->foreignIdFor(Details::class, "doctor_id")
                ->cascadeOnDelete();
            $table->foreignIdFor(Appointment::class)->cascadeOnDelete();
            $table->foreignIdFor(Prescription::class)->cascadeOnDelete();
            $table->string("diagnosis");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("patient_records");
    }
};
