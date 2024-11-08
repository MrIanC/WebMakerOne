<?php
if (1) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

include __DIR__ . "/authenticate.php";
include __DIR__ . "/path.php";


$menu = [];
$plugins = glob(__DIR__ . "/plugins/*/main.php");
foreach ($plugins as $key => $plugin) {
    include $plugin;
}

if (isset($_GET['pluginpage'], $menu[$_GET['pluginpage']]['page']) && function_exists($menu[$_GET['pluginpage']]['page'])) {
    $menu[$_GET['pluginpage']]['page']();
    exit();
}

$state = json_decode(file_get_contents(__DIR__ . "/index/state.json"), true);

if (!file_exists(__DIR__ . "/plugins/build/build.txt")) {
    unset($menu['edit']);
    if (isset($_POST['toggle_live'])) {
        $state['index'] = ($state['index'] == "online") ? "offline" : "online";
        file_put_contents(__DIR__ . "/index/state.json", json_encode($state));
        header("Location: /setup");
    }

    $state = json_decode(file_get_contents(__DIR__ . "/index/state.json"), true);

    $partspageshtml = glob("../resources/parts/pages/*.html");
    $partspageshtml[] = "";
    $webroot = $_SERVER['DOCUMENT_ROOT'];
    $htmlonline = file_get_contents(__DIR__ . "/index/online.html");
    $htmloffline = file_get_contents(__DIR__ . "/index/offline.html");

    foreach ($partspageshtml as $key => $file) {
        $foldername = "/" . str_replace(".html", "", basename($file));
        $foldername = ($foldername == "/") ? "" : $foldername;
        switch ($state['index']) {
            case "online":
                file_put_contents("$webroot$foldername/index.html", $htmlonline);
                break;
            default:
                file_put_contents("$webroot$foldername/index.html", $htmloffline);
                break;
        }
    }

    $button = ($state['index'] == "online") ? "Activate Under Construction Mode" : "Activate Live Mode";
} else {
    unset($menu['build']);
    $state['index'] = "online";
    file_put_contents(__DIR__ . "/index/state.json", json_encode($state));
    $button = "";

}

$banner = ($state['index'] == "online") ? "Status: Live" : "Status: Under Construction";

$t = 0;
$content = [];
foreach ($menu as $key => $value) {
    $t++;
    if (isset($value['description']) && isset($value['link']) && isset($value['title']) && isset($value['sequence'])) {
        $content[$value['sequence']] = '
            <div class="col-12 col-sm-4 col-md-3 col-lg-2 " >
                <div class="m-1 shadow ">
                    <a title="' . $value['description'] . '" class="btn form-control py-4 " href="' . $value['link'] . '">' . $value['title'] . '</a>
                </div>
            </div>';
    }
}
ksort($content);

$warning = [];
if (file_exists("$usersPath/21232f297a57a5a743894a0e4a801fc3.php")) {
    $warning[] = "<div class=\"bg-warning text-danger p-3 text-center\">";
    $warning[] = "<div class=\"fw-bold\">User \"Admin\" exists</div>";
    $warning[] = "<div>to remove the user \"admin\" from settings->users you should login with a different account.</div>";
    $warning[] = "</div>";
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script id="jsonld" type="application/ld+json"></script>
</head>

<body>
    <?php
    echo implode($warning);
    ?>
    <div class="pt-3 text-center bg-white text-secondary">
        <h2 class="fw-bold">WebMakerOne</h2>
    </div>
    <div class="mb-5 sticky-top border-bottom">
        <form method="post">
            <div class="small bg-light">
                <div class="container">
                    <div class="d-flex justify-content-center">
                        <?php echo ($button == "") ? '' : "<button name=\"toggle_live\" value=\"toggle\" class=\"btn-sm btn btn-link\">$button</button>"; ?>
                        <span class="btn btn-sm">
                            <?php echo $banner ?>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <div class="row">
            <?php echo implode($content); ?>
        </div>
    </div>
    <div style="height:30vh">
    </div>
    <div class="container py-2">
        <div class="d-flex justify-content-between border-top bg-secondary-subtle p-3 rounded shadow">
            <div class="text-center align-self-center">
                <i class="text-secondary display-5 bi bi-gear"></i>
                <div class=" align-self-center text-secondary m-0 p-0">Settings</div>
            </div>
            <div class=" align-self-center">
                <a class="btn btn-light" href="<?php $mi = "ai";
                echo $menu[$mi]['link']; ?>"
                    title="<?php echo $menu[$mi]['description']; ?>"><?php echo $menu[$mi]['title']; ?></a>
                <a class="btn btn-light" href="<?php $mi = "cxchat";
                echo $menu[$mi]['link']; ?>"
                    title="<?php echo $menu[$mi]['description']; ?>"><?php echo $menu[$mi]['title']; ?></a>
                <a class="btn btn-light" href="<?php $mi = "users";
                echo $menu[$mi]['link']; ?>"
                    title="<?php echo $menu[$mi]['description']; ?>"><?php echo $menu[$mi]['title']; ?></a>
            </div>
        </div>
    </div>
    <div class="container py-2">
        <div class="d-flex justify-content-between border-top bg-secondary-subtle p-3 rounded shadow">
            <div class="text-center align-self-center">
                <i class="text-secondary  display-5 bi bi-file-earmark-arrow-down"></i>
                <div class=" align-self-center text-secondary m-0 p-0">Downloads</div>
            </div>
            <div class=" align-self-center">
                <a class="btn btn-light" href="<?php $mi = "zip";
                echo $menu[$mi]['link']; ?>"
                    title="<?php echo $menu[$mi]['description']; ?>"><?php echo $menu[$mi]['title']; ?></a>
                <a class="btn btn-light" href="<?php $mi = "bufti";
                echo $menu[$mi]['link']; ?>"
                    title="<?php echo $menu[$mi]['description']; ?>"><?php echo $menu[$mi]['title']; ?></a>
                <a class="btn btn-light" href="<?php $mi = "bifti";
                echo $menu[$mi]['link']; ?>"
                    title="<?php echo $menu[$mi]['description']; ?>"><?php echo $menu[$mi]['title']; ?></a>
            </div>
        </div>
    </div>
    <div class="container py-2">
        <div class="d-flex justify-content-between border-top bg-secondary-subtle p-3 rounded shadow">
            <div class="text-center align-self-center">
                <i class="text-secondary display-5 bi bi-plugin"></i>
                <div class=" align-self-center text-secondary m-0 p-0">Updates</div>
            </div>
            <div class=" align-self-center">
                <a class="btn btn-light" href="<?php $mi = "update";
                echo $menu[$mi]['link']; ?>"
                    title="<?php echo $menu[$mi]['description']; ?>"><?php echo $menu[$mi]['title']; ?></a>
            </div>
        </div>
    </div>
</body>

</html>