<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core/DataMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/UserAnswer.php";

class UserAnswerMapper extends DataMapper

{
    public function create(object $obj): ?int
    {
        if ($obj->getOfferedAnswerId() >= 0)
        {
            $sql = "INSERT IGNORE INTO `user_answers` (`question_id`,`user_id`,`offered_answer_id`) 
                VALUES ('{$obj->getQuestionId()}','{$obj->getUserId()}','{$obj->getOfferedAnswerId()}')";
        }
        else
        {
            $sql = "INSERT IGNORE INTO `user_answers` (`question_id`,`user_id`,`user_answer_text`) 
                VALUES ('{$obj->getQuestionId()}','{$obj->getUserId()}','{$obj->getUserAnswerText()}')";
        }

        $this->execute($sql);

        $id = $this->database->getPDO()->lastInsertId();
        if (gettype($id) == "string") {
            return intval($id);
        }
        return null;

    }

    public function get(int $id): ?UserAnswer
    {
        $sql = "SELECT * 
                FROM `user_answers` 
                WHERE `user_answer_id` = '$id';";
        $data = $this->executeAndFetch($sql);
        if ($data) {
            return new UserAnswer($data["user_answer_id"], $data["question_id"], $data["user_id"], $data["offered_answer_id"],$data["user_answer_text"]);
        }
        return Null;
    }


    public function update($obj): bool
    {
//        $sql = "UPDATE `questions`
//                SET `survey_id`='{$obj->getEmail()}', `question_text`='{$obj->getQuestionText()}',
//                    `is_plain_text`='{$obj->isPlainText()}'
//                WHERE `question_id` = '{$obj->getQuestionId()}';";
//        return $this->execute($sql);
    }

    public function delete($obj): bool
    {
//        $sql = "DELETE FROM `questions` WHERE `question_id` = '{$obj->getQuestionId()}';";
//        return $this->execute($sql);
    }
    public function getByQuestion(int $id): ?array
    {
        $sql = "SELECT * 
                FROM `user_answers` 
                WHERE `question_id` = '$id'
                ORDER BY user_answer_id DESC; ";
        $data = $this->executeAndFetchAll($sql);
        if ($data) {
            $surveys = array();
            foreach ($data as $data_elem) {
                $surveys[] = new UserAnswer($data_elem["user_answer_id"], $data_elem["question_id"], $data_elem["user_id"], $data_elem["offered_answer_id"],$data_elem["user_answer_text"]);
            }
            return $surveys;
        }
        return Null;
    }

}

