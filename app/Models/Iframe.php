<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iframe extends Model
{
    protected $table = 'iframes';

    public function responses()
    {
        return $this->belongsToMany(Response::class)
            ->using(IframeResponse::class);
    }

    public function getEmbedCodeAttribute()
    {
        $url = route('embed', [
            'iframe' => $this->id,
        ]);

        return '<iframe src="'.$url.'" height="400" width="100%"></iframe>';
    }
}
