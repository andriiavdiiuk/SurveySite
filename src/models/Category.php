<?php


class Category
{
    private int $category_id;
    private string $category_text;
    private string $category_whom;
    private string $category_what_you_get;

    /**
     * @param int $category_id
     * @param string $category_text
     * @param string $category_whom
     * @param string $category_what_you_get
     */
    public function __construct(int $category_id=-1, string $category_text="", string $category_whom="", string $category_what_you_get="")
    {
        $this->category_id = $category_id;
        $this->category_text = $category_text;
        $this->category_whom = $category_whom;
        $this->category_what_you_get = $category_what_you_get;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function getCategoryText(): string
    {
        return $this->category_text;
    }

    public function getCategoryWhom(): string
    {
        return $this->category_whom;
    }

    public function getCategoryWhatYouGet(): string
    {
        return $this->category_what_you_get;
    }
}
