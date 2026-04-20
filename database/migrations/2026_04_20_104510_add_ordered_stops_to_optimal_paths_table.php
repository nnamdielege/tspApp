<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('optimal_paths', function (Blueprint $table) {
            $table->json('ordered_stops')->nullable()->after('locations');
        });
    }

    public function down(): void
    {
        Schema::table('optimal_paths', function (Blueprint $table) {
            $table->dropColumn('ordered_stops');
        });
    }
};