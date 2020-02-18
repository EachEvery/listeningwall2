<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use App\Models\Source as Model;
use App\Nova\Actions\AddToCollection;
use App\Nova\Actions\Enable;
use App\Nova\Actions\Disable;
use Digitalcloud\MultilingualNova\Multilingual;
use Laravel\Nova\Fields\Textarea;
use Naxon\NovaFieldSortable\Concerns\SortsIndexEntries;
use Naxon\NovaFieldSortable\Sortable;

class Source extends Resource
{
    use SortsIndexEntries;

    public static $defaultSortField = 'order';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Source';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Multilingual::make('Language')->hideFromIndex(),
            Text::make('Title'),
            Text::make('Video')->hideFromIndex(),
            Text::make('Thumbnail')->hideFromIndex(),
            Textarea::make('Text')->hideFromIndex(),
            Textarea::make('Transcript'),
            Text::make('Video Timestamps'),
            Boolean::make('Enabled'),
            BelongsTo::make('Category'),
            Sortable::make('Order', 'id')->onlyOnIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [new Enable(), new Disable(), new AddToCollection()];
    }
}
