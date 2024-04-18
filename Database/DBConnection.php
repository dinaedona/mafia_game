<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Exception/DatabaseException.php';

class DBConnection
{
    const HOST = "localhost";
    const USER = "root";
    const PASSWORD = "";
    const DATABASE = "mafia_game";
    const PORT = '3306';
    private mysqli $connection;
    private array $transactions = [];
    private bool $hasTransactionError = false;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }


    public static function connect(): DBConnection
    {
        $connection = mysqli_connect(self::HOST, self::USER, self::PASSWORD, self::DATABASE, self::PORT) or die('Network connection error. Check connection then reload');
        return new DBConnection($connection);
    }

    public function executeQuery($query, string $types, ...$parameters): void
    {
        $statement = $this->connection->prepare($query);
        if ('' !== $types || !empty($parameters)) {
            $statement->bind_param($types, ...$parameters);
        }
        $statement->execute();
        $statement->close();
    }

    /**
     * @param string $query
     * @param string $types
     * @param mixed[] $parameters
     * @return mysqli_result
     */
    private function getResult(string $query, string $types, array $parameters): mysqli_result
    {
        $statement = $this->connection->prepare($query);
        if (is_bool($statement) && $statement === false) {
            throw new DatabaseException($this->connection->error);
        }

        if ('' !== $types || !empty($parameters)) {
            $statement->bind_param($types, ...$parameters);
        }
        $statement->execute();
        $result = $statement->get_result();
        if (!$result) {
            throw new DatabaseException('Could not execute query. Error: ' . $this->connection->error);
        }

        $statement->close();
        return $result;
    }


    public function fetchOne(string $query, string $types = '', ...$parameters): ?array
    {
        return $this->getResult($query, $types, $parameters)->fetch_array(MYSQLI_ASSOC);
    }

    public function fetchAll(string $query, string $types = '', ...$parameters): array
    {
        return $this->getResult($query, $types, $parameters)->fetch_all(MYSQLI_ASSOC);
    }

    public function count(string $query, string $types = '', ...$parameters): int
    {
        $countQuery = <<<SQL
select count(*) AS 'uniqueCountAliasKe92uField' FROM 
({$query})uniqueCountAliasKe92u;
SQL;
        return (int)$this->fetchOne($countQuery, $types, ...$parameters)['uniqueCountAliasKe92uField'];
    }

    /** @inheritDoc */
    public function hasEntries(string $query, string $types = '', ...$parameters): bool
    {
        return $this->count($query, $types, ...$parameters) > 0;
    }

    public function runInTransaction(Closure $command, ...$args)
    {
        try {
            if (empty($this->transactions)) {
                $this->connection->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            }
            $commandId = random_bytes(16);
            $this->transactions[$commandId] = 1;

            $result = $command(...$args);
        } catch (TypeError | Throwable $e) {
            $this->hasTransactionError = true;
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } finally {
            unset($this->transactions[$commandId]);

            if (empty($this->transactions)) {
                if ($this->hasTransactionError) {
                    $this->connection->rollback();
                }

                $this->connection->commit();

                $this->hasTransactionError = false;
                $this->transactions = [];
            }
        }
        return $result;
    }

    public function getLastInsertId(): int
    {
        return mysqli_insert_id($this->connection);
    }
}