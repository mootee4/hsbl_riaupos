<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompetitionTypeToAddDataTable extends Migration
{
    public function up()
    {
        Schema::table('add_data', function (Blueprint $table) {
            $table->string('competition_type')->nullable()->after('competition'); 
            // taruh di setelah kolom 'competition', sesuaikan kalau kolom lain berbeda
        });
    }

    public function down()
    {
        Schema::table('add_data', function (Blueprint $table) {
            $table->dropColumn('competition_type');
        });
    }
}
