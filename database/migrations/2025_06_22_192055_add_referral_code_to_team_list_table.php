<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferralCodeToTeamListTable extends Migration
{
    public function up()
    {
        Schema::table('team_list', function (Blueprint $table) {
            $table->string('referral_code')->unique()->after('school_name'); // Menambahkan kolom referral_code
        });
    }

    public function down()
    {
        Schema::table('team_list', function (Blueprint $table) {
            $table->dropColumn('referral_code'); // Menghapus kolom jika rollback
        });
    }
}

