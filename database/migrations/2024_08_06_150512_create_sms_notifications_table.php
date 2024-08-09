<?php

use App\Models\Appointment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("sms_notifications", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Appointment::class)->cascadeOnDelete();
            $table->string("sms_message")->nullable();
            $table->dateTime("sms_date_time", precision: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("sms_notifications");
    }
};
