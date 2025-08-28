<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events_data', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('term_cond')->nullable(); // ✅ Tambahan untuk path PDF
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events_data');
    }
};
