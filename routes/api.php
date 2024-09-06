<?php

use App\Http\Controllers\AuthCoontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register',[AuthCoontroller::class,'register']);
Route::post('login',[AuthCoontroller::class,'login']);
Route::get('Getdata',[AuthCoontroller::class,'Getdata'])->middleware('auth:sanctum');