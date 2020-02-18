<?php

use Podlove\Webvtt\Parser;
use GuzzleHttp\Client as  Guzzle;
use Podlove\Webvtt\ParserException;

class TranscriptGenerator
{
    public function __construct(Parser $parser, Guzzle $guzzle)
    {
        $this->parser = $parser;
        $this->guzzle = $guzzle;
    }

    public function isValid($url)
    {
        try {
            $this->getCues($url);
        } catch (ParserException $e) {
            return false;
        }

        return true;
    }

    public function generate($vttUrl)
    {
        return $this->getParesdCueChunks($vttUrl);
    }

    protected function getParesdCueChunks($url, $chunks = 6)
    {
        return $this->getCues($url)->chunk($chunks)->map(function ($chunk) {
            $seconds = $chunk->first()['start'];

            return (object) [
                'seconds' => $seconds,
                'time_for_humans' => gmdate('i:s', (int) $seconds),
                'text' => $chunk->pluck('text')->implode(' '),
            ];
        });
    }

    protected function getCues($url)
    {
        $cuesArray = $this->parser->parse(
            $this->getContentFromUrl($url)
        );

        return collect($cuesArray['cues']);
    }

    protected function getContentFromUrl($url)
    {
        return $this->guzzle->request('GET', $url)->getBody();
    }
}
