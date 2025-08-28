<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchDataTable extends Migration
{
    public function up()
    {
        Schema::create('match_data', function (Blueprint $table) {
            $table->id();
            $table->date('upload_date');
            $table->string('main_title');
            $table->text('caption')->nullable();
            $table->string('layout_image')->nullable();
            $table->enum('status', ['draft', 'publish']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_data');
    }
}
