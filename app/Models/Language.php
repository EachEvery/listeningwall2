<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $guarded = [];

    public function collections()
    {
        return $this->belongsToMany(Collection::class)->withPivot(['is_default']);
    }

    public function setTokensAttribute($val)
    {
        $this->attributes['tokens'] = json_encode($val);
    }
}
