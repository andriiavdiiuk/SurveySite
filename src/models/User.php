<?php

class User
{
    private int $user_id;
    private string $email;
    private string $password;
    private bool $admin;

    /**
     * @param int $user_id
     * @param string $email
     * @param string $password
     * @param bool $admin
     */
    public function __construct(int $user_id=-1, string $email="", string|null $password="", bool|null $admin=false)
    {
        $this->user_id = $user_id;
        $this->email = $email;
        if ($password == null)
        {
            $this->password = "";
        }
        else {
            $this->password = $password;
        }
        if ($admin == null)
        {
            $this->admin = $admin;
        }
        else {
            $this->admin = $admin;
        }

    }


    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }
}
