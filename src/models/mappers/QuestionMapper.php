<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core/DataMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/Question.php";

class QuestionMapper extends DataMapper

{
    public function create(object $obj): ?int
    {
        $is_plain_text = (int)$obj->isPlainText();
        $sql = "INSERT IGNORE INTO `questions` (`survey_id`,`question_text`,`is_plain_text`) 
                VALUES ('{$obj->getSurveyId()}','{$obj->getQuestionText()}','{$is_plain_text}')";

        $this->execute($sql);

        $id = $this->database->getPDO()->lastInsertId();
        if (gettype($id) == "string") {
            return intval($id);
        }
        return null;

    }

    public function get(int $id): ?Question
    {
        $sql = "SELECT * 
                FROM `questions` 
                WHERE `question_id` = '$id';";
        $data = $this->executeAndFetch($sql);
        if ($data) {
            return new Question($data["question_id"], $data["survey_id"], $data["question_text"], (bool)$data["is_plain_text"]);
        }
        return Null;
    }


    public function update($obj): bool
    {
        $is_plain_text = (int)$obj->isPlainText();
        $sql = "UPDATE `questions` 
                SET `survey_id`='{$obj->getSurveyId()}', `question_text`='{$obj->getQuestionText()}',
                    `is_plain_text`='{$is_plain_text}'
                WHERE `question_id` = '{$obj->getQuestionId()}';";
        return $this->execute($sql);
    }

    public function delete($obj): bool
    {
        $sql = "DELETE FROM `questions` WHERE `question_id` = '{$obj->getQuestionId()}';";
        return $this->execute($sql);
    }

    public function getBySurvey(int $id): ?array
    {
        $sql = "SELECT * 
                FROM `questions` 
                WHERE `survey_id` = '$id';";
        $data = $this->executeAndFetch($sql);
        $data = $this->executeAndFetchAll($sql);
        if ($data) {
            $surveys = array();
            foreach ($data as $data_elem) {
                $surveys[] = new Question($data_elem["question_id"], $data_elem["survey_id"], $data_elem["question_text"], (bool)$data_elem["is_plain_text"]);
            }
            return $surveys;
        }
        return Null;
    }
    public function isExist(string $id) : bool
    {
        $sql = "SELECT question_id FROM `questions` WHERE `question_id` = '$id'";
        $data= $this->executeAndFetch($sql);
        if ($data != NULL && $data['question_id'] > 0)
        {
            return true;
        }
        return false;
    }
}

