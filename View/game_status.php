<?php
// redirect to view based on game phase

if(isset($_SESSION['game_id'])) {
    require_once '../Repository/GameRepository.php';
    $gameRepo = new GameRepository();

    $gameStatus = $gameRepo->findGameStatus($_SESSION['game_id']);
    $page = "Location: /index.php";
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
    header($page);
    exit(); // Stop executing the script
}
?>