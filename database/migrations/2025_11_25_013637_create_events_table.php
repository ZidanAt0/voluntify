<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // Konten inti
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();

            // Lokasi & waktu
            $table->string('location_type', 10)->default('onsite'); // onsite|online|hybrid
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');

            // Publikasi & kapasitas
            $table->unsignedInteger('capacity')->nullable(); // null = tanpa batas
            $table->unsignedInteger('registration_count')->default(0);
            $table->string('status', 20)->default('draft'); // draft|published|closed|cancelled
            $table->timestamp('published_at')->nullable();

            // Media
            $table->string('banner_path')->nullable();

            $table->timestamps();

            // Index penting
            $table->index(['status', 'starts_at']);
            $table->index('city');
            $table->index('category_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
