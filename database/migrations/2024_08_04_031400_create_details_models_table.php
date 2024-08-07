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
        Schema::create("details", function (Blueprint $table) {
            $table->id();
            $table->string("first_name");
            $table->string("middle_name");
            $table->string("last_name");
            $table->string("contact_no")->nullable();
            $table->enum("gender", ["male", "female", "other"])->nullable();
            $table->string("address")->nullable();
            $table->string("dental_clinic_name")->nullable();
            $table->string("dentist_signature")->nullable();
            $table->tinyInteger("acct_status")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("details");
    }
};
