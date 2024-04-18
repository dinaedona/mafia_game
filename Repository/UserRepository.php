<?php
require_once '../Database/DBConnection.php';
require_once '../Model/User.php';

class UserRepository
{
    private DBConnection $connection;
    public function __construct()
    {
        $this->connection = DBConnection::connect();
    }

    public function findOneByUsernameAndPassword(string $username): ?User
    {
        $sql = "SELECT * FROM users WHERE username = ?";

       $results = $this->connection->fetchOne($sql, 's', $username);
       if($results === NULL){
           return null;
       }
        return User::fromArray($results);
    }
    public function findOneById(int $id): ?User
    {
        $sql = "SELECT * FROM users WHERE id = ?";

        $results = $this->connection->fetchOne($sql, 'i', $id);
        if($results === NULL){
            return null;
        }
        return User::fromArray($results);
    }

    /**
     * @return User[]
     */
    public function findPcPlayers(): array
    {
        $sql = "SELECT * FROM users WHERE is_pc_player = 1";

        $results = $this->connection->fetchAll($sql);
       return array_map(static fn(array $user) =>  User::fromArray($user), $results);
    }

    public function usernameExists(string $username): bool
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->connection->hasEntries($sql, 's', $username);
    }

    public function insert(string $username, string $password): int
    {
        $sql = "INSERT INTO users (username, password, is_pc_player) VALUES (?, ?, ?)";
        $this->connection->executeQuery($sql, 'ssi', $username, $password, 0);
        return $this->connection->getLastInsertId();
    }
}