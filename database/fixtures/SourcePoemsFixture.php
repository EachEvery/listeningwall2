<?php

class SourcePoemsFixture
{
    public function get()
    {
        $string = file_get_contents(__DIR__.'/poems.json');

        return json_decode($string, false)->poems;
    }
}
