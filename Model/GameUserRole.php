<?php
require_once '../Model/User.php';
require_once '../Model/Role.php';
require_once '../Model/Game.php';
require_once '../Model/UserRole.php';

class GameUserRole
{
    private Game $game;
    private UserRole $userRole;
    private string $text;
    private bool $isAlive;

    public function __construct(Game $game, UserRole $userRole, string $text, bool $isAlive)
    {
        $this->game = $game;
        $this->userRole = $userRole;
        $this->text = $text;
        $this->isAlive = $isAlive;
    }

    public static function fromArray(array $data): GameUserRole
    {
        $game = Game::fromValues($data['game_id'], $data['game_status'], $data['game_day'], $data['game_eliminate_user_id'], $data['game_protect_user_id']);
        $user = User::fromValues($data['user_id'], $data['user_username'], $data['user_password']);
        $role = Role::fromValues($data['role_id'], $data['role_name']);
        $isAlive = $data['game_user_is_alive'] === 1;
        return new self($game, UserRole::fromValues($user, $role), $data['game_user_text'], $isAlive);
    }

    public static function fromValues(Game $game, UserRole $userRole, string $text, bool $isAlive): GameUserRole
    {
        return new self($game, $userRole, $text, $isAlive);
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getUserRole(): UserRole
    {
        return $this->userRole;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return bool
     */
    public function isAlive(): bool
    {
        return $this->isAlive;
    }

    public function __toString(): string
    {
        return (string)json_encode([
            'game' => [
                'id' => $this->game->getId(),
                'day' => $this->game->getDay(),
                'status' => $this->game->getStatus(),
                'user_id' => $this->game->getUserId()
            ],
            'userRole' => [
                'user' => [
                    'id' => $this->userRole->getUser()->getId(),
                    'username' => $this->userRole->getUser()->getUsername(),
                    'password' => $this->userRole->getUser()->getPassword()
                ],
                'role' => [
                    'id' => $this->userRole->getRole()->getId(),
                    'name' => $this->userRole->getRole()->getName()
                ]
            ]
        ]);
    }
}