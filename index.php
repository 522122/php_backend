<?php
// error_reporting(0);
error_reporting(E_ALL);
ob_start();

require_once 'core/Session.php';
require_once 'core/Model.php';
require_once 'core/Router.php';
require_once 'routes/middlewares.php';
require_once 'routes/User.php';
require_once 'routes/Fuel.php';

Session::start();

function get_db_connection() {
    require_once 'db.env.php';
    return pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_password");
}

// Router::push('GET', 'q=admin-register', ['User::admin_register']);

Router::push('GET', 'q=userprofile', ['login_required', 'User::profile']);
Router::push('POST', 'q=login', ['User::login']);
Router::push('GET', 'q=logout', ['login_required', 'User::logout']);
Router::push('GET', 'q=fuel', ['login_required', 'Fuel::listing']);
Router::push('DELETE', 'q=fuel&id=?', ['login_required', 'Fuel::delete']);
Router::push('POST', 'q=fuel&id=?', ['login_required', 'Fuel::update']);
Router::push('POST', 'q=fuel', ['login_required', 'Fuel::insert']);

Router::push('GET', 'q=test', [function(&$request, &$response) {
    $response->middle = '<h1>Welcome dude!</h1>';
    return true;
}, function(&$request, &$response) {
    $response->html("{$response->middle}<h2>Best porn site here!</h2>");
}]);

Router::exec();
ob_end_flush();
