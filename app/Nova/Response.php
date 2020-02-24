<?php

namespace App\Nova;

use Eachevery\Poemcard\Poemcard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use MichielKempen\NovaOrderField\Orderable;

class Response extends Resource
{
    use Orderable;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Response';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**g
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'author',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Title'),
            Text::make('Author'),
            Poemcard::make('Poem Card', 'poem_card_data')->exceptOnForms(),
            Number::make('Published Height')->onlyOnForms(),
            DateTime::make('Created At')->format('dddd, MMM Do, YYYY')->exceptOnForms(),
            BelongsToMany::make('Iframes'),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->when(empty($request->get('orderBy')), function (Builder $q) {
            $q->getQuery()->orders = [];

            return $q->orderBy('created_at', 'desc');
        });
    }

    /**
     * Get the cards available for the request.
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
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
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
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
