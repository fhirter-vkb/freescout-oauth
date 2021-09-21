<?php

Route::group(['middleware' => 'web',
    'prefix' => \Helper::getSubdirectory(),
    'namespace' => 'Modules\OAuth\Http\Controllers'], function()
{
    Route::get('/oauth_callback', 'OAuthController@index')->name('oauth_callback');
});
