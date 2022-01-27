<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of api the routes for an application.
| This routes are available under the /api group
|
*/

$router->get('/version', function () use ($router) {
    return $router->app->version();
});

//Route to get all the users
$router->get('/users', 'UserController@all');

//Route to get a user by id
$router->get('/user', 'UserController@find');

//Route to get a user bills
$router->get('/user/bills', 'UserController@bills');

//Route to create a user
$router->post('/user', 'UserController@create');

//Route to get all the devices
$router->get('/devices', 'DeviceController@all');

//Route to get a device by id
$router->get('/device', 'DeviceController@find');

//Route to create a device
$router->post('/device', 'DeviceController@create');

//Route to get device logs
$router->get('/device/logs', 'DeviceController@logs');

//Route to get all the paths
$router->get('/paths', 'PathController@all');

//Route to get a path by id
$router->get('/path', 'PathController@find');

//Route to create a path
$router->post('/path', 'PathController@create');

//Route to get all the stations
$router->get('/stations', 'StationController@all');

//Route to get a station by id
$router->get('/station', 'StationController@find');

//Route to create a station
$router->post('/station', 'StationController@create');

//Route to enter a station
$router->post('/station/enter', 'StationController@enter');

//Route to exit a station
$router->post('/station/exit', 'StationController@exit');
