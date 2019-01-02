<?php

use Illuminate\Http\Request;

Route::post('login', 'Api\LoginController@login');
Route::post('login/refresh', 'Api\LoginController@refresh');

// Route::post('register', 'Api\RegisterController@register');
Route::get('register/verify-user-exists/{email}', 'Api\RegisterController@verifyUserExists');

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', 'Api\LoginController@logout');
    Route::get('user', 'Api\UserController@currentUser');

    Route::apiResources([
        'users' => 'Api\UserController',
    ]);
});
