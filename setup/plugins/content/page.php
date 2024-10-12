<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);
if (isset($_GET['deletePage'])) {
    if (!in_array($_GET['deletePage'], ["pages/home", "pages/underconstruction"])) {
        $filename = "../resources/parts/" . $_GET['deletePage'] . ".html";
        $bakname = $filename . date("YmdHis") . ".bak";
        if (file_exists($filename)) {
            file_put_contents($bakname, file_get_contents($filename));
            unlink($filename);
        }
    }
}
if (isset($_GET['restore'])) {
    $filename = "../resources/parts/" . $_GET['restore'] . ".html";
    $bakname = "../resources/parts/" . $_GET['backupname'];
    echo $filename;
    file_put_contents($filename, file_get_contents($bakname));
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
            <div class="display-1 fw-bold">Content Pages</div>
        </div>
        <div id="items" class="row">

            <div class="col">
                <div class="fw-bold mb-3">Create New Page</div>
                <form id="newPage">
                    <div class="input-group">
                        <input type="text" id="newpagename" name="pagename" class="form-control"
                            placeholder="new page name" required>
                        <button class="btn btn-success fw-bold">New</button>
                    </div>
                </form>
            </div>
            <div class="col">
                <div class="fw-bold mb-3">Edit Page Html</div>
                <form>
                    <?php
                    $contentpages = glob("../resources/parts/pages/*.html");
                    foreach ($contentpages as $key => $value) {
                        $pagelink = "pages/" . str_replace(".html", "", basename($value));
                        $dd = !in_array($pagelink, ["pages/home", "pages/underconstruction"]) ? "" : "disabled";
                        echo "
                        <div class=\"p-3\">
                            <div class=\"d-flex justify-content-between\">
                                <a href=\"?pluginpage=grapesjs&pagename=$pagelink\">$pagelink</a>
                                <button name=\"deletePage\" value=\"$pagelink\" class=\"btn btn-danger fw-bold $dd\">X</button>
                            </div>
                        </div>
                        ";

                    }
                    ?>
                    <input type="hidden" name="pluginpage" value="content">
                </form>

            </div>



        </div>
        <datalist id="pages">
            <?php
            foreach (glob("../resources/parts/pages/*.html") as $page) {
                $link = "/" . str_replace(".html", "", basename($page));
                echo "<option value=\"$link\">$link</option>";
            }
            echo ""
                ?>
        </datalist>
    </div>
    <div class="border container">
        <div class="fw-bold mb-3">Restore Page</div>
        <?php
        $contentpages = glob("../resources/parts/pages/*.bak");
        $files = [];
        foreach ($contentpages as $key => $value) {
            $pagelin = "pages/" . basename($value);
            $explo = explode(".html", $pagelin);
            $pagelink = $explo[0];
            $bakdate = str_replace(".bak", "", $explo[1]);
            $actualDate = date("Y-m-d H:i:s", strtotime($bakdate));
            $files[$pagelink][] = [
                $pagelin,
                $actualDate
            ];
        }

        foreach ($files as $key => $value) {

            echo "<details class=\"pb-3\">";
            echo "<summary class=\"form-select\">";
            echo $key;
            echo "</summary>";
            echo "<div>";
            $t = 0;
            foreach ($value as $k => $v) {
                $backfilename = $v[0];
                $dates = $v[1];
                if ($t < 10) {
                    echo "
                <form>
                    <div class=\"p-3\">
                        <div class=\"d-flex justify-content-between\">
                            <span>$key</span>
                            <span>$dates</span>
                            <input type=\"hidden\" name=\"backupname\" value=\"$backfilename\">
                            <button name=\"restore\" value=\"$key\" class=\"btn btn-warning fw-bold\">Restore</button>
                        </div>
                    </div>
                    <input type=\"hidden\" name=\"pluginpage\" value=\"content\">
                </form>
                    ";
                } else {
                    unlink($webroot . "/resources/parts/" . $backfilename);
                }

                $t++;

            }
            echo "<div>";
            echo "</details>";

        }



        ?>


    </div>


    <script>

        $(document).on("submit", "#newPage", function (event) {
            console.log("Check");
            event.preventDefault();
            console.log("/setup/?pluginpage=grapesjs&pagename=pages/" + $("#newpagename").val());
            window.location = "/setup/?pluginpage=grapesjs&pagename=pages/" + $("#newpagename").val();
        })

    </script>
</body>

</html>