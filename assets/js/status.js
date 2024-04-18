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