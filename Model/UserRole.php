<?php
require_once '../Model/User.php';
require_once '../Model/Role.php';
class UserRole
{
    private User $user;
    private Role $role;

    private function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public static function fromValues(User $user, Role $role): UserRole
    {
        return new self($user, $role);
    }

    public static function fromDb(array $data): UserRole {
        $user = User::fromValues($data['user_id'], $data['user_username'], $data['user_password']);
        $role = Role::fromValues($data['role_id'], $data['role_name']);
        return self::fromValues($user, $role);
    }
    public function getUser(): User
    {
        return $this->user;
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}