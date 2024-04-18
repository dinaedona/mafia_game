$(document).ready(function () {
    $('#start_game').click(function () {
        $.ajax({
            type: 'POST',
            url: '/Controller/GameController.php',
            data: {method: 'start'},
            dataType: "json",
            success: function (response) {
                if (parseInt(response.status) === 1) {
                    window.open('statement.php', '_self')
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
    $('#save_statement').click(function () {
        $.ajax({
            type: 'POST',
            url: '/Controller/GameController.php',
            data: {method: 'saveStatement', text: $('#user_text').val()},
            dataType: "json",
            success: function (response) {
                if (parseInt(response.status) === 1) {
                    window.open('day.php', '_self')
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

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

    $('#eliminate_player').click(function () {
        let selectedUser = $('input[name="user_to_eliminate"]').filter(":checked").val();
        if(selectedUser === undefined || selectedUser === null){
            return;
        }
        $.ajax({
            type: 'POST',
            url: '/Controller/GameController.php',
            data: {method: 'eliminatePlayer', user_id: selectedUser},
            dataType: "json",
            success: function (response) {
                showHistorySection();
                let status = parseInt(response.status);
                if (status === -1) {
                    showDetectiveSection(response.data)
                }
                if (status === -2 || status === 1 ) {
                    showGameOverSection(response.message)
                }
                if(status === 0){
                    window.open('night.php', '_self')
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#detective_eliminate_player').click(function () {
        let selectedUser = $('input[name="detective_user_to_eliminate"]').filter(":checked").val();
        if(selectedUser === undefined || selectedUser === null){
            return;
        }
        $.ajax({
            type: 'POST',
            url: '/Controller/GameController.php',
            data: {method: 'detectiveEliminatePlayer', user_id: selectedUser},
            dataType: "json",
            success: function (response) {
                showHistorySection();
                let status = parseInt(response.status);
                if (status === -2 || status === 1 ) {
                    showGameOverSection(response.message)
                }
                if(status === 0){
                    window.open('night.php', '_self')
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    function showDetectiveSection(userIds){
        $.ajax({
            type: 'POST',
            url: 'day_detective.php',
            data: {user_ids: userIds},
            dataType: "html",
            success: function (response) {
                $('#user_role_section').empty().append(response);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    function showGameOverSection(message){
        $.ajax({
            type: 'POST',
            url: 'game_over.php',
            data: {message: message},
            dataType: "html",
            success: function (response) {
                $('#user_role_section').empty().append(response);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function showHistorySection(message){
        $.ajax({
            type: 'POST',
            url: 'game_user_history.php',
            data: {message: message},
            dataType: "html",
            success: function (response) {
                $('#game_user_history').empty().append(response);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
});
