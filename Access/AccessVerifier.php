<?php
require_once '../Repository/GameRepository.php';

class AccessVerifier
{
    private GameRepository $gameRepo;

    public function __construct()
    {
        $this->gameRepo = new GameRepository();
    }

    public function verify($page): void
    {
        $gameStatus = $this->gameRepo->findGameStatus($_SESSION['game_id']);
        if ($page === 'statement') {
            if ($gameStatus === 'Day') {
                $page = "Location: /View/day.php";
            }
            if ($gameStatus === 'Night') {
                $page = "Location: /View/night.php";
            }
            if ($gameStatus === 'End') {
                $page = "Location: /View/main.php";
            }
        }
        if ($page === 'day') {
            if ($gameStatus === 'Start') {
                $page = "Location: /View/statement.php";
            }
            if ($gameStatus === 'Night') {
                $page = "Location: /View/night.php";
            }
            if ($gameStatus === 'End') {
                $page = "Location: /View/main.php";
            }
        }
        if ($page === 'night') {
            if ($gameStatus === 'Start') {
                $page = "Location: /View/statement.php";
            }
            if ($gameStatus === 'Day') {
                $page = "Location: /View/day.php";
            }
            if ($gameStatus === 'End') {
                $page = "Location: /View/main.php";
            }
        }
        if ($page === 'main') {
            if ($gameStatus === 'Start') {
                $page = "Location: /View/statement.php";
            }
            if ($gameStatus === 'Day') {
                $page = "Location: /View/day.php";
            }
            if ($gameStatus === 'Night') {
                $page = "Location: /View/night.php";
            }
        }
        header($page);
        exit();
    }
}