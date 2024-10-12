<?php

ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

$pagename = $_POST['page'];
$html = $_POST['html'];
$css = $_POST['css'];

$CssRules = [];
// Get rid of #tags that don't belong
$whitespace = ["\t", "\n", " ", "\r"];
foreach (explode("}", $css) as $key => $value) {
    if (!empty($value)) {
        $rule = trim(str_replace($whitespace, "", $value) . "}");
        $selector = explode("{", $rule)[0];
        if ($selector[0] == "#")
        $CssRules[$selector] = $rule;
    }
}

$css = "";
foreach ($CssRules as $CssSelector => $value) {
    if (str_contains($html, ltrim($CssSelector, "#"))) {
        $css .= $value . "\n";
    }
}

$rootpath = str_replace("/setup/plugins", "", realpath("../"));

$filename = "$rootpath/resources/parts/$pagename.html";
echo $filename . "\n";
if (file_exists($filename)) {
    file_put_contents($filename . date("YmdHis") . ".bak", file_get_contents($filename));
}

$full = str_replace(["<body>", "</body>"], "", $html) . "<style>$css</style>";
file_put_contents($filename, $full);
echo "saved" . "\n";


if (str_contains($pagename, "pages")) {
    echo "Is Page " . basename($pagename) . "\n";
    $folder = basename($pagename);
    if (is_dir("$rootpath/$folder")) {
        echo "Dir Exists" . "\n";
        file_put_contents("$rootpath/$folder/index.html", file_get_contents("$rootpath/index.html"));
    } else {
        echo "Dir not found" . "\n";
        if (mkdir("$rootpath/$folder", 0777, true)) {
            echo "Created";
        } else {
            echo "mkdir Failded";
        }

        ;
        file_put_contents("$rootpath/$folder/index.html", file_get_contents("$rootpath/index.html"));
    }
}