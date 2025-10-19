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
        Schema::create('prophecies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('jebikalam_vanga_date'); // The specific date for Jebikalam Vaanga
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'restricted'])->default('public');
            $table->json('tags')->nullable(); // Store tags as JSON array
            $table->string('featured_image')->nullable();
            $table->text('excerpt')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->integer('print_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('jebikalam_vanga_date');
            $table->index('status');
            $table->index('visibility');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prophecies');
    }
};
