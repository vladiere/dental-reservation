<?php

use App\Models\DentalClinic;
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
        Schema::create("schedules", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->cascadeOnDelete();
            $table->foreignIdFor(DentalClinic::class)->cascadeOnDelete();
            $table->string("available_day")->nullable();
            $table->string("available_time")->nullable();
            $table->tinyInteger("doctor_status")->default(0);
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
