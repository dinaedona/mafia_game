<?php
include 'header.php';
require_once '../Repository/GameUserRepository.php';
require_once '../Repository/UserRoleRepository.php';
require_once '../Repository/GameRepository.php';
require_once '../Model/GameUserRole.php';
require_once '../Model/UserRole.php';
require_once '../Access/AccessVerifier.php';
$access = new AccessVerifier();
$access->verify('night');

$gameUserRepo = new GameUserRepository();
$userRoleRepo = new UserRoleRepository();
$gameRepo = new GameRepository();
$loggedInUser = $gameUserRepo->findOneByGameIdAndUserId($_SESSION['game_id'], $_SESSION['user_id']);
$gameUserRoles = $gameUserRepo->findAliveByGameId($_SESSION['game_id']);
$game = $gameRepo->findOneById($_SESSION['game_id']);
$isMafia = $loggedInUser->getUserRole()->getRole()->isMafia();
$isDoctorAndAlive =  $loggedInUser->getUserRole()->getRole()->isDoctor() && $loggedInUser->isAlive();
$key = $isMafia ? 'eliminate' : 'protect';
$role = $isMafia ? 'mafia' : 'doctor'
?>
<div class="form-group text-center">
<h1 class="text-center mt-5"> Night phase...zzZ! </h1>
<span class=" text-center" data-show="<?= !$isMafia && !$isDoctorAndAlive ? 1: 0 ?>"
      style="font-size: larger; font-weight: bolder; <?= !$isMafia && !$isDoctorAndAlive ? '': 'display:none' ?>" id="countdown">10</span>
</div>


<?php if ($isMafia || $isDoctorAndAlive): ?>
    <div class="container mt-5 ">
        <div class="card" style="width: 60rem; ">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="user_role_section" style="margin-top: 15px">
                        <h6 class="card-title">Choose the user that you want to <?= $key ?></h6>
                        <form>
                            <?php foreach ($gameUserRoles as $gameUserRole):
                                if ($gameUserRole->getUserRole()->getUser()->getId() === $loggedInUser->getUserRole()->getUser()->getId()) {
                                    continue;
                                } ?>
                                <div class="form-group">
                                    <label for="user<?= $gameUserRole->getUserRole()->getUser()->getId() ?>">
                                        <input type="radio"
                                               id="user<?= $gameUserRole->getUserRole()->getUser()->getId() ?>"
                                               name="user_to_<?= $key ?>"
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
                                        id="<?= $role ?>_<?= $key ?>_player">Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function () {
        function playNightPhase() {
            $.ajax({
                type: 'POST',
                url: '/Controller/GameController.php',
                data: {method: 'playNightPhase', user_id: null},
                dataType: "json",
                success: function (response) {
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }


            let countDownElem = document.getElementById("countdown");
            if (parseInt(countDownElem.getAttribute('data-show')) === 1) {
                let currentTimestamp = Date.now();
                // Add 10 seconds (30,000 milliseconds) to the current timestamp
                let futureTimestamp = currentTimestamp + 10000;
                // Set the date we're counting down to
                let countDownDate = new Date(futureTimestamp).getTime();

                // Update the count down every 1 second
                let x = setInterval(function () {
                    let now = new Date().getTime();
                    let distance = countDownDate - now;
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    // Display the result in the element with id="demo"
                    document.getElementById("countdown").innerHTML = seconds + "s ";

                    // If the count down is finished, write some text
                    if (seconds < 1) {
                        clearInterval(x);
                        changeGameStatus('Day');
                        window.open('day.php', '_self')
                    }
                }, 1000);
                playNightPhase();
            }
        function changeGameStatus(status) {
            $.ajax({
                type: 'POST',
                url: '/Controller/GameController.php',
                data: {method: 'changeGameStatus', status: status},
                dataType: "json",
                success: function () {
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
        function playMafiaDoctorNightPhase(selectedUser) {
            $.ajax({
                type: 'POST',
                url: '/Controller/GameController.php',
                data: {method: 'playNightPhase', user_id: selectedUser},
                dataType: "json",
                success: function (response) {
                    changeGameStatus('Day');
                    window.open('day.php', '_self')
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        $('#mafia_eliminate_player').click(function () {
            let selectedUser = $('input[name="user_to_eliminate"]').filter(":checked").val();
            if (selectedUser === undefined || selectedUser === null) {
                return;
            }
            playMafiaDoctorNightPhase(selectedUser);
        });

        $('#doctor_protect_player').click(function () {
            let selectedUser = $('input[name="user_to_protect"]').filter(":checked").val();
            if (selectedUser === undefined || selectedUser === null) {
                return;
            }
            playMafiaDoctorNightPhase(selectedUser);
        });
    });
</script>
<script src="/assets/js/game.js"></script>

