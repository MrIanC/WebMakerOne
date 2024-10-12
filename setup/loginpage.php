<?php
if (!isset($users)) {
    header("HTTP/1.1 404 Not Found");
    die();
}
function checkCreds()
{
    global $usersPath;
    if (!isset($_POST['username']))
        return;
    if (!isset($_POST['password']))
        return;
    if (empty($_POST['username']))
        return;
    if (empty($_POST['password']))
        return;
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passhash = "";
    $userfilename = md5($username);
    $credentialFile = "$usersPath/$userfilename.php";
    if (file_exists($credentialFile)) {
        $json = json_decode(str_replace("<?php", "", file_get_contents($credentialFile)), true);
        $passhash = $json['hash'];
    }
    if (password_verify($password . $username, $passhash)) {
        $_SESSION['user_logged_in'] = md5($username);
        header("Location: /setup");
        exit();
    } else {
        $error = "Invalid username or password.";
        return $error;
    }

}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = checkCreds();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            margin-top: 10%;
            padding: 2rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            box-shadow: none;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="login-container col-md-4">
            <h3 class="text-center mb-4">Login</h3>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username"
                        placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password"
                        placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                <a href="#">Forgot your password?</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>