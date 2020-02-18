<?php

namespace App\Repositories;

use App\Models\Collection;

class Collections
{
    public function slugOrFail($slug)
    {
        return Collection::whereSlug($slug)->firstOrFail();
    }

    public function first()
    {
        return Collection::oldest()->first();
    }

    public function all()
    {
        return Collection::all();
    }
}
