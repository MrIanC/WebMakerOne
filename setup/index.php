<?php


ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

include __DIR__ . "/authenticate.php";

$menu = [];
$plugins = glob(__DIR__ . "/plugins/*/main.php");
foreach ($plugins as $key => $plugin) {
    include $plugin;
}
if (isset($_GET['pluginpage'])) {
    $menu[$_GET['pluginpage']]['page']();
    exit();
}

$state = json_decode(file_get_contents(__DIR__ . "/index/state.json"), true);

if (!file_exists(__DIR__ . "/plugins/build/build.txt")) {
    unset($menu['edit']);
    if (isset($_POST['toggle_live'])) {
        echo "Toggle";
        $state['index'] = ($state['index'] == "online") ? "offline" : "online";
        file_put_contents(__DIR__ . "/index/state.json", json_encode($state));
        header("Location: index.php");
    }
    
    $state = json_decode(file_get_contents(__DIR__ . "/index/state.json"), true);

    foreach (glob("../resources/parts/pages/*.html") as $key => $file) {
        $foldername = str_replace(".html", "", basename($file));
        if ($state['index'] == "online") {
            file_put_contents("../$foldername/index.html", file_get_contents(__DIR__ . "/index/online.html"));
        } else {
            file_put_contents("../$foldername/index.html", file_get_contents(__DIR__ . "/index/offline.html"));
        }
    }
    if ($state['index'] == "online") {
        file_put_contents("../index.html", file_get_contents(__DIR__ . "/index/online.html"));
    } else {
        file_put_contents("../index.html", file_get_contents(__DIR__ . "/index/offline.html"));
    }
    $button = ($state['index'] == "online") ? "Activate Under Construction Mode" : "Activate Live Mode";

} else {
    unset($menu['build']);
    $state['index'] = "online";
    file_put_contents(__DIR__ . "/index/state.json", json_encode($state));
    $button ="";

}

$banner = ($state['index'] == "online") ? "Status: Live" : "Status: Under Construction";

$t = 0;
$content = [];
foreach ($menu as $key => $value) {
    $t++;
    if (isset($value['description']) && isset($value['link']) && isset($value['title']) && isset($value['sequence'])) {
        $content[$value['sequence']] = '
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 " >
                <div class="m-1 shadow ">
                    <a title="' . $value['description'] . '" class="btn form-control py-4 " href="' . $value['link'] . '">' . $value['title'] . '</a>
                </div>
            </div>';
    }
}
ksort($content);
$warning = [];
if (file_exists(__DIR__ . "/plugins/users/21232f297a57a5a743894a0e4a801fc3.json")) {
    $warning[] = "<div class=\"bg-warning text-danger p-3 text-center\">";
    $warning[] = "<div class=\"fw-bold\">User \"Admin\" still exists</div>";
    $warning[] = "<div>Create a new user and remove the default 'admin' user from the users section</div>";
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
    <div class="text-center">
        <h2 class="display-5 fw-bold">WebMakerOne</h2>
    </div>

    <form method="post" class=" pb-2 mb-5">
        <div class="small bg-light mb-1">
            <div class="container">
                <div class="d-flex justify-content-center">
                    <?php echo ($button == "")?'':'<button name="toggle_live" value="toggle" class="btn-sm btn btn-link">'.$button.'</button>';?> 
                    <span class="btn btn-sm">
                        <?php echo $banner ?>
                    </span>
                </div>
            </div>
        </div>
    </form>
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