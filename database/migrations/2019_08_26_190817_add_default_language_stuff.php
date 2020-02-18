<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddDefaultLanguageStuff extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $setDefaultToEn = collect([
            'categories.name',
            'sources.title',
            'sources.text',
            'sources.transcript',
        ]);

        $setDefaultToEn->each(function ($item) {
            $arr = explode('.', $item);
            $table = $arr[0];
            $attr = $arr[1];

            DB::table($arr[0])->get()->each(function ($row) use ($table, $attr) {
                DB::table($table)->where('id', $row->id)->update([
                    $attr => sprintf('{"EN":"%s"}', $row->$attr),
                ]);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
