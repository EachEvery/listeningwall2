<?php

namespace App\Http\Controllers;

class UpdateCollectionRowController
{
    public function __invoke($collection)
    {
        $sources = $collection->sources()->find(request('source_ids'));
    }
}
