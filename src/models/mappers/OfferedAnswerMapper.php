<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core/DataMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/OfferedAnswer.php";

class OfferedAnswerMapper extends DataMapper

{
    public function create(object $obj): ?int
    {
        $sql = "INSERT IGNORE INTO `offered_answers` (`question_id`,`offered_answer_text`) 
                VALUES ('{$obj->getQuestionId()}','{$obj->getText()}')";

        $this->execute($sql);

        $id = $this->database->getPDO()->lastInsertId();
        if (gettype($id) == "string") {
            return intval($id);
        }
        return null;

    }

    public function get(int $id): ?OfferedAnswer
    {
        $sql = "SELECT * 
                FROM `offered_answers` 
                WHERE `offered_answer_id` = '$id'
                ORDER BY 'offered_answer_id' DESC";
        $data = $this->executeAndFetch($sql);
        if ($data) {
            return new OfferedAnswer($data["offered_answer_id"], $data["question_id"], $data["offered_answer_text"]);
        }
        return Null;
    }


    public function update($obj): bool
    {
        $sql = "UPDATE `offered_answers` 
                SET `question_id`='{$obj->getQuestionId()}', `offered_answer_text`='{$obj->getText()}'
                WHERE `offered_answer_id` = '{$obj->getOfferedAnswerId()}';";
        return $this->execute($sql);
    }

    public function delete($obj): bool
    {
        $sql = "DELETE FROM `offered_answers` WHERE `offered_answer_id` = '{$obj->getOfferedAnswerId()}';";
        return $this->execute($sql);
    }
    public function getByQuestion(int $id): ?array
    {
        $sql = "SELECT * 
                FROM `offered_answers` 
                WHERE `question_id` = '$id';";
        $data = $this->executeAndFetchAll($sql);
        if ($data) {
            $surveys = array();
            foreach ($data as $data_elem) {
                $surveys[] = new OfferedAnswer($data_elem["offered_answer_id"], $data_elem["question_id"], $data_elem["offered_answer_text"]);
            }
            return $surveys;
        }
        return Null;
    }

    public function isExist(string $id) : bool
    {
        $sql = "SELECT offered_answer_id FROM `offered_answers` WHERE `offered_answer_id` = '$id'";
        $data= $this->executeAndFetch($sql);
        if ($data != NULL && $data['offered_answer_id'] > 0)
        {
            return true;
        }
        return false;
    }
}

