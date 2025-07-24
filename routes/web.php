<?php

use App\Jobs\ProcessPodcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    ProcessPodcast::dispatch()->delay(now()->addMinutes(1));
    ProcessPodcast::dispatch(2);
    return "test route";
});