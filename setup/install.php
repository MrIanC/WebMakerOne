<?php
function makeUser()
{
    if (!isset($_POST['username']))
        return;
    if (!isset($_POST['password']))
        return;
    if (!isset($_POST['displayname']))
        return;
    if (empty($_POST['username']))
        return;
    if (empty($_POST['password']))
        return;
    if (empty($_POST['displayname']))
        return;

    $usersPath = $_SERVER['DOCUMENT_ROOT'] . "/setup/plugins/users/credentials";
    $oodr = false;

    if (is_readable(dirname($_SERVER['DOCUMENT_ROOT']))) {
        try {
            file_put_contents(dirname($_SERVER['DOCUMENT_ROOT']) . "/text.txt", "testing");
            unlink(dirname($_SERVER['DOCUMENT_ROOT']) . "/text.txt");
            $oodr = true;
        } catch (Exception $error) {
            $oodr = false;
        }

        if ($oodr == true) {
            $usersPath = dirname($_SERVER['DOCUMENT_ROOT']) . "/users";
        }
    } else {
        $oodr = false;
    }

    try {
        if (!is_dir($usersPath)) {
            mkdir($usersPath);
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        $displayname = $_POST['displayname'];

        $file = md5($username);
        $passhash = password_hash($password . $username, PASSWORD_DEFAULT);
        $tmp = [
            "name" => $displayname,
            "hash" => $passhash
        ];
        $userfilename = md5($username);
        $credentialFile = "$usersPath/$userfilename.php";
        if ($oodr == true) {
            file_put_contents(__DIR__ . "/path.php", "<?php
            \$usersPath = dirname(\$_SERVER['DOCUMENT_ROOT']) . \"/users\";
            ");
        } else {
            file_put_contents(__DIR__ . "/path.php", "<?php
            \$usersPath = \$_SERVER['DOCUMENT_ROOT'] . \"/setup/plugins/users/credentials\";
            ");
        }
        ;

        file_put_contents($credentialFile, "<?php" . json_encode($tmp));
        header("location: login.php");

    } catch (Exception $error) {
        print_r($error);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    makeUser();
}


$oodr = false;

if (is_readable(dirname($_SERVER['DOCUMENT_ROOT']))) {
    try {
        file_put_contents(dirname($_SERVER['DOCUMENT_ROOT']) . "/text.txt", "testing");
        unlink(dirname($_SERVER['DOCUMENT_ROOT']) . "/text.txt");
        $oodr = true;
    } catch (Exception $error) {
        $oodr = false;
    }

    if ($oodr == true) {
        $usersPath = dirname($_SERVER['DOCUMENT_ROOT']) . "/users";
    }

} else {
    $oodr = false;
}

$hint = $oodr ? "User Credentials are stored outside of the document root. <b>This is Good</b>" : "User Credentials are stored inside of the document root. <b>This is Bad</b>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Custom CSS for additional styling -->
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
        <div class="login-container col-md-8">
            <h1 class="text-center mb-4">WebMakerOne</h1>
            <h3 class="text-center mb-4">Installation Setup</h3>
            <p class="fw-bold">
                Create Credentials
            </p>


            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($hint)): ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $hint; ?>
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
                <div class="mb-3">
                    <label for="username" class="form-label">Display Name</label>
                    <input type="text" name="displayname" class="form-control" id="displayusername"
                        placeholder="Enter your Display Name" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Create</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional but recommended for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>