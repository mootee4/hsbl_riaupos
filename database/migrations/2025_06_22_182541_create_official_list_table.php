<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficialListTable extends Migration
{
    public function up()
    {
        Schema::create('official_list', function (Blueprint $table) {
            $table->bigIncrements('official_id');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('nik')->nullable();
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('school')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('tshirt_size')->nullable();
            $table->string('shoes_size')->nullable();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('formal_photo')->nullable();
            $table->string('license_photo')->nullable();
            $table->string('identity_card')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('official_list');
    }
}
