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
            $table->integer('week_number')->nullable()->after('jebikalam_vanga_date')->comment('Continuous week number (global sequence)');
            $table->index('week_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prophecies', function (Blueprint $table) {
            $table->dropIndex(['week_number']);
            $table->dropColumn('week_number');
        });
    }
};
