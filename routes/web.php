<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//Public Routes For User Auth & Registration.
$router->group(['prefix' => 'v1/auth'], function ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
});

$router->group(['middleware' => 'auth', 'prefix' => 'v1/auth'], function ($router) {
    $router->post('refresh', 'AuthController@refresh');
});

// User Self Profile
$router->group(['middleware' => 'auth', 'prefix' => 'v1/users'], function ($router) {
    $router->get('profile', 'UserController@Profile');
});

//Users API
$router->group(['middleware' => 'auth', 'prefix' => 'v1'], function ($router) {
    $router->get('users', ['uses' => 'UserController@getAllUsers']);
    $router->get('users/{id}', ['uses' => 'UserController@getUser']);
    $router->patch('users/{id}', ['uses' => 'UserController@updateUser']);
    $router->post('users', ['uses' => 'AuthController@register']);
    $router->delete('users/{id}', ['uses' => 'UserController@deleteUser']);
});

//Employees API
$router->group(['middleware' => 'auth', 'prefix' => 'v1'], function ($router) {
    $router->get('employees', ['uses' => 'EmployeesController@index']);
    $router->get('employees/{id}', ['uses' => 'EmployeesController@getOne']);
    $router->patch('employees/{id}', ['uses' => 'EmployeesController@update']);
    $router->post('employees', ['uses' => 'EmployeesController@create']);
    $router->delete('employees/{id}', ['uses' => 'EmployeesController@destroy']);
});