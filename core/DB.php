<?php

class Database {

    static $connection;

    static function get_connection() {
        
        if (!self::$connection) {
            require_once 'db.env.php';
            self::$connection = pg_connect("host={$db_host} port={$db_port} dbname={$db_name} user={$db_user} password={$db_password}");
        }

        return self::$connection;
    }
}