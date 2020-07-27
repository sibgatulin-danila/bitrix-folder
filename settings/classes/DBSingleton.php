<?php

/**
 * DB Class
 *
 */
class DBSingleton {

    private static $_instance = null;

    private function __construct($dbHost, $dbName, $dbUser, $dbPass) {

        self::$_instance = new PDO(
                'mysql:host=' . $dbHost . ';dbname=' . $dbName,
                $dbUser,
                $dbPass,
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
        );
    }

    private function __clone() {

    }

    private function __wakeup() {

    }

    public static function getInstance($dbHost, $dbName, $dbUser, $dbPass) {
        if (self::$_instance != null) {
            return self::$_instance;
        }
        new self($dbHost, $dbName, $dbUser, $dbPass);
        return self::$_instance;
    }

}

