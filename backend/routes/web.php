<?php

use Illuminate\Support\Facades\Route;


// on definit les routes ici

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return 'Hello, World!';
});
