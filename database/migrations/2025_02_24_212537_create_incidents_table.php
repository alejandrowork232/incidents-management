<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            // Esto crea una clave foránea a 'users(id)' para el campo 'user_creator'
            $table->foreignId('user_creator')->constrained('users')->onDelete('cascade')->name('fk_incidents_user_creator')->index();
            // Esto crea una clave foránea a 'users(id)' para el campo 'user_asigned'
            $table->foreignId('user_assigned')->nullable()->constrained('users')->onDelete('set null')->name('fk_incidents_user_assigned')->index();
            $table->string('title');
            $table->text('description');
            $table->enum('urgency', ['high', 'medium', 'low']);
            $table->enum('impact', ['high', 'medium', 'low']);
            $table->enum('category', ['hardware', 'software', 'network', 'security']);
            $table->enum('status', ['open', 'reopen', 'pending', 'in_progress', 'closed'])->default('open')->index();
            $table->timestamps();
        });

        Schema::create('incident_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade')->name('fk_incident_log_incident');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->name('fk_incident_log_user');
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->text('description')->nullable();
            $table->enum('event_type', ['status_change', 'comment', 'assignment', 'file_upload']);
            $table->timestamps();
        });

        Schema::create('incident_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade')->name('fk_incident_attachment_incident');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->name('fk_incident_attachment_user');
            $table->string('filename');
            $table->text('path');
            $table->string('mime_type');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_attachments');
        Schema::dropIfExists('incident_logs');
        Schema::dropIfExists('incidents');
    }
};
