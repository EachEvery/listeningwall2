<?php

namespace App\Contracts;

use App\Models\Collection;

interface RelatesToCollections
{
    public function addToCollection(Collection $collection);
}
