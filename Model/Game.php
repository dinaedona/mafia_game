<?php


class Game
{
    private ?int $id;
    private string $status;
    private int $day;
    private int $eliminateUserId;
    private ?int $protectUserId;


    private function __construct(?int $id, string $status, int $day, int $eliminateUserId, ?int $protectUserId)
    {
        $this->id = $id;
        $this->status = $status;
        $this->day = $day;
        $this->eliminateUserId = $eliminateUserId;
        $this->protectUserId = $protectUserId;
    }

    public static function fromArray(array $data): Game
    {
        $id = $data['id'] ?: null;
        return new self($id, $data['status'], $data['day'], $data['eliminate_user_id'], $data['protect_user_id']);
    }

    public static function fromValues(?int $id, string $status, int $day, int $eliminateUserId, ?int $protectUserId): Game
    {
        return new self($id, $status, $day, $eliminateUserId, $protectUserId);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getEliminateUserId(): int
    {
        return $this->eliminateUserId;
    }

    public function getProtectUserId(): ?int
    {
        return $this->protectUserId;
    }
}