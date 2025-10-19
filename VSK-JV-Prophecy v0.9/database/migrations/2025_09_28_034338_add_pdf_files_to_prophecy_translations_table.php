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
        Schema::table('prophecy_translations', function (Blueprint $table) {
            $table->string('pdf_file')->nullable()->after('prayer_points');
            $table->timestamp('pdf_uploaded_at')->nullable()->after('pdf_file');
            $table->string('pdf_original_name')->nullable()->after('pdf_uploaded_at');
            $table->integer('pdf_file_size')->nullable()->after('pdf_original_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prophecy_translations', function (Blueprint $table) {
            $table->dropColumn(['pdf_file', 'pdf_uploaded_at', 'pdf_original_name', 'pdf_file_size']);
        });
    }
};