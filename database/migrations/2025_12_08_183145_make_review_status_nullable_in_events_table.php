<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make review_status nullable
        DB::statement("ALTER TABLE events ALTER COLUMN review_status DROP NOT NULL");
        DB::statement("ALTER TABLE events ALTER COLUMN review_status DROP DEFAULT");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: make review_status NOT NULL with default 'pending'
        DB::statement("ALTER TABLE events ALTER COLUMN review_status SET DEFAULT 'pending'");
        DB::statement("UPDATE events SET review_status = 'pending' WHERE review_status IS NULL");
        DB::statement("ALTER TABLE events ALTER COLUMN review_status SET NOT NULL");
    }
};
