<?php
if (isset($_POST['savefile'])) {
    file_put_contents(__DIR__ . "/available/" . $_GET['script'] . ".js", $_POST['savefile']);
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
            <div class="display-1 fw-bold">Scripts</div>
        </div>
        <div>
            <div class="h1">Active Scripts</div>
            <form method="POST">

                <textarea autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" name="savefile" class="form-control"
                    rows="20"><?php echo file_get_contents(__DIR__ . "/available/" . $_GET['script'] . ".js"); ?></textarea>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <script>
    </script>
</body>

</html>