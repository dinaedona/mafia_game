<?php
require_once '../Model/User.php';

class GameUserHistory
{
    private User $actor;
    private User $recipient;
    private string $action;

    public function __construct(User $actor, User $recipient, string $action)
    {
        $this->actor = $actor;
        $this->recipient = $recipient;
        $this->action = $action;
    }

    public static function fromArray(array $data): GameUserHistory
    {
        $actor = User::fromValues($data['actor_id'], $data['actor_username'], $data['actor_password']);
        $recipient = User::fromValues($data['recipient_id'], $data['recipient_username'], $data['recipient_password']);
        return new self($actor, $recipient, $data['action']);
    }

    public function getActor(): User
    {
        return $this->actor;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}