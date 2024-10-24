<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/usage-data', function () {
    return view('usage-data');
})->name('usage-data');

Route::get('/results/costs', function () {
    return view('results.costs');
})->name('results.costs');

Route::get('/results/plans', function () {
    return view('results.plans');
})->name('results.plans');

Route::get('/reset-session', function () {
    Session::flush();
    return redirect()->route('welcome')->with('message', 'Your details have been forgotten.');
})->name('reset-session');
