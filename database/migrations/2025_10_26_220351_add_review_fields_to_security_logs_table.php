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
        Schema::table('security_logs', function (Blueprint $table) {
            $table->boolean('is_reviewed')->default(false)->after('event_time');
            $table->foreignId('reviewed_by')->nullable()->after('is_reviewed')->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_logs', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['is_reviewed', 'reviewed_by', 'reviewed_at']);
        });
    }
};
