<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../Repository/GameUserHistoryRepository.php';
require_once '../Model/User.php';
require_once '../Model/GameUserHistory.php';
$gameUserHistoryRepo = new GameUserHistoryRepository();
$gameUserHistories = $gameUserHistoryRepo->findByGameId($_SESSION['game_id']);
?>
<?php foreach ($gameUserHistories as $gameUserHistory): ?>
    <p><?= $gameUserHistory->getActor()->getUsername() ?> <span
                style="font-style: italic; font-size: 10px"><?= $gameUserHistory->getAction() ?></span> <?= $gameUserHistory->getRecipient()->getUsername() ?>
    </p>
<?php endforeach; ?>
