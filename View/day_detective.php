<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../Repository/GameUserRepository.php';
require_once '../Repository/UserRoleRepository.php';
require_once '../Repository/GameRepository.php';
require_once '../Model/GameUserRole.php';
require_once '../Model/UserRole.php';
$gameUserRepo = new GameUserRepository();
$userRoleRepo = new UserRoleRepository();
$gameRepo = new GameRepository();
$gameUserRoles = $gameUserRepo->findByGameIdAndUserIds($_SESSION['game_id'], json_decode($_POST['user_ids'], true));
$game = $gameRepo->findOneById($_SESSION['game_id']);
?>
<h6 class="card-title">Investigate the most suspect players</h6>
<form>
    <?php foreach ($gameUserRoles as $gameUserRole):?>
        <div class="form-group">
            <label for="user<?= $gameUserRole->getUserRole()->getUser()->getId() ?>">
                <input type="radio"
                       id="user<?= $gameUserRole->getUserRole()->getUser()->getId() ?>"
                       name="detective_user_to_eliminate"
                       <?= $gameUserRole->isAlive() ? '' : 'disabled' ?>
                       value="<?= $gameUserRole->getUserRole()->getUser()->getId() ?>">
                <?= $gameUserRole->getUserRole()->getUser()->getUsername() ?>:
                <span style="font-style: italic; font-size: 10px">" <?= $gameUserRole->getText() ?>"</span>
            </label>
        </div>
    <?php endforeach; ?>
    <div class="form-group" style="float: right">
        <button type="button" class="btn btn btn-light mr-2"
                style="width: 100px; font-weight: bold; background-image: url('/assets/img/background.png')"
                id="detective_eliminate_player">Save
        </button>
    </div>
</form>
<script src="/assets/js/game.js"></script>