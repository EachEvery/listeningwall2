<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CollectionRowController
{
    public function update($collection, $rowNumber)
    {
        $table = DB::table('collection_source');

        $items = $table->whereIn('id', request('ids'))->get();

        $items->each(function ($item, $i) use ($table, $rowNumber) {
            $table->where('id', $item->id)->update([
                'row' => $rowNumber,
                'order' => $i + 1,
            ]);
        });

        return $collection->sources()->get();
    }
}
