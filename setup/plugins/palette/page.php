<?php
ini_set(option: 'display_errors', value: 1);
error_reporting(error_level: E_ALL);

$settings = (file_exists(filename: __DIR__ . '/settings.json')) ? json_decode(json: file_get_contents(filename: __DIR__ . '/settings.json'), associative: true) : [];

if (!empty($_GET['bs-custom-light']) && !empty($_GET['bs-custom-dark'])) {

} else {
    if (isset($settings['colors']['bs-custom-light']) && isset($settings['colors']['bs-custom-dark'])) {
        $_GET = $settings['colors'];
    }
}
if (!empty($_GET['bs-custom-light']) && !empty($_GET['bs-custom-dark'])) {
    $settings['colors'] = $_GET;
    file_put_contents(filename: __DIR__ . '/settings.json', data: json_encode(value: $settings, flags: JSON_PRETTY_PRINT));
}
function darkenColor($hexColor, $percent): string
{
    // Remove the hash (#) if present
    $hexColor = str_replace(search: '#', replace: '', subject: $hexColor);

    // If the color is in shorthand (3 characters), convert to full form
    if (strlen(string: $hexColor) == 3) {
        $hexColor = $hexColor[0] . $hexColor[0] . $hexColor[1] . $hexColor[1] . $hexColor[2] . $hexColor[2];
    }

    // Convert the hex color to RGB
    $r = hexdec(hex_string: substr(string: $hexColor, offset: 0, length: 2)); // Red
    $g = hexdec(hex_string: substr(string: $hexColor, offset: 2, length: 2)); // Green (corrected)
    $b = hexdec(hex_string: substr(string: $hexColor, offset: 4, length: 2)); // Blue (corrected)

    // Darken each color component by the percentage
    $r = max(0, min(255, $r * $percent));
    $g = max(0, min(255, $g * $percent));
    $b = max(0, min(255, $b * $percent));

    // Convert back to hex and return the result
    return '#' . sprintf("%02x%02x%02x", $r, $g, $b);
}

// Function to generate Bootstrap button class with custom colors
ini_set('display_errors', 1);
error_reporting(E_ALL);

$light = $_GET['bs-custom-light'] ?? "#FFFFFF";
$dark = $_GET['bs-custom-dark'] ?? "#000000";

$lightrgb = hexdec(substr($light, 1, 2)) . ", " . hexdec(substr($light, 3, 2)) . ", " . hexdec(substr($light, 5, 2));
$darkrgb = hexdec(substr($dark, 1, 2)) . ", " . hexdec(substr($dark, 3, 2)) . ", " . hexdec(substr($dark, 5, 2));


$css = "
    :root {
        --gen-primary-rgb: $lightrgb;
        --gen-secondary-rgb: $darkrgb;
        --gen-primary: $light;
        --gen-secondary: $dark;
    }

    .text-custom-light {
        --bs-text-opacity: 1;
        color: rgba(var(--gen-primary-rgb), var(--bs-text-opacity)) !important;
    }

    .text-custom-dark {
        --bs-text-opacity: 1;
        color: rgba(var(--gen-secondary-rgb), var(--bs-text-opacity)) !important;
    }

    .bg-custom-light {
        --bs-bg-opacity: 1;
        background-color: rgba(var(--gen-primary-rgb), var(--bs-bg-opacity)) !important;
    }

    .bg-custom-dark {
        --bs-bg-opacity: 1;
        background-color: rgba(var(--gen-secondary-rgb), var(--bs-bg-opacity)) !important;
    }

    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }

    .bg-opacity-25 {
        --bs-bg-opacity: 0.25;
    }

    .bg-opacity-50 {
        --bs-bg-opacity: 0.5;
    }

    .bg-opacity-75 {
        --bs-bg-opacity: 0.75;
    }

    .bg-opacity-100 {
        --bs-bg-opacity: 1;
    }

    .btn-custom-light {
        --bs-btn-color: #000;
        --bs-btn-bg: $light;
        --bs-btn-border-color: $light;
        --bs-btn-hover-color: #000;
        --bs-btn-hover-bg: " . darkenColor($light, 0.85) . ";
        --bs-btn-hover-border-color: " . darkenColor($light, 0.85) . ";
        --bs-btn-focus-shadow-rgb: 49, 132, 253;
        --bs-btn-active-color: #000;
        --bs-btn-active-bg: " . darkenColor($light, 0.85) . ";
        --bs-btn-active-border-color: " . darkenColor($light, 0.85) . ";
        --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
        --bs-btn-disabled-color: #aaa;
        --bs-btn-disabled-bg: " . darkenColor($light, 1.5) . ";
        --bs-btn-disabled-border-color: " . darkenColor($light, 1.5) . ";
    }
    .btn-custom-dark {
        --bs-btn-color: #fff;
        --bs-btn-bg: $dark;
        --bs-btn-border-color: $dark;
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: " . darkenColor($dark, 1.25) . ";
        --bs-btn-hover-border-color: " . darkenColor($dark, 1.25) . ";
        --bs-btn-focus-shadow-rgb: 49, 132, 253;
        --bs-btn-active-color: #fff;
        --bs-btn-active-bg: " . darkenColor($dark, 1.25) . ";
        --bs-btn-active-border-color: " . darkenColor($dark, 1.25) . ";
        --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
        --bs-btn-disabled-color: #aaa;
        --bs-btn-disabled-bg: " . darkenColor($dark, 1.5) . ";
        --bs-btn-disabled-border-color: " . darkenColor($dark, 1.50) . ";
    }
    .nav-custom-light {
        --bs-nav-link-color:  $light;
        --bs-nav-link-hover-color: " . darkenColor($light, 0.55) . ";
    }
    .nav-custom-dark {
        --bs-nav-link-color:  $dark;
        --bs-nav-link-hover-color: " . darkenColor($dark, 1.45) . ";
    }
";


file_put_contents("../resources/css/color.css", $css);

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
    <script id="jsonld" type="application/ld+json"></script>
    <style>
        <?php echo $css; ?>
    </style>
</head>

<body>
    <?php include "menu.php"; ?>
    <div class="container">
        <div class="text-center">
            <h2 class="display-1 fw-bold">Palette</h2>
        </div>
        <form>
            <div class="p-3 m-3 border rounded shadow">
                <div class="display-4 border-bottom mb-3">Custom Light color</div>
                <div class="row">
                    <div class="col">
                        <a href="#" class="btn btn-custom-light m-3">btn-custom-light</a>
                        <a href="#" class="disabled btn btn-custom-light m-3">disabled btn-custom-light</a>
                        <div class="bg-custom-light">bg-custom-light</div>
                        <div class="text-custom-light">text-custom-light</div>
                    </div>
                    <div class="col">
                        <div>Custom Color Light</div>
                        <input type="color" name="bs-custom-light" value="<?php echo $light ?>">
                    </div>
                </div>
            </div>
            <div class="p-3 m-3 border rounded shadow">
                <div class="display-4 border-bottom mb-3">Custom Dark color</div>
                <div class="row">
                    <div class="col">
                        <a href="#" class="btn btn-custom-dark m-3">btn-custom-dark</a>
                        <a href="#" class="disabled btn btn-custom-dark m-3">disabled btn-custom-dark</a>
                        <div class="bg-custom-dark">bg-custom-dark</div>
                        <div class="text-custom-dark">text-custom-dark</div>
                    </div>
                    <div class="col">
                        <div>Custom Color Dark</div>
                        <input type="color" name="bs-custom-dark" value="<?php echo $dark ?>">
                    </div>
                </div>
            </div>

            <div class="text-start py-3">

                <input class="btn btn-primary" type="submit" value="submit">
            </div>
            <input type="hidden" name="pluginpage" value="palette">
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>