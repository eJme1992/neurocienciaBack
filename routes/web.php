<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
