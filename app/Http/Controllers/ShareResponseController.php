<?php

namespace App\Http\Controllers;

use App\Notifications\ShareResponseNotification;
use App\User;

class ShareResponseController extends Controller
{
    public function __invoke($response, $number)
    {
        (new User(['phone' => $number]))
            ->notify(new ShareResponseNotification($response));

        return response(201);
    }
}
