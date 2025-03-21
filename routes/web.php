<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Middleware\CheckRole;


Route::get('/', function () {
    return view('home');
})->name('home');
