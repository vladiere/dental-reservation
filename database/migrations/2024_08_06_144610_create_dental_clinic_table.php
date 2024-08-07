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
        Schema::create("dental_clinic", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string("clinic_name");
            $table->string("clinic_address");
            $table->time("operate", precision: 0);
            $table->string("day");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("dental_clinic");
    }
};
