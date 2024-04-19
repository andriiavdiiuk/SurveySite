<?php

class OfferedAnswer
{
    public int $offered_answer_id;
    public int $question_id;
    public string $text;

    /**
     * @param int $offered_answer_id
     * @param int $question_id
     * @param string $text
     */
    public function __construct(int $offered_answer_id=-1, int $question_id=-1, string $text="")
    {
        $this->offered_answer_id = $offered_answer_id;
        $this->question_id = $question_id;
        $this->text = $text;
    }

    public function getOfferedAnswerId(): int
    {
        return $this->offered_answer_id;
    }

    public function getQuestionId(): int
    {
        return $this->question_id;
    }

    public function setQuestionId(int $question_id): void
    {
        $this->question_id = $question_id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

}