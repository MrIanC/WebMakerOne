<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);
include($_SERVER['DOCUMENT_ROOT'] . "/setup/path.php");
$aiapikeydir = dirname($usersPath)."/plugins";
$apifile =  "$aiapikeydir/ai.php";
if (!is_dir($aiapikeydir)) {
    mkdir($aiapikeydir,0777);
}


if (isset($_POST['aiapikey'])) {
    $filename = $apifile;
    $settings = [
        "apiKey" => $_POST['aiapikey']
    ];
    file_put_contents($filename, "<?php". json_encode($settings, JSON_PRETTY_PRINT));
}

$filename = $apifile;
$apikey = "PASTE YOUR API KEY HERE";
if (file_exists($filename)) {
    $settings = json_decode(str_replace("<?php","",file_get_contents($filename)), true);
    if (isset($settings['apiKey'])) {
        $apikey = $settings['apiKey'];
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
            <div class="display-1 fw-bold">AI API Key</div>
        </div>
        <div class="row">
            <div class="col">
                <p>To use the AI tools in the editor an api key is needed from <a
                        href="https://aistudio.google.com/app/apikey">Google Ai Studio</a>.
            </div>
            </p>

            <div class="col">
                <form method="post">
                    <div class="fw-bold">Google AI Studio API Key</div>
                    <input class="form-control mb-3" type="password" name="aiapikey" value="<?php echo $apikey; ?>">
                    <button class="btn btn-primary">Save</button>

                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on("mousedown","input",function(){
            $(this).attr("type","text");
        })
        $(document).on("mouseup mouseout","input",function(){
            $(this).attr("type","password");
        })
    </script>
</body>

</html>