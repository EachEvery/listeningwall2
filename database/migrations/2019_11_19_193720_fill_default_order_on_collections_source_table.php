<?php

use Illuminate\Database\Migrations\Migration;

class FillDefaultOrderOnCollectionsSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $items = DB::table('collection_source')->get();

        $groups = $items->groupBy('collection_id')->values();

        $groups->each(function ($items) {
            $chunks = $items->chunk(ceil($items->count() / 4));

            $chunks->values()->each(function ($items, $i) {
                $row = $i + 1;

                $items->values()->each(function ($item, $j) use ($row) {
                    $order = $j + 1;

                    $sizes = ['small', 'medium', 'large'];

                    DB::table('collection_source')->where('id', $item->id)->update([
                        'row' => $row,
                        'order' => $order,
                        'size' => $sizes[array_rand($sizes)],
                    ]);
                });
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
