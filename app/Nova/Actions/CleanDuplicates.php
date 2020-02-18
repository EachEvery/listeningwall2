<?php

namespace App\Nova\Actions;

use App\Models\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\DestructiveAction;
use Laravel\Nova\Fields\ActionFields;

class CleanDuplicates extends DestructiveAction
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $ids = [];

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each(function ($collection) {
            $groups = $collection->responses->groupBy(function ($response) {
                return base64_encode(json_encode($response->words).'.'.$response->author);
            });

            $groups->each(function ($items, $i) {
                $items->each(function ($item, $i) {
                    if ($i > 0) {
                        $this->ids[] = $item->id;
                    }
                });
            });
        });

        Response::whereIn('id', $this->ids)->delete();

        $message = 0 === count($this->ids) ? 'No duplicate responses found.' : sprintf('%s duplicate responses were deleted.', count($this->ids));

        return Action::message($message);
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
