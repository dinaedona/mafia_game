<?php
include 'header.php';
require_once '../Repository/GameUserRepository.php';
require_once '../Repository/UserRoleRepository.php';
require_once '../Repository/GameRepository.php';
require_once '../Model/GameUserRole.php';
require_once '../Model/UserRole.php';
$gameUserRepo = new GameUserRepository();
$userRoleRepo = new UserRoleRepository();
$gameRepo = new GameRepository();
$loggedInUser = $userRoleRepo->findOneByGameIdAndUserId($_SESSION['game_id'], $_SESSION['user_id']);
$gameUserRoles = $gameUserRepo->findByGameId($_SESSION['game_id']);
$game = $gameRepo->findOneById($_SESSION['game_id']);
?>
<style>
    .scrollable-div {
        width: 40rem;
        height: 280px;
        overflow: auto;
    }
</style>
<div class="container mt-5 ">
    <div class="card" style="width: 60rem; ">
        <div class="card-body">
            <div style="display: inline">
                <i class="fa fa-sun-o" aria-hidden="true">Day <?= $game->getDay() ?></i>
                <i class="fa fa-users" style="margin-left: 10px" aria-hidden="true"><?= $_SESSION['username'] ?></i>
            </div>
            <div class="row">
                <div class="col-md-12" id="user_role_section" style="margin-top: 15px">
                            <h6 class="card-title">Player you want to kill</h6>
                            <form>
                                <?php foreach ($gameUserRoles as $gameUserRole):
                                    if ($gameUserRole->getUserRole()->getUser()->getId() === $loggedInUser->getUser()->getId()) {
                                        continue;
                                    } ?>
                                    <div class="form-group" <?= $gameUserRole->isAlive() ? '' : 'style="color: red"' ?>>
                                        <label for="user<?= $gameUserRole->getUserRole()->getUser()->getId() ?>">
                                            <input type="radio"
                                                   id="user<?= $gameUserRole->getUserRole()->getUser()->getId() ?>"
                                                   name="user_to_eliminate"
                                                   <?= $gameUserRole->isAlive() ? '' : 'disabled' ?>
                                                   value="<?= $gameUserRole->getUserRole()->getUser()->getId() ?>">
                                            <?= $gameUserRole->getUserRole()->getUser()->getUsername() ?>:
                                            <span style="font-style: italic; font-size: 10px;">" <?= $gameUserRole->getText() ?>"</span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <div class="form-group" style="float: right">
                                    <button type="button" class="btn btn btn-light mr-2"
                                            style="width: 100px; font-weight: bold; background-image: url('/assets/img/background.png')"
                                            id="eliminate_player">Save
                                    </button>
                                </div>
                            </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card" style="width: 40rem; margin-left: 10px ">
        <div class="card-body">
            <h3 class="text-center">Game History</h3>
            <div class="row">
                <div class="col-md-12 scrollable-div" id="game_user_history">
                <?php include 'game_user_history.php' ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/game.js"></script>

