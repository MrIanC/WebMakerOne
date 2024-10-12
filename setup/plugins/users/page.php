<?php
include $_SERVER['DOCUMENT_ROOT'] . "/setup/path.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['displayname']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $displayname = $_POST['displayname'];
        $password = $_POST['password'];
        $passhash = password_hash($password . $username, PASSWORD_DEFAULT);
        $tmp = [
            "name" => $displayname,
            "hash" => $passhash
        ];
        $userfilename = md5($username);
        $credentialFile =  "$usersPath/$userfilename.php";
    
        file_put_contents($credentialFile, "<?php".json_encode($tmp));
        //password_verify($password, $passhash);
        header("Location: login.php");
    }
    if (isset($_POST["deleteAccount"])) {
        $userfilename = $_POST["deleteAccount"];
        $credentialFile = "$usersPath/$userfilename";;
        if (file_exists($credentialFile)) {
            unlink($credentialFile);
        }
    }
}

$current_users = [];
foreach (glob("$usersPath/*.php") as $filename) {
    $fn = basename($filename);
    $tmp = json_decode(str_replace("<?php","",file_get_contents($filename)), true);
    if ($_SESSION['user_logged_in'] == str_replace(".php", "", $fn)) {
        $current_users[] = "
        <div class=\"mb-3 border-bottom\">
            <div class=\"d-flex justify-content-between\">
                <div>
                    {$tmp['name']}
                </div>
            </div>
        </div>";
    } else {
        if (isset($tmp['name'])) {
            $current_users[] = "
        <div class=\"mb-3 border-bottom\">
            <div class=\"d-flex justify-content-between\">
                <div>
                    {$tmp['name']}
                </div>
                <div>
                <button name=\"deleteAccount\" value=\"$fn\" class=\"btn btn-danger btn-sm\">X
                    
                </button>
                </div>
            </div>
        </div>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="no-index, no-follow">
    <meta name="favicon" content="favicon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="/resources/css/fonts.css" rel="stylesheet">
    <style>
    </style>
</head>

<body>
    <?php include "menu.php"; ?>
    <div class="container">

        <div class="text-center">
            <div class="display-1 fw-bold">Users</div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="border rounded px-3 pb-3 pt-1">
                    <div class="h1">Users</div>
                    <form method="POST">
                        <?php echo implode($current_users); ?>
                    </form>
                </div>

            </div>
            <div class="col-12 col-sm-6">
                <div class="border rounded px-3 pb-3 pt-1">
                    <form method="POST">
                        <div class="h1">New/Edit User</div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Display name</label>
                            <input type="text" name="displayname" class="form-control" id="displayname"
                                placeholder="Enter your Display Name" required>
                        </div>
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
                        <button type="submit" class="btn btn-primary w-100">Create/Edit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
    </script>
</body>

</html>