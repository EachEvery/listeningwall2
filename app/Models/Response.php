<?php

namespace App\Models;

use App\Contracts\RelatesToCollections;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Traits\Tappable;

class Response extends Model implements RelatesToCollections
{
    use Notifiable;
    use Tappable;

    protected $guarded = [];

    protected $attributes = [
        'title' => 'Untitled',
        'photo' => '',
        'video' => '',
        'author' => 'Anonymous',
    ];

    public $appends = ['words', 'published_height', 'word_data'];

    public function iframes()
    {
        return $this->belongsToMany(Iframe::class);
    }

    public function getPublishedHeightAttribute($val)
    {
        return empty($val) ? 4000 : $val;
    }

    public function routeNotificationForTwilio()
    {
        return '+1'.strval(intval(preg_replace('/[^0-9]+/', '', $this->phone), 10));
    }

    public function getPublishedWidthAttribute()
    {
        return $this->published_height * 0.5625;
    }

    public function getWordsAttribute($val)
    {
        return json_decode($val);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function getWordDataAttribtute()
    {
        return $this->words_with_color;
    }

    public function addToCollection(Collection $collection)
    {
        $this->collection()->save($collection);
    }

    public function getTimesampForHumansAttribute()
    {
        return $this->created_at->format(
            $this->collection->time_format
        );
    }

    public function getLeftPercentage($word)
    {
        $originalLineWidth = $this->published_width * .43;

        return $word->left / $originalLineWidth * 100;
    }

    public function getImageUrlAttribute()
    {
        return route('screenshot', ['response' => $this]);
    }

    public function getPoemCardDataAttribute()
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'words' => $this->words_with_color,
            'imageUrl' => $this->image_url,
            'height' => $this->published_height,
            'id' => $this->id,
        ];
    }

    public function getWordsWithColor()
    {
        $words = collect($this->words);

        $sources = Source::findMany($words->pluck('sourceId'));

        return $words->map(function ($word) use ($sources) {
            if (!isset($word->sourceId)) {
                $color = '#000';
            } else {
                $source = $sources->find($word->sourceId);
                $color = $source->load('category')->category->color ?? '#000';
            }

            $left_percentage = $this->getLeftPercentage($word);

            return (object) array_merge(
                (array) $word,
                compact('color', 'left_percentage')
            );
        });
    }

    public function getWordsWithColorAttribute()
    {
        $key = sprintf('responses.%s.%s.wordsWithColor', $this->id, $this->updated_at);

        return Cache::rememberForever($key, function () {
            return $this->getWordsWithColor();
        });
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'photo' => $this->photo,
            'video' => $this->video,
            'words' => $this->words,
            'published_height' => $this->published_height,
        ];
    }
}
