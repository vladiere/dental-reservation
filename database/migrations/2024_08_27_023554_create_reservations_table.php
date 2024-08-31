<?php

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
        Schema::create("reservations", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Service::class);
            $table->dateTime("reservation_datetime", precision: 0);
            $table->enum("reserve_type", ["clustered", "solo"]);
            $table->tinyInteger("count");
            // 0    --- Pending
            // 1    --- Accept
            // 2    --- Denied/Reject
            // 3    --- Complete
            // 4    --- Error
            $table->tinyInteger("reservation_status")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("reservations");
    }
};
