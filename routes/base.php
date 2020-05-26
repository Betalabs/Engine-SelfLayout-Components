<?php

use Illuminate\Support\Facades\Route;

// Prefix /api
// Namespace \Betalabs\LaravelHelper\Http\Controllers
Route::post('/apps/genesis', 'AppController@register');
