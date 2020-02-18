<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CollectionSourceController
{
    public function update($collection)
    {
        $table = DB::table('collection_source');

        $table->where('collection_id', $collection->id)->delete();

        $table->insert(request('pivots'));

        return response('Success');
    }
}
