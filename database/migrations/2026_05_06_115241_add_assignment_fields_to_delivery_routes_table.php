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
        Schema::table('delivery_routes', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_routes', 'status')) {
                $table->string('status')->default('unassigned');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_routes', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_routes', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
