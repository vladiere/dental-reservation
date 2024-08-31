<?php

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
        Schema::create("dentist_schedules", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->date("sched_date");
            $table->string("sched_days");
            $table->time("time_to", precision: 0);
            $table->time("time_from", precision: 0);
            // 0 -- available
            // 1 -- unavailable
            // 2 -- out_of_office
            // 3 -- busy
            $table->tinyInteger("sched_status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("dentist_schedules");
    }
};
