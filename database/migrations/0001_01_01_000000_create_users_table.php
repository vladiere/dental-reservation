<?php

use App\Models\Details;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Details::class)->cascadeOnDelete();
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            // 0 -- admin
            // 1 -- subadmin
            // 2 -- dentist
            // 3 -- patient
            // 4 -- receptionist
            // 5 -- guest
            $table->tinyInteger("user_role")->default(5);
            $table
                ->enum("user_status", [
                    "pending",
                    "registered",
                    "rejected",
                    "cancel",
                ])
                ->default("pending");
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create("password_reset_tokens", function (Blueprint $table) {
            $table->string("email")->primary();
            $table->string("token");
            $table->timestamp("created_at")->nullable();
        });

        Schema::create("sessions", function (Blueprint $table) {
            $table->string("id")->primary();
            $table->foreignId("user_id")->nullable()->index();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->longText("payload");
            $table->integer("last_activity")->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("users");
        Schema::dropIfExists("password_reset_tokens");
        Schema::dropIfExists("sessions");
    }
};
