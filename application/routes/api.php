<?php


Route::post('login', 'Api\LoginController@login');
Route::post('login/refresh', 'Api\LoginController@refresh');

// Route::post('register', 'Api\RegisterController@register');
Route::get('register/verify-user-exists/{email}', 'Api\RegisterController@verifyUserExists');

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', 'Api\LoginController@logout');
    Route::get('users/me', 'Api\UserController@me');
	Route::get('questrade-credentials/me', 'Api\Questrade\QuestradeCredentialController@me');

    Route::apiResources([
        'users' => 'Api\UserController',
        'questrade-credentials' => 'Api\Questrade\QuestradeCredentialController'
    ]);
});
