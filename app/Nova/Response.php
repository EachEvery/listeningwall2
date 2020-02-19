<?php

namespace App\Nova;

use Eachevery\Poemcard\Poemcard;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use MichielKempen\NovaOrderField\Orderable;
use MichielKempen\NovaOrderField\OrderField;

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
        $fields = [
            Text::make('Title'),
            Text::make('Author'),
            Poemcard::make('Poem Card', 'poem_card_data')->exceptOnForms(),
            Number::make('Published Height')->onlyOnForms(),
            BelongsToMany::make('Iframes'),
        ];

        // if ('iframes' === $request->viaResource && 'responses' === $request->viaRelationship) {
        //     array_unshift($fields, OrderField::make('Order', 'iframe_response.order'));
        // }

        return $fields;
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
