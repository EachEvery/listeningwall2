<?php

namespace App\Repositories;

use App\Models\Source;

class SourceRepository
{
    public function all()
    {
        return Source::all();
    }

    public function forCollection($collection)
    {
        return $collection->sources()->ordered()->where('enabled', 1)->with('category')->get();
    }
}
