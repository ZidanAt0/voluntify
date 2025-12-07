<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'review_status')) {
                $table->enum('review_status', ['pending', 'approved', 'rejected'])->default('approved')->after('status');
            }
            if (!Schema::hasColumn('events', 'reviewed_by_id')) {
                $table->foreignId('reviewed_by_id')->nullable()->after('review_status')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('events', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('reviewed_by_id');
            }
            if (!Schema::hasColumn('events', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'review_status')) {
                $table->dropColumn('review_status');
            }
            if (Schema::hasColumn('events', 'reviewed_by_id')) {
                $table->dropConstrainedForeignId('reviewed_by_id');
            }
            if (Schema::hasColumn('events', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('events', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
        });
    }
};
