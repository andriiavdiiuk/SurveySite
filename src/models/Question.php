<?php

class Question
{
    public int $question_id;
    public int $survey_id;
    public string $question_text;
    public bool $is_plain_text;

    /**
     * @param int $question_id
     * @param int $survey_id
     * @param string $question_text
     * @param bool $is_plain_text
     */
    public function __construct(int $question_id=-1, int $survey_id=-1, string $question_text="", bool $is_plain_text=true)
    {
        $this->question_id = $question_id;
        $this->survey_id = $survey_id;
        $this->question_text = $question_text;
        $this->is_plain_text = $is_plain_text;
    }

    public function getQuestionId(): int
    {
        return $this->question_id;
    }

    public function getSurveyId(): int
    {
        return $this->survey_id;
    }

    public function setSurveyId(int $survey_id): void
    {
        $this->survey_id = $survey_id;
    }

    public function getQuestionText(): string
    {
        return $this->question_text;
    }

    public function setQuestionText(string $question_text): void
    {
        $this->question_text = $question_text;
    }

    public function isPlainText(): bool
    {
        return $this->is_plain_text;
    }

    public function setIsPlainText(bool $is_plain_text): void
    {
        $this->is_plain_text = $is_plain_text;
    }
}
