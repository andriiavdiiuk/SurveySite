<?php
require_once $_SERVER['DOCUMENT_ROOT']."/src/core/DataMapper.php";
require_once $_SERVER['DOCUMENT_ROOT']."/src/models/User.php";
class UserMapper extends DataMapper

{
    public function create(object $obj) : ?int
    {
        $sql = "INSERT IGNORE INTO `users` (`email`) 
                VALUES ('{$obj->getEmail()}')";

        $this->execute($sql);

        $id = $this->database->getPDO()->lastInsertId();
        if (gettype($id) == "string") {
            return intval($id);
        }
        return null;

    }
    public function get(int $id) : ?User
    {
        $sql = "SELECT * 
                FROM `users` 
                WHERE `user_id` = '$id';";
        $data = $this->executeAndFetch($sql);
        if ($data)
        {
            return new User($data["user_id"],$data["email"],$data["password"],(bool)$data["is_admin"]);
        }
        return Null;
    }
    public function getByEmail(string $email) : ?User
    {
        $sql = "SELECT * 
                FROM `users` 
                WHERE `email` = '$email';";
        $data = $this->executeAndFetch($sql);
        if ($data)
        {
            return new User($data["user_id"],$data["email"],$data["password"],(bool)$data["is_admin"] );
        }
        return Null;
    }
    public function update($obj) : bool
    {
        $sql = "UPDATE `users` 
                SET `email`='{$obj->getEmail()}', `password`='{$obj->getPassword()}'                   
                WHERE `user_id` = '{$obj->getUserId()}';";
        return $this->execute($sql);
    }

    public function delete($obj) : bool
    {
        $sql = "DELETE FROM `users` WHERE `user_id` = '{$obj->getUserId()}';";
        return $this->execute($sql);
    }

    public function isExist(string $id) : bool
    {
        $sql = "SELECT user_id FROM `users` WHERE `user_id` = '$id'";
        $data= $this->executeAndFetch($sql);
        if ($data != NULL && $data['user_id'] > 0)
        {
            return true;
        }
        return false;
    }
    public function isExistByEmail(string $email) : bool
    {
        $sql = "SELECT email FROM `users` WHERE `email` = '$email'";
        $data= $this->executeAndFetch($sql);
        if ($data != NULL && $data['email'] > 0)
        {
            return true;
        }
        return false;
    }
}

?>