<?php

namespace App\Http\Middleware;

use App\Repositories\Collections;
use Closure;

class SetLocale
{
    public function __construct(Collections $collections)
    {
        $this->collections = $collections;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($req, Closure $next)
    {
        $collection = $this->collections->slugOrFail($req->route('collection_slug'));
        $default = $collection->languages()->where('is_default', 1)->first()->code ?? 'EN';
        $locale = $req->has('locale') ? $req->input('locale') : $default;

        if ($collection->languages()->count() > 1 && !$collection->languages()->pluck('code')->contains($locale)) {
            abort(401);
        }

        app()->setLocale($locale);

        return $next($req);
    }
}
