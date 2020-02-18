<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionLanguageTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('collection_language', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('collection_id');
            $table->string('language_id');
            $table->boolean('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('collection_language');
    }
}
