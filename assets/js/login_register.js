$(document).ready(function () {
    $('#login').click(function () {
        let params = getParams();
        sendRequest('login', params);
    });

    $('#register').click(function () {
        let params = getParams();
        sendRequest('register', params);
    });
});

function getParams() {
    return {
        username: $('#username').val(),
        password: $('#password').val()
    };
}
function sendRequest(method, params) {
    params.method = method;
    $.ajax({
        type: 'POST',
        url: 'Controller/AuthenticatorController.php',
        data: params,
        dataType: "json",
        success: function (response) {
            if (parseInt(response.status) === 1) {
                window.open('View/main.php', '_self')
                return;
            }
            $('#usernamePasswordError').show().empty().text(response.message);
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}