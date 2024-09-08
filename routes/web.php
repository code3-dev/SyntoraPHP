<?php

use SyntoraPHP\App\Route;

/*
|--------------------------------------------------------------------------
| SyntoraPHP Route
|--------------------------------------------------------------------------
|
| The Route class is responsible for defining routes and their associated
| handlers in the application. It provides methods for specifying various
| types of HTTP routes such as GET, POST, etc. and mapping them to
| corresponding controller actions or closures.
|
*/

Route::get('/', function () {
    view("index", [
        "title" => "SyntoraPHP"
    ]);
});

Route::get('/panel', 'PanelController@index');

// Auth Pages
Route::group('/auth', function () {
    Route::get('/login', 'AuthController@login');
    Route::get('/register', 'AuthController@register');
});


// API Routes
Route::group('/api/v1', function () {
    Route::post('/login', 'ApiController@login');
    Route::post('/register', 'ApiController@register');
    Route::get('/auth', 'ApiController@getUserData');
    Route::get('/logout', 'ApiController@logout');
    Route::post('/update-password', 'ApiController@updatePassword');
    Route::post('/update-name', 'ApiController@updateName');
    Route::post('/update-email', 'ApiController@updateEmail');
    Route::post('/delete-user', 'ApiController@deleteUser');
});
