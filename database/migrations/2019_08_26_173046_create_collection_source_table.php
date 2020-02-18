<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionSourceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('collection_source', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('source_id');
            $table->unsignedInteger('collection_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('collection_source');
    }
}
