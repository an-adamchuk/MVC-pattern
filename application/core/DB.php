<?php

namespace App\Core;

/**
 * Class Db to manage database connection
 * @package App\Core
 */
class Db
{
    private $_connection;
    private static $_instance; //The single instance
    private $_host = DB_HOST;
    private $_username = DB_USER;
    private $_password = DB_PASSWORD;
    private $_database = DB_DB;

    /**
     * Return instance of the Database.
     *
     * @return Db
     */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
        try {
            $this->_connection  = new \PDO("mysql:host=$this->_host;dbname=$this->_database", $this->_username, $this->_password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Magic method clone is empty to prevent duplication of connection.
     */
    private function __clone()
    {
    }

    /**
     * Magic method wakeup is empty to prevent duplication of connection.
     */
    private function __wakeup()
    {
    }

    /**
     * Get mysql pdo connection.
     *
     * @return mixed
     */
    public function getConnection()
    {
        return $this->_connection;
    }
}