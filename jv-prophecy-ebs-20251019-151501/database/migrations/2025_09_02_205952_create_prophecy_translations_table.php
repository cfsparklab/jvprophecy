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
        Schema::create('prophecy_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prophecy_id')->constrained()->onDelete('cascade');
            $table->string('language', 5); // en, ta, kn, te, ml, hi
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable(); // Full prophecy content
            $table->text('excerpt')->nullable();
            $table->json('metadata')->nullable(); // Additional language-specific metadata
            $table->timestamps();
            
            // Unique constraint to prevent duplicate translations
            $table->unique(['prophecy_id', 'language']);
            
            // Indexes for better performance
            $table->index('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prophecy_translations');
    }
};
