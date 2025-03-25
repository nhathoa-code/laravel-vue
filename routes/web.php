<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin{any}', function () {
    return view('index');
})->where('any','.*');

Route::get('/{any}',function() {
    return view('public');
})->where('any','.*');;