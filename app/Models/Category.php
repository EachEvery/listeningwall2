<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    protected $guarded = ['id'];
    protected $translatable = [
        'name',
    ];

    public function sources()
    {
        return $this->hasMany(Source::class);
    }
}
