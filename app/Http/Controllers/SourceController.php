<?php

namespace App\Http\Controllers;

use App\Http\Resources\Source;
use App\Repositories\Collections;
use App\Repositories\SourceRepository as Sources;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function __construct(Sources $sources, Collections $collections)
    {
        $this->sources = $sources;
        $this->collections = $collections;
    }

    public function index(Request $req)
    {
        $collection = $this->collections->slugOrFail(
            $req->route('collection_slug')
        );

        return Source::collection(
            $this->sources->forCollection($collection)
        );
    }
}
