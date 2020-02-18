<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Collection extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $translatable = [
        'name', 'watermark_html', 'word_bank',
    ];

    protected $appends = [
        'hashtag', 'helper_words',
    ];

    public function getHelperWordsAttribute()
    {
        return collect(explode("\n", $this->word_bank ?? ''))->map(function ($str) {
            return trim($str);
        });
    }

    public function getHashtagAttribute()
    {
        return '#'.studly_case($this->name);
    }

    public function sources()
    {
        return $this->belongsToMany(Source::class)
            ->withPivot(['id', 'size', 'order', 'row']);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function getUrlAttribute()
    {
        return route('collection', [
            'collection_slug' => $this->slug,
        ]);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class)->withPivot(['is_default']);
    }
}
