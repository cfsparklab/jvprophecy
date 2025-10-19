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
        Schema::table('prophecies', function (Blueprint $table) {
            $table->longText('prayer_points')->nullable()->after('excerpt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prophecies', function (Blueprint $table) {
            $table->dropColumn('prayer_points');
        });
    }
};
