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
        Schema::create("services", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(DentalClinic::class);
            $table->string("service_name");
            $table->decimal("service_price", total: 10, places: 2);
            $table->string("service_description");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("services");
    }
};
