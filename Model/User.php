<?php

class User
{
    private ?int $id;
    private string $username;
    private string $password;

    private function __construct(?int $id, string $username, string $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public static function fromValues(?int $id, string $username, string $password): User
    {
        return new self($id, $username, $password);
    }

    public static function fromArray(array $data): User
    {
        $id = $data['id'] ?? null;
        return new self($id, $data['username'], $data['password']);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}