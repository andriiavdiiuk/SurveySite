<?php declare(strict_types=1);
require_once "config.php";
class Database
{

    private static Database $instance;
    private static bool $is_init = false;
    private PDO $PDO;

    function getPDO() : PDO
    {
        return $this->PDO;
    }
    private function __construct()
    {
        $this->connect();
    }
    public static function getInstance() : Database
    {
        if (!self::$is_init)
        {
            self::$instance = new self;
            self::$is_init = true;
        }
        return self::$instance;
    }
    private function connect() : void
    {
        try
        {
            $this->PDO = new PDO("mysql:host=".DB_HOST.";dbname=". DB_NAME, DB_USER, DB_PASSWORD);
        }
        catch (PDOException $e)
        {
            die("Connection failed: " . $e);
        }

    }

}
?>