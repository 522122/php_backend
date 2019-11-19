<?php
// error_reporting(0);
error_reporting(E_ALL);
ob_start();

require_once 'core/Session.php';
require_once 'core/Model.php';
require_once 'core/Router.php';
require_once 'core/DB.php';
require_once 'routes/middlewares.php';
require_once 'routes/User.php';
require_once 'routes/Fuel.php';
require_once 'routes/Maintenance.php';

Session::start();

// Router::push('GET', 'q=admin-register', ['User::admin_register']);

Router::push('GET', 'q=userprofile', ['login_required', 'User::profile']);
Router::push('POST', 'q=login', ['User::login']);
Router::push('GET', 'q=logout', ['login_required', 'User::logout']);
Router::push('GET', 'q=fuel', ['login_required', 'Fuel::listing']);
Router::push('DELETE', 'q=fuel&id=?', ['login_required', 'Fuel::delete']);
Router::push('POST', 'q=fuel&id=?', ['login_required', 'Fuel::update']);
Router::push('POST', 'q=fuel', ['login_required', 'Fuel::insert']);

Router::push('POST', 'q=maintenance', ['login_required', 'Maintenance::insert']);
Router::push('GET', 'q=maintenance', ['login_required', 'Maintenance::listing']);
Router::push('DELETE', 'q=maintenance&id=?', ['login_required', 'Maintenance::delete']);


Router::push('GET', 'q=test', [function(&$request, &$response) {
    $response->middle = '<h1>Welcome dude!</h1>';
    return true;
}, function(&$request, &$response) {
    $response->html("{$response->middle}<h2>Best porn site here!</h2>");
}]);

Router::exec();
ob_end_flush();
