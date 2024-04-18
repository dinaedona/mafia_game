<?php
require_once 'Repository/GameRepository.php';

class AccessVerifier
{
    private GameRepository $gameRepo;

    public function __construct()
    {
        $this->gameRepo = new GameRepository();
    }

    public function verify($page): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /index.php");
            exit();
        }

        if ($page !== 'main' && !isset($_SESSION['game_id'])) {
            header("Location: /View/main.php");
            exit();
        }
        if ($page === 'main' && !isset($_SESSION['game_id'])) {
            return;
        }
        $gameStatus = $this->gameRepo->findGameStatus($_SESSION['game_id']);
        if ($page === 'index') {
            if ($gameStatus === 'Start') {
                $page = "Location: /View/statement.php";
            }
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
        if ($page === 'statement') {
            if ($gameStatus === 'Start') {
                return;
            }
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
            if ($gameStatus === 'Day') {
                return;
            }
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
            if ($gameStatus === 'Night') {
                return;
            }
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
            if ($gameStatus === 'main') {
                return;
            }
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