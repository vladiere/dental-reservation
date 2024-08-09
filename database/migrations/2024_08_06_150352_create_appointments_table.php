<?php

use App\Models\Guest;
use App\Models\Schedule;
use App\Models\Service;
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
        Schema::create("appointments", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->cascadeOnDelete();
            $table->foreignIdFor(Guest::class)->cascadeOnDelete();
            $table->foreignIdFor(Schedule::class)->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->cascadeOnDelete();
            $table->string("patient_type");
            $table->dateTime("appointment_date", precision: 0);
            $table->string("appointment_type");
            $table->tinyInteger("appointment_status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("appointments");
    }
};
