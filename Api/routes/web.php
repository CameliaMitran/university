<?php

$router->group(['middleware' => 'auth', 'prefix' => 'api'], function ($router) {
    $router->get('me', 'AuthController@me');


    //users routes
    $router->group(['prefix' => 'users'], function ($router) {
        $router->get('/', 'AuthController@index');
        $router->post('/', 'UserController@store');
        $router->get('{user_id}', 'UserController@edit');
        $router->get('{user_id}/edit', 'UserController@edit');
        $router->put('{user_id}', 'UserController@update');
        $router->delete('{user_id}', 'UserController@delete');
    });

    //students route
    $router->group(['prefix' => 'students'], function ($router) {
        $router->get('/', 'StudentsController@index');
        $router->get('create', 'StudentsController@create');
        $router->post('/', 'StudentsController@store');
        $router->get('{user_id}', 'StudentsController@edit');
        $router->get('{user_id}/edit', 'StudentsController@edit');
        $router->put('{user_id}', 'StudentsController@update');
        $router->delete('{user_id}', 'StudentsController@delete');
    });


});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');

});
