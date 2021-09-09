<?php

$router->group(['middleware' => 'auth', 'prefix' => 'api'], function ($router) {
    $router->get('me', 'AuthController@me');


    //users routes
    $router->group(['prefix' => 'users'], function ($router) {
        $router->get('/', 'AuthController@index');
        $router->get('create', 'UsersController@create');
        $router->post('/', 'UsersController@store');
        $router->get('{user_id}', 'UsersController@show');
        $router->get('{user_id}/edit', 'UsersController@edit');
        $router->put('{user_id}', 'UsersController@update');
        $router->delete('{user_id}', 'UsersController@delete');
    });


});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');

});
