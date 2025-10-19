<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_type'); // view, download, print, access_attempt, etc.
            $table->string('resource_type')->nullable(); // prophecy, category, etc.
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Additional event data
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->timestamp('event_time')->useCurrent();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('event_type');
            $table->index('resource_type');
            $table->index('ip_address');
            $table->index('event_time');
            $table->index('severity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
