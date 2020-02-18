<?php

namespace App\Nova\Actions;

use App\Models\Collection as AppCollection;
use App\Repositories\Collections;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Nova\Fields\Select;
use App\Contracts\RelatesToCollections;

class AddToCollection extends Action
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection    $models
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $collection = resolve(Collections::class)->slugOrFail($fields->get('collection'));

        $models->each(function (RelatesToCollections $item) use ($collection) {
            $item->addToCollection($collection);
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $options = AppCollection::all()->mapWithKeys(function ($item) {
            return [
                $item->slug => $item->name,
            ];
        });

        return [
            Select::make('Collection')->options($options)->displayUsingLabels(),
        ];
    }
}
