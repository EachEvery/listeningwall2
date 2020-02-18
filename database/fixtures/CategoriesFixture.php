<?php

class CategoriesFixture
{
    public function get()
    {
        $string = file_get_contents(__DIR__.'/tags.json');

        return collect(json_decode($string, false)->tags)->map(function ($item) {
            $item->color_light = $this->lighten_color($item->color, .3);

            return $item;
        })->toArray();
    }

    public function lighten_color($hex, $a)
    {
        list($r, $g, $b) = sscanf($hex, '#%02x%02x%02x');

        $r = $r * $a + 255 * (1 - $a);
        $g = $g * $a + 255 * (1 - $a);
        $b = $b * $a + 255 * (1 - $a);

        return sprintf('rgb(%s,%s,%s)', intval($r), intval($g), intval($b));
    }
}
