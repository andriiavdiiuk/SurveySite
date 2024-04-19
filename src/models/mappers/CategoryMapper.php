<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core/DataMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/Category.php";

class CategoryMapper extends DataMapper

{
    public function create(object $obj): ?int
    {
        $sql = "INSERT IGNORE INTO `categories` (`category_text`,`category_whom_this_quiz`,`category_what_you_get`) 
                VALUES ('{$obj->getCategoryText()}','{$obj->getCategoryWhom()}','{$obj->getCategoryWhatYouGet()}')";

        $this->execute($sql);

        $id = $this->database->getPDO()->lastInsertId();
        if (gettype($id) == "string") {
            return intval($id);
        }
        return null;

    }

    public function get(int $id): ?Category
    {
        $sql = "SELECT * 
                FROM `categories` 
                WHERE `category_id` = '$id';";
        $data = $this->executeAndFetch($sql);
        if ($data) {
            return new Category($data["category_id"],$data["category_text"], $data["category_whom_this_quiz"], $data["category_what_you_get"]);
        }
        return Null;
    }


    public function update($obj): bool
    {
        $sql = "UPDATE `categories` 
                SET `category_text`='{$obj->getCategoryText()}', `category_whom_this_quiz`='{$obj->getCategoryWhom()}',
                    `category_what_you_get`='{$obj->getCategoryWhatYouGet()}'
                WHERE `category_id` = '{$obj->getCategoryId()}';";
        return $this->execute($sql);
    }

    public function delete($obj): bool
    {
        $sql = "DELETE FROM `categories` WHERE `category_id` = '{$obj->getCategoryId()}';";
        return $this->execute($sql);
    }

    public function getAll(): ?array
    {
        $sql = "SELECT * FROM `categories`;";
        $data = $this->executeAndFetchAll($sql);
        if ($data) {
            $surveys = array();
            foreach ($data as $survey) {
                $surveys[] = new Category($survey["category_id"],$survey["category_text"], $survey["category_whom_this_quiz"], $survey["category_what_you_get"]);
            }
            return $surveys;
        }
        return Null;
    }

}

