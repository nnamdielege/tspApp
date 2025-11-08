<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('optimal_paths', function (Blueprint $table) {
            // If employee_id column doesn't exist
            if (!Schema::hasColumn('optimal_paths', 'employee_id')) {
                $table->foreignId('employee_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('users')
                    ->onDelete('cascade');
            }

            // Add status column to track if route was started
            if (!Schema::hasColumn('optimal_paths', 'status')) {
                $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])
                    ->default('planned')
                    ->after('optimize_type');
            }

            // Add start and end times
            if (!Schema::hasColumn('optimal_paths', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('status');
                $table->timestamp('completed_at')->nullable()->after('started_at');
            }

            // Add notes
            if (!Schema::hasColumn('optimal_paths', 'route_notes')) {
                $table->text('route_notes')->nullable()->after('completed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('optimal_paths', function (Blueprint $table) {
            if (Schema::hasColumn('optimal_paths', 'employee_id')) {
                $table->dropForeignKeyIfExists(['employee_id']);
                $table->dropColumn('employee_id');
            }
            if (Schema::hasColumn('optimal_paths', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('optimal_paths', 'started_at')) {
                $table->dropColumn(['started_at', 'completed_at', 'route_notes']);
            }
        });
    }
};