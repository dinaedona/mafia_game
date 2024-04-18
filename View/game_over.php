<h1 class="text-center"><?= $_POST['message'] ?></h1>
<div class="form-group" style="float: right">
    <button type="button" class="btn btn btn-light mr-2"
            style="width: 100px; font-weight: bold; background-image: url('/assets/img/background.png')"
            id="end_game">End
    </button>
</div>
<script>
    $(document).ready(function () {
        $('#end_game').click(function () {
            $.ajax({
                type: 'POST',
                url: '/Controller/GameController.php',
                data: {method: 'endGame',},
                dataType: "json",
                success: function (response) {
                    if (parseInt(response.status) === 1) {
                        window.open('main.php', '_self')
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
