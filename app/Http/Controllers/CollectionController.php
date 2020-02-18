<?php

namespace App\Http\Controllers;

use App\Repositories\Collections;
use Illuminate\Http\Request;

class CollectionController
{
    public function __construct(Collections $collections)
    {
        $this->collections = $collections;
    }

    public function index()
    {
        return $this->collections->all();
    }

    public function show(Request $req)
    {
        $isProduction = 'production' === config('app.env');
        $defaultSize = $isProduction ? '32' : '8';
        $defaultCursor = $isProduction ? ((bool) request()->cursor) : true;
        $collection = $this->collections->slugOrFail(
            $req->route('collection_slug')
        );

        $collection->load('languages');

        return view('app', [
            'locale' => app()->getLocale(),
            'size' => request()->size ?? $defaultSize,
            'hide_cursor' => !$defaultCursor,
            'collection' => collect(array_merge($collection->toArray(), [
                'name' => $collection->name,
                'watermark_html' => $collection->watermark_html,
            ])),
        ]);
    }
}
