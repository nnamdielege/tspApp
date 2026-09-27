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
        Schema::table('driver_locations', function (Blueprint $table) {
            $table->unique('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_locations', function (Blueprint $table) {
            // MySQL/InnoDB will have re-pointed the driver_id foreign key at the
            // unique index added in up() (since it can also satisfy the FK's
            // index requirement). Dropping the unique index directly fails with
            // "Cannot drop index ... needed in a foreign key constraint", so the
            // FK must be dropped first, then re-added afterwards to restore the
            // original (non-unique, auto-created) supporting index - matching
            // the constraint originally created in
            // 2026_05_05_110624_create_driver_locations_table's up().
            $table->dropForeign(['driver_id']);
            $table->dropUnique(['driver_id']);
            $table->foreign('driver_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};