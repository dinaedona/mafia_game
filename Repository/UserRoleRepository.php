<?php
require_once '../Database/DBConnection.php';
require_once '../Model/User.php';
require_once '../Model/UserRole.php';

class UserRoleRepository
{
    private DBConnection $connection;
    public function __construct()
    {
        $this->connection = DBConnection::connect();
    }

    public function findOneByGameIdAndUserId(int $gameId, int $userId): ?UserRole
    {
        $sql = "SELECT 
       u.id as  user_id,
       u.username as user_username,
       u.password as user_password,
       r.id as role_id,
       r.name as role_name
FROM game_user gu
INNER JOIN users u ON gu.user_id = u.id
INNER JOIN role r ON gu.role_id = r.id
WHERE gu.user_id = ? AND gu.game_id = ?";

        $results = $this->connection->fetchOne($sql, 'ii', $userId, $gameId);
        if ($results === NULL) {
            return null;
        }
        return UserRole::fromDb($results);
    }

}