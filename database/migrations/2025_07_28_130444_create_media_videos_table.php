<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_videos', function (Blueprint $table) {
            $table->id();
            $table->string('video_code')->unique();     // Contoh: VID-001
            $table->string('title');
            $table->string('thumbnail');                // Path gambar atau URL
            $table->text('description')->nullable();
            $table->string('youtube_link');
            $table->string('slug')->unique();           // Untuk URL detail
            $table->enum('type', ['video', 'live']);    // Filter jenis video
            $table->enum('status', ['view', 'draft']);  // Tampilkan atau tidak
            $table->timestamps();                       // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_videos');
    }
};
