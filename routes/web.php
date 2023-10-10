<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/orders', function () {
    DB::enableQueryLog();
    try {
        request()->validate([
            'products' =>  ['required', 'array'],
            'products.*.id' =>  ['required', Rule::exists('products', 'id')],
        ]);
        return DB::getQueryLog();
    } catch (Exception $e) {
        return $e->getMessage();
    }
});
