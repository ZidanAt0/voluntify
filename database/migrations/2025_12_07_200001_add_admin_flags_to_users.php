<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'organizer_verified_at')) {
                $table->timestamp('organizer_verified_at')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('organizer_verified_at');
            }
            if (!Schema::hasColumn('users', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('suspended_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'organizer_verified_at')) {
                $table->dropColumn('organizer_verified_at');
            }
            if (Schema::hasColumn('users', 'suspended_at')) {
                $table->dropColumn('suspended_at');
            }
            if (Schema::hasColumn('users', 'admin_notes')) {
                $table->dropColumn('admin_notes');
            }
        });
    }
};
