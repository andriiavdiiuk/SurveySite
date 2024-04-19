<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/core/DataMapper.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/models/Survey.php";

class SurveyMapper extends DataMapper

{
    public function create(object $obj): ?int
    {
        $isAccessOnlyByUrl = (int)$obj->isAccessOnlyByUrl();
        $sql = "INSERT IGNORE INTO `surveys` (`survey_title`,`category_id`,`survey_thanks_title`,`survey_video_url`,`access_only_by_url`) 
                VALUES ('{$obj->getTitle()}','{$obj->getCategoryId()}','{$obj->getSurveyThanksTitle()}','{$obj->getVideoUrl()}','{$isAccessOnlyByUrl}')";

        $this->execute($sql);

        $id = $this->database->getPDO()->lastInsertId();
        if (gettype($id) == "string") {
            return intval($id);
        }
        return null;

    }

    public function get(int $id): ?Survey
    {
        $sql = "SELECT * 
                FROM `surveys` 
                WHERE `survey_id` = '$id';";
        $data = $this->executeAndFetch($sql);
        if ($data) {
            return new Survey($data["survey_id"],$data["category_id"], $data["survey_title"], $data["survey_thanks_title"], $data["survey_video_url"],$data['access_only_by_url']);
        }
        return Null;
    }


    public function update($obj): bool
    {
        $is_only_by_url = (int)$obj->isAccessOnlyByUrl();
        $sql = "UPDATE `surveys` 
                SET `survey_title`='{$obj->getTitle()}', `survey_thanks_title`='{$obj->getSurveyThanksTitle()}',
                    `survey_video_url`='{$obj->getVideoUrl()}',`access_only_by_url`='$is_only_by_url',
                    `category_id`='{$obj->getCategoryId()}'
                WHERE `survey_id` = '{$obj->getSurveyId()}';";
        return $this->execute($sql);
    }

    public function delete($obj): bool
    {
        $sql = "DELETE FROM `surveys` WHERE `survey_id` = '{$obj->getSurveyId()}';";
        return $this->execute($sql);
    }

    public function getAll(): ?array
    {
        $sql = "SELECT * FROM `surveys`;";
        $data = $this->executeAndFetchAll($sql);
        if ($data) {
            $surveys = array();
            foreach ($data as $survey) {
                $surveys[] = new Survey($survey["survey_id"],$survey["category_id"], $survey["survey_title"], $survey["survey_thanks_title"], $survey["survey_video_url"],(bool)$survey['access_only_by_url']);
            }
            return $surveys;
        }
        return Null;
    }
    public function getByCategory($id): ?array
    {
        $sql = "SELECT * FROM `surveys`
                WHERE `category_id`='$id';";
        $data = $this->executeAndFetchAll($sql);
        if ($data) {
            $surveys = array();
            foreach ($data as $survey) {
                $surveys[] = new Survey($survey["survey_id"],$survey["category_id"], $survey["survey_title"], $survey["survey_thanks_title"], $survey["survey_video_url"],(bool)$survey['access_only_by_url']);
            }
            return $surveys;
        }
        return Null;
    }

}

