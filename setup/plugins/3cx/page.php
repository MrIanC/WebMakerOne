<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/setup/plugins/scripts/available/")) {
    header("location: /setup");
    exit;
}



include($_SERVER['DOCUMENT_ROOT'] . "/setup/path.php");
$cxapikeydir = dirname($usersPath) . "/plugins";
$apifile = "$cxapikeydir/3cx.php";
if (!is_dir($cxapikeydir)) {
    mkdir($cxapikeydir, 0777);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $filename = $apifile;
    $settings = [
        "phonesystem-url" => ($_POST['phonesystem-url'] ?? ""),
        "party" => ($_POST['party'] ?? "")
    ];
    file_put_contents($filename, "<?php" . json_encode($settings, JSON_PRETTY_PRINT));
}
$filename = $apifile;
$phonesystemurl = "";
$party = "";

if (file_exists($filename)) {
    $settings = json_decode(str_replace("<?php", "", file_get_contents($filename)), true);
    $phonesystemurl = $settings['phonesystem-url'] ?? "";
    $party = $settings['party'] ?? "";
}

$r = str_replace(['#phonesystemurl#','#party#'],[$phonesystemurl,$party],file_get_contents(__DIR__ . "/3cxContactjs.js"));
file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/setup/plugins/scripts/available/3cxContact.js",$r);

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
            <div class="display-1 fw-bold">3CX Chat</div>
        </div>
        <div class="row">
            <div class="col">
                <p>To use the chat feature you need to setup an account with 3cx and set up a live chat from the voice
                    and chat. <a href="https://www.3cx.com/">3cx</a>.
            </div>
            </p>

            <div class="col">
                <form method="post">
                    <div class="fw-bold">3CX phonesystem-url</div>
                    <input class="form-control mb-3" type="text" name="phonesystem-url"
                        value="<?php echo $phonesystemurl; ?>">
                    <div class="fw-bold">3CX party</div>
                    <input class="form-control mb-3" type="text" name="party" value="<?php echo $party; ?>">
                    <button class="btn btn-primary">Save</button>

                </form>
            </div>
        </div>
    </div>
</body>

</html>