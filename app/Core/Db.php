<?php

namespace app\Core;

use PDO;

class Db
{
    const HOST = 'localhost';
    const DBNAME = 'users';
    const LOGIN = 'root';
    const PASSWORD = '';
    private $db;

    /** @var  $instance self */
    private static $instance;


    public function __construct()
    {
        $this->db = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DBNAME, self::LOGIN, self::PASSWORD);
    }

    public static function instance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance->db;
    }
}