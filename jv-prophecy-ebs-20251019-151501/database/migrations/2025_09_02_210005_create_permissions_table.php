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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // create_prophecy, edit_prophecy, delete_prophecy, etc.
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->string('module'); // prophecy, category, user, etc.
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Index for better performance
            $table->index('module');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
