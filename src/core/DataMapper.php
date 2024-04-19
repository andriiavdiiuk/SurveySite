<?php declare(strict_types=1);
require_once "Database.php";
abstract class DataMapper
{
    protected Database $database;
    public function __construct()
    {
        $this->database = Database::getInstance();
    }
    abstract function create (object $obj) : ?int;
    abstract function get(int $id) : ?object;
    abstract function update(object $obj) : bool;
    abstract function delete(object $obj) : bool;

    protected function execute(string $sql) : bool
    {
        return $this->database->getPDO()->prepare($sql)->execute();
    }
    protected function executeAndFetch(string $sql) : mixed
    {
        $result= $this->database->getPDO()->prepare($sql);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
    protected function executeAndFetchAll(string $sql) : array|false
    {
        $result= $this->database->getPDO()->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}