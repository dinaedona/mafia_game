<?php
require_once '../Database/DBConnection.php';
require_once '../Model/User.php';
require_once '../Model/UserRole.php';
require_once '../Database/WhereInClause.php';

class GameUserRepository
{
    private DBConnection $connection;

    public function __construct()
    {
        $this->connection = DBConnection::connect();
    }

    /**
     * @param int $gameId
     * @param UserRole[] $userRoles
     * @return void
     */
    public function insert(int $gameId, array $userRoles): void
    {
        foreach ($userRoles as $userRole) {
            $sql = "INSERT INTO game_user (game_id, user_id, role_id, text, is_alive) VALUES (?, ?, ?, ?, ?)";
            $this->connection->executeQuery($sql, 'iiisi', $gameId, $userRole->getUser()->getId(), $userRole->getRole()->getId(), '', 1);
        }
    }

    private function getBaseQuery(): string
    {
        return "SELECT 
       u.id as  user_id,
       u.username as user_username,
       u.password as user_password,
       r.id as role_id,
       r.name as role_name,
       g.id as game_id,
       g.day as game_day,
       g.status as game_status,
       g.eliminate_user_id as game_eliminate_user_id,
       g.protect_user_id as game_protect_user_id,
       gu.text as game_user_text,
       gu.is_alive as game_user_is_alive      
FROM game_user gu
INNER JOIN game g ON gu.game_id = g.id
INNER JOIN users u ON gu.user_id = u.id
INNER JOIN role r ON gu.role_id = r.id";
    }

    public function findOneByGameIdAndUserId(int $gameId, int $userId): ?GameUserRole
    {
        $sql = $this->getBaseQuery() . " WHERE gu.user_id = ? AND gu.game_id = ?";

        $results = $this->connection->fetchOne($sql, 'ii', $userId, $gameId);
        if ($results === NULL) {
            return null;
        }
        return GameUserRole::fromArray($results);
    }

    public function findDoctorByGameId(int $gameId): ?GameUserRole
    {
        $sql = $this->getBaseQuery() . " WHERE gu.game_id = ? AND r.name = 'Doctor'";

        $results = $this->connection->fetchOne($sql, 'i', $gameId);
        if ($results === NULL) {
            return null;
        }
        return GameUserRole::fromArray($results);
    }

    /**
     * @param int $gameId
     * @return GameUserRole[]
     */
    public function findByGameId(int $gameId): array
    {
        $sql = $this->getBaseQuery() . " WHERE gu.game_id = ?";

        $results = $this->connection->fetchAll($sql, 'i', $gameId);
        return array_map(static fn(array $gameUserRole) => GameUserRole::fromArray($gameUserRole), $results);
    }

    /**
     * @param int $gameId
     * @return GameUserRole[]
     */
    public function findAliveByGameId(int $gameId): array
    {
        $sql = $this->getBaseQuery() . " WHERE gu.game_id = ? and gu.is_alive = 1";

        $results = $this->connection->fetchAll($sql, 'i', $gameId);
        return array_map(static fn(array $gameUserRole) => GameUserRole::fromArray($gameUserRole), $results);
    }

    /**
     * @param int $gameId
     * @param int[] $ids
     * @return GameUserRole[]
     */
    public function findByGameIdAndUserIds(int $gameId, array $ids): array
    {
        $whereIn = WhereInClause::fromInts($ids);
        $sql = $this->getBaseQuery() . " WHERE gu.game_id = ? and gu.user_id IN ({$whereIn->getPlaceholder()})";

        $results = $this->connection->fetchAll($sql, 'i'.$whereIn->getTypes(), $gameId, ...$whereIn->getParameters());
        return array_map(static fn(array $gameUserRole) => GameUserRole::fromArray($gameUserRole), $results);
    }

    /**
     * @param int $gameId
     * @return User[]
     */
    public function findActiveUsersByGameId(int $gameId): array
    {
        $sql = "SELECT u.*
    FROM game_user gu
INNER JOIN users u ON gu.user_id = u.id
WHERE gu.game_id = ? and gu.is_alive = 1";

        $results = $this->connection->fetchAll($sql, 'i', $gameId);
        return array_map(static fn(array $user) => User::fromArray($user), $results);
    }

    /**
     * @param int $gameId
     * @return User[]
     */
    public function findAliveVillagersByGameId(int $gameId): array
    {
        $sql = "SELECT u.*
    FROM game_user gu
INNER JOIN users u ON gu.user_id = u.id
INNER JOIN role r ON gu.role_id = r.id
WHERE gu.game_id = ? and gu.is_alive = 1 and r.name = 'Villager'";

        $results = $this->connection->fetchAll($sql, 'i', $gameId);
        return array_map(static fn(array $user) => User::fromArray($user), $results);
    }

    public function updateText(int $gameId, array $userTexts)
    {
        foreach ($userTexts as $userId => $text) {
            $sql = "UPDATE game_user SET text = ? WHERE game_id = ? AND user_id = ?";
            $this->connection->executeQuery($sql, 'sii', $text, $gameId, (int)$userId);
        }
    }

    public function updateUserStatus(int $gameId, int $userId)
    {
        $sql = "UPDATE game_user SET is_alive = 0 WHERE game_id = ? AND user_id = ?";
        $this->connection->executeQuery($sql, 'ii', $gameId, $userId);
    }



}