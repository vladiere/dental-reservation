<?php

use App\Models\DentalClinic;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("schedules", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DentalClinic::class)->cascadeOnDelete();
            $table->string("available_day")->nullable();
            $table->time("time_from", precision: 0)->nullable();
            $table->time("time_to", precision: 0)->nullable();
            // 0 - available
            // 1 - unavailable
            // 2 - busy
            // 3 - not around
            $table->tinyInteger("doctor_status")->default(0);
            // 0 - unavailable
            // 1 - available
            // 2 - maintenance
            // 3 - close
            // 4 - remove
            $table->tinyInteger("clinic_status")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("schedules");
    }
};
