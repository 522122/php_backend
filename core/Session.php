<?php

class Session {

    const EXPIRATION_TIME = 3600;

    static function start() {
        session_start();
        $now = time();
        $ip = $_SERVER['REMOTE_ADDR'];

        if (isset($_SESSION['exp']) && $_SESSION['exp'] < $now ) {
            Session::destroy();
        }

        if (isset($_SESSION['client']) && $_SESSION['client'] !== $ip) {
            Session::destroy();
        } else {
            $_SESSION['client'] = $ip;
        }

        $_SESSION['exp'] = $now + Session::EXPIRATION_TIME;

    }

    static function destroy() {
        session_unset();
        session_regenerate_id(true);
    }

}
