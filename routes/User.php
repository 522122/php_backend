<?php

class User {
    static function profile(&$request, &$response) {
        if (isset($request->user)) {
            $response->json($request->user);
        } else {
            return;
        }
    }

    static function login(&$request, &$response) {
        $model = new Model(get_db_connection(), 'users');
    
        $user = $model
            ->conditions(array(
                'username' => $_POST['username'],
                'hash' => md5($_POST['password'])
            ))
            ->limit(1)->select();
    
        if (count($user) > 0) {
            $_SESSION["user"] = $user[0];
        } else {
            $response->code = 404;
        }
    }

    static function logout(&$request, &$response) {
        Session::destroy();
    }

    static function admin_register(&$request, &$response) {
        $model = new Model(get_db_connection(), 'users');
    
        $user = $model
            ->model(array(
                "username" => "admin",
                "hash" => md5("admin")
            ))
            ->insert();
    
        if (count($user) > 0) {
            $response->html("User {$user[0]['username']} created.");
        } else {
            $response->html("Something went wrong.");
        }
    }
}





