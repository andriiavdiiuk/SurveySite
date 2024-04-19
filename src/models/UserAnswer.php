<?php
class UserAnswer
{
    public int $user_answer_id;
    public int $question_id;
    public int $user_id;
    public int $offered_answer_id;
    public string $user_answer_text;

    /**
     * @param int $user_answer_id
     * @param int $question_id
     * @param int $user_id
     * @param int $offered_answer_id
     * @param string $user_answer_text
     */
    public function __construct(int $user_answer_id=-1, int $question_id=-1, int $user_id=-1, int|null $offered_answer_id=-1, string|null $user_answer_text='')
    {
        $this->user_answer_id = $user_answer_id;
        $this->question_id = $question_id;
        $this->user_id = $user_id;
        if ($offered_answer_id == null)
        {
            $this->offered_answer_id = -1;
        }
        else
        {
            $this->offered_answer_id = $offered_answer_id;
        }

        if ($user_answer_text == null)
        {
            $this->user_answer_text = '';
        }
        else {
            $this->user_answer_text = $user_answer_text;
        }
    }

    public function getUserAnswerId(): int
    {
        return $this->user_answer_id;
    }

    public function getQuestionId(): int
    {
        return $this->question_id;
    }

    public function setQuestionId(int $question_id): void
    {
        $this->question_id = $question_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getOfferedAnswerId(): int
    {
        return $this->offered_answer_id;
    }

    public function setOfferedAnswerId(int $offered_answer_id): void
    {
        $this->offered_answer_id = $offered_answer_id;
    }

    public function getUserAnswerText(): string
    {
        return $this->user_answer_text;
    }

    public function setUserAnswerText(string $user_answer_text): void
    {
        $this->user_answer_text = $user_answer_text;
    }
}