<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // applied | approved | waitlisted | rejected | cancelled | checked_in | completed
            $table->string('status', 20)->default('applied');

            $table->json('answers')->nullable();      // form singkat (opsional next)
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('cancelled_at')->nullable();

            // siapkan untuk check-in (next step)
            $table->string('checkin_code', 64)->nullable()->unique();

            $table->timestamps();

            $table->unique(['event_id','user_id']);   // 1 user 1 pendaftaran per event
            $table->index(['user_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('registrations');
    }
};
