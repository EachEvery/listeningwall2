<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class IframeResponse extends Pivot implements Sortable
{
    use SortableTrait;

    /*
     * Determine the column name of the order column.
     */
    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    /*
     * Group cards per player, making it possible to restart ordering
     * from index 1 for each player.
     */
    public function buildSortQuery()
    {
        return static::query()->where('response_id', $this->response_id);
    }
}
