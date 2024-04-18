<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/Database/DBConnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] .'/Model/Game.php';

class GameRepository
{
    private DBConnection $connection;
    public function __construct()
    {
        $this->connection = DBConnection::connect();
    }

    public function findGameStatus(int $id): string
    {
        $sql = "SELECT status FROM game WHERE id = ?";

        $results = $this->connection->fetchOne($sql, 'i', $id);
        return $results['status'];
    }

    public function insert(Game $game): int
    {
        $sql = "INSERT INTO game (status, day, eliminate_user_id, protect_user_id) VALUES (?, ?, ?, ?)";
        $this->connection->executeQuery($sql, 'siii', $game->getStatus(), $game->getDay(), $game->getEliminateUserId(), $game->getProtectUserId());
        return $this->connection->getLastInsertId();
    }

    public function updateStatus(int $gameId, string $status): void
    {
        $sql = "UPDATE game SET status = ? WHERE id = ?";
        $this->connection->executeQuery($sql, 'si', $status, $gameId);
    }

    public function update(Game $game): void
    {
        $sql = "UPDATE game SET status = ?, day = ?, eliminate_user_id = ?, protect_user_id = ?  WHERE id = ?";
        $this->connection->executeQuery($sql, 'siiii', $game->getStatus(),$game->getDay(), $game->getEliminateUserId(), $game->getProtectUserId(), $game->getId());
    }

    public function findOneById(int $id): ?Game
    {
        $sql = "SELECT * FROM game WHERE id = ?";

        $results = $this->connection->fetchOne($sql, 'i', $id);
        if($results === NULL){
            return null;
        }
        return Game::fromArray($results);
    }
}