<?php

namespace App\Models;

use App\Contracts\RelatesToCollections;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait as CanBeSorted;
use Spatie\Translatable\HasTranslations;

class Source extends Model implements Sortable, RelatesToCollections
{
    use CanBeSorted;
    use HasTranslations;

    protected $guarded = ['id'];
    protected $with = ['category'];

    protected $translatable = [
        'title', 'text', 'transcript',
    ];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public function collections()
    {
        return $this->belongsToMany(Collection::class)
            ->withPivot(['id', 'size', 'order', 'row']);
    }

    public function addToCollection(Collection $collection)
    {
        $this->collections()->save($collection);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
