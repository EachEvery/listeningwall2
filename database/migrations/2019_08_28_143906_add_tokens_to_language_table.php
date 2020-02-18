<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTokensToLanguageTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->longText('tokens')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('tokens');
        });
    }
}
