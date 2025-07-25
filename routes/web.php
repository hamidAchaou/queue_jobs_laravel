<?php

use App\Jobs\ProcessPodcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    ProcessPodcast::dispatch(1)->onqueue('hight');
    ProcessPodcast::dispatch(2)->onqueue('low');
    return "test route";
});