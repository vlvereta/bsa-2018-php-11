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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/market/lots/add', function () {
    return view('add-form', ['success' => null]);
});

Route::post('/market/lots/add', function () {
    return view('add-form', ['success' => true, 'error' => null]);
})->name('store');
