<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("history_logs", function (Blueprint $table) {
            $table->id("log_id"); // Auto-incrementing primary key
            $table->integer("entity_id"); // ID of the entity performing the action
            $table->string("action_type", 50); // Action performed (CREATE, UPDATE, etc.)
            $table->string("actor_role", 50); // Role of the person performing the action (admin, subadmin, patient, dentist)
            $table
                ->timestamp("timestamp")
                ->default(DB::raw("CURRENT_TIMESTAMP")); // Timestamp of the action
            $table->integer("performed_by"); // Who performed the action (admin_id, user_id, etc.)
            $table->integer("affected_user_id")->nullable(); // ID of the user affected
            $table->string("action_scope", 100)->nullable(); // The module or section affected
            $table->string("status", 50)->nullable(); // Result of the action (SUCCESS, FAILED, etc.)
            $table->string("ip_address", 45)->nullable(); // IP address from which the action was performed
            $table->string("session_id", 100)->nullable(); // Session ID
            $table->string("device_info", 255)->nullable(); // Browser or device information
            $table->text("old_value")->nullable(); // Old value before update
            $table->text("new_value")->nullable(); // New value after update
            $table->text("reason")->nullable(); // Reason for action
            $table->string("user_agent", 255)->nullable(); // Browser or application details
            $table->integer("reference_id")->nullable(); // Reference ID for tracking related events
            $table->string("geo_location", 255)->nullable(); // Geolocation
            $table->json("changeset")->nullable(); // Detailed changes in JSON format
            $table->text("event_description")->nullable(); // Human-readable description of the action
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("history_logs");
    }
};
