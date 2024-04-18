<?php
require_once '../Database/DBConnection.php';
require_once '../Model/User.php';
require_once '../Model/UserRole.php';

class GameUserHistoryRepository
{
    private DBConnection $connection;

    public function __construct()
    {
        $this->connection = DBConnection::connect();
    }

    /**
     * @param int $gameId
     * @param mixed[] $users
     * @return void
     */
    public function insert(int $gameId, array $users): void
    {
        foreach ($users as $actor => $recipient) {
            $sql = "INSERT INTO game_user_history (game_id, actor_id, recipient_id, action) VALUES (?, ?, ?, ?)";
            $this->connection->executeQuery($sql, 'iiis', $gameId, (int)$actor, (int)$recipient, "wants to eliminate");
        }
    }

    /**
     * @param int $gameId
     * @return GameUserHistory[]
     */
    public function findByGameId(int $gameId): array
    {
        $sql = "SELECT
    a.id AS actor_id,
    a.username AS actor_username,
    a.password AS actor_password,
    r.id AS recipient_id,
    r.username AS recipient_username,
    r.password AS recipient_password,
      guh.action AS action
FROM game_user_history AS guh
JOIN users AS a ON guh.actor_id = a.id
JOIN users AS r ON guh.recipient_id = r.id
Where guh.game_id = ?";
        $results = $this->connection->fetchAll($sql, 'i', $gameId);
        return array_map(static fn(array $gameUserHistory) => GameUserHistory::fromArray($gameUserHistory), $results);
    }
}