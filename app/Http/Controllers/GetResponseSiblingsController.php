<?php

namespace App\Http\Controllers;

use App\Models\Response;

class GetResponseSiblingsController
{
    public function __invoke($response)
    {
        $all = Response::latest()->get();

        $index = $all->search(function ($r) use ($response) {
            return $r->is($response);
        });

        $next = $all->values()->get($index + 1);
        $previous = $all->values()->get($index - 1);

        if (0 === $index) {
            $previous = $all->last();
        }

        if ($index === $all->count() - 1) {
            $next = $all->values()->get(0);
        }

        return collect(compact('next', 'previous'));
    }
}
