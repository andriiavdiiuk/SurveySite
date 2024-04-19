<?php

class Survey
{
    public int $survey_id;
    private  int $category_id;

    public string $title;
    public string $survey_thanks_title;
    public string $video_url;
    public bool $access_only_by_url;

    /**
     * @param int $survey_id
     * @param string $title
     * @param string $survey_thanks_title
     */
    public function __construct(int $survey_id=-1,int $category_id=-1, string $title="", string $survey_thanks_title="", string $video_url="",bool $access_only_by_url=false)
    {
        $this->survey_id = $survey_id;
        $this->title = $title;
        $this->survey_thanks_title = $survey_thanks_title;
        $this->video_url = $video_url;
        $this->access_only_by_url = $access_only_by_url;
        $this->category_id = $category_id;
    }

    public function getSurveyId(): int
    {
        return $this->survey_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSurveyThanksTitle(): string
    {
        return $this->survey_thanks_title;
    }

    public function setSurveyThanksTitle(string $survey_thanks_title): void
    {
        $this->survey_thanks_title = $survey_thanks_title;
    }

    public function getVideoUrl(): string
    {
        return $this->video_url;
    }

    public function setVideoUrl(string $video_url): void
    {
        $this->video_url = $video_url;
    }

    public function isAccessOnlyByUrl(): bool
    {
        return $this->access_only_by_url;
    }

    public function setAccessOnlyByUrl(bool $access_only_by_url): void
    {
        $this->access_only_by_url = $access_only_by_url;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }
}
