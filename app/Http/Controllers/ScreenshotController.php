<?php

namespace App\Http\Controllers;

use ScreenshotMachine\ScreenshotMachine;

class ScreenshotController extends Controller
{
    public function __construct(ScreenshotMachine $ssm)
    {
        $this->ssm = $ssm;
    }

    public function show($response)
    {
        $url = str_replace('listeningwall2.test', 'a2737c10.ngrok.io', route('render', compact('response')));

        $url = $this->ssm->generate_screenshot_api_url([
            'url' => $url,
            'dimension' => '1300xfull',
            'device' => 'desktop',
            'format' => 'png',
            'delay' => '10',
        ]);

        header('Content-type: image/png');
        echo file_get_contents($url);
        exit;
    }
}
