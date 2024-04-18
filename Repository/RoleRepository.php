<?php
require_once '../Database/DBConnection.php';
require_once '../Model/Role.php';

class RoleRepository
{
    private DBConnection $connection;
    public function __construct()
    {
        $this->connection = DBConnection::connect();
    }

    /**
     * @return Role[]
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM role";

        $results = $this->connection->fetchAll($sql);
       return array_map(static fn(array $role) => Role::fromArray($role), $results);
    }
}