<?php

abstract class FactoryBase
{
    protected function dbConnect()
    {
        require realpath(__DIR__."/../../../config/variables.php");
        $db = new \PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        return $db;
    }
}
