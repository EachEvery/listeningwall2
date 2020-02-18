<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPivotFieldsToCollectionSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_source', function (Blueprint $table) {
            $table->string('size')->nullable();
            $table->unsignedInteger('row')->nullable();
            $table->unsignedInteger('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_source', function (Blueprint $table) {
            $table->dropColumn(['size', 'row', 'order']);
        });
    }
}
