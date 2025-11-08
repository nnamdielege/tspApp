<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add suspension columns if they don't exist
            if (!Schema::hasColumn('users', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false)->after('email_verified_at')->index();
            }

            if (!Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('is_suspended');
            }

            if (!Schema::hasColumn('users', 'suspended_by')) {
                $table->unsignedBigInteger('suspended_by')->nullable()->after('suspended_at');
            }

            if (!Schema::hasColumn('users', 'suspension_reason')) {
                $table->text('suspension_reason')->nullable()->after('suspended_by');
            }

            if (!Schema::hasColumn('users', 'unsuspended_at')) {
                $table->timestamp('unsuspended_at')->nullable()->after('suspension_reason');
            }
        });

        // Add foreign key for suspended_by if not exists
        $this->addForeignKeyIfNotExists();
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'suspended_by')) {
                // Drop foreign key if exists
                try {
                    $table->dropForeign(['suspended_by']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
            }

            if (Schema::hasColumn('users', 'is_suspended')) {
                $table->dropColumn([
                    'is_suspended',
                    'suspended_at',
                    'suspended_by',
                    'suspension_reason',
                    'unsuspended_at'
                ]);
            }
        });
    }

    /**
     * Add foreign key constraint if it doesn't exist
     */
    private function addForeignKeyIfNotExists()
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('suspended_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        } catch (\Exception $e) {
            // Foreign key might already exist or another issue occurred
            Log::info('Foreign key not added (may already exist): ' . $e->getMessage());
        }
    }
};