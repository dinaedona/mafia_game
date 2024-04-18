<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mafia Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('assets/img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh; /* Full height */
        }

        .container {
            display: flex;
        }
    </style>
</head>
<body>
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
                        <div class="mb-3">
                            <label for="username" class="form-label" style="float: left !important;">Username</label>
                            <input type="text" class="form-control" id="username" name="username" re>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label" style="float: left !important;">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary mr-2" id="login">Login</button>
                            <button type="button" class="btn btn-success" id="register">Register</button>
                        </div>
                        <div class="text-center">
                            <span style="display: none; color: red" id="usernamePasswordError"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/login_register.js"></script>
</body>
</html>

