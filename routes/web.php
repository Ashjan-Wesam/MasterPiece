<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    Mail::raw("This is a test message", function ($message) {
        $message->to("Ashjanwesam@gmail.com")->subject("Test Email");
    });

    return "Test email sent!";
});
