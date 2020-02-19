<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Middleware\SetLocale;
use App\Repositories\Collections;

Route::get('/responses/{response}', 'ResponseController@show')->name('render');
Route::get('/responses/{response}/screenshot', 'ScreenshotController@show')->name('screenshot');

Route::get('/embed/{iframe}', 'IframeController@show')->name('embed');

Route::get('/', function () {
    $collection = resolve(Collections::class)->first();

    return redirect($collection->url);
});

Route::get('/csrf', 'CsrfTokenController');

Route::get('/collections', 'CollectionController@index');

Route::prefix('/collections/{collection_slug}')->middleware([SetLocale::class, 'web'])->group(function ($router) {
    $router->get('/', 'CollectionController@show')->name('collection');
    $router->get('/sources', 'SourceController@index');
    $router->get('/responses', 'ResponseController@index');
    $router->post('/responses', 'ResponseController@store');
});

Route::put('/collections/{collection}/sources', 'CollectionSourceController@update');

Route::post('/response/{response}/{number}', 'ShareResponseController');

Route::post('/upload-speed-stub', function () {
    return response('Done', 200);
});

Route::put('/responses/{response}', 'ResponseController@update')->middleware('auth');
