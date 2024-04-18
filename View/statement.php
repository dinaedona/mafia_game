<?php
include 'header.php';
require_once '../Repository/GameUserRepository.php';
require_once '../Model/GameUserRole.php';
require_once '../Access/AccessVerifier.php';
$access = new AccessVerifier();
$access->verify('statement');
$gameUserRepo = new GameUserRepository();
$gameUserRole = $gameUserRepo->findOneByGameIdAndUserId($_SESSION['game_id'], $_SESSION['user_id']);
?>
<div class="container mt-5 ">
    <div class="card" style="width: 60rem; ">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form>
                        <div class="mb-3 mt-5">
                            <h4><?= $_SESSION['username'] ?>(<?= $gameUserRole->getUserRole()->getRole()->getName() ?>
                                )</h4>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label" style="float: left !important; font-weight: bold">Your
                                turn to speak...</label>
                            <textarea class="form-control" id="user_text" name="user_text" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn btn-light mr-2"
                                    style="width: 200px; font-size: large; font-weight: bold; background-image: url('/assets/img/background.png')"
                                    id="save_statement">Done
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/game.js"></script>
