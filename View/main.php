<?php include 'header.php';
require_once '../Access/AccessVerifier.php';
$access = new AccessVerifier();
$access->verify('main');
?>
<div class="container mt-5 justify-content-center">
    <div class="card justify-content-center" style="width: 30rem; ">
        <div class="card-body">
            <h1 class="text-center">Mafia Game</h1>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form>
                        <div class="mb-3 text-center">
                            <img src="/assets/img/mafia_logo.jpg" alt="Mafia Logo" class="img-fluid">
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn btn-light mr-2"
                                    style="width: 200px; font-size: large; font-weight: bold; background-image: url('/assets/img/background.png')"
                                    id="start_game">Start Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/game.js"></script>