<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $t) {
            $t->string('phone', 30)->nullable();
            $t->string('whatsapp', 30)->nullable();
            $t->string('city', 100)->nullable();
            $t->text('bio')->nullable();
            $t->json('interests')->nullable();
            $t->string('avatar_path')->nullable();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['phone','whatsapp','city','bio','interests','avatar_path']);
        });
    }
};
