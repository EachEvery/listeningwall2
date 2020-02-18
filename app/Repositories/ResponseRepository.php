<?php

namespace App\Repositories;

use App\Models\Response;
use App\Notifications\CreateResponseNotification;
use Illuminate\Support\Arr;

class ResponseRepository
{
    public function __construct(AttachmentRepository $attachments)
    {
        $this->attachments = $attachments;
    }

    public function create($collection, $data)
    {
        $shouldSend = Arr::pull($data, 'should_send_to_author');

        return tap($collection->responses()->create($data), function ($response) use ($shouldSend) {
            if ('yes' === $shouldSend && !empty($response)) {
                $response->notify(new CreateResponseNotification());
            }
        });
    }

    public function forCollection($collection)
    {
        return $collection->responses->each(function ($r) {
            return $r->setAppends(['word_data']);
        });
    }

    public function all()
    {
        return Response::all();
    }
}
