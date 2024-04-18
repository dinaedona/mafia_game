$(document).ready(function () {
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