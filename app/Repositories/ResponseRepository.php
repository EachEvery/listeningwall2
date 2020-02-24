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

        $response = $collection->responses()->create($data);

        if ('yes' === $shouldSend && !empty($response)) {
            $response->notify(new CreateResponseNotification());
        }

        return $response->poem_card_data;
    }

    public function forCollection($collection)
    {
        return $collection->responses->map(function ($r) {
            return $r->poem_card_data;
        });
    }

    public function all()
    {
        return Response::all();
    }
}
