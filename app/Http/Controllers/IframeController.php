<?php

namespace App\Http\Controllers;

class IframeController
{
    public function show($iframe)
    {
        return view('embed', [
            'responses' => $iframe->responses()->get(),
            'iframe' => $iframe,
        ]);
    }
}
