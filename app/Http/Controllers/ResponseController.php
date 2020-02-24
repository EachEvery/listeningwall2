<?php

namespace App\Http\Controllers;

use App\Repositories\AttachmentRepository as Attachments;
use App\Repositories\Collections;
use App\Repositories\ResponseRepository;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function __construct(
            ResponseRepository $responses,
            Collections $collections,
            Attachments $attachments
    ) {
        $this->responses = $responses;
        $this->collections = $collections;
        $this->attachments = $attachments;
    }

    public function index(Request $req)
    {
        $collection = $this->collections->slugOrFail(
            $req->route('collection_slug')
        );

        return $this->responses
            ->forCollection($collection)->toArray();
    }

    public function show($response)
    {
        return view('response', compact('response'));
    }

    public function update($response)
    {
        $response->update(request('response'));

        return $response->fresh()->poem_card_data;
    }

    public function store(Request $request)
    {
        $collection = $this->collections->slugOrFail(
            $request->route('collection_slug')
        );

        return $this->responses->create(
            $collection,
            $request->input('response')
        );
    }
}
