<?php
//include __DIR__ . "/authenticate.php";
if (isset($_GET['pagename'])) {
    if ($_GET['pagename'] == str_replace(" ", "-", $_GET['pagename'])) {

    } else {
        header("location: ?pagename=" . str_replace(" ", "-", $_GET['pagename']));
    }


}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>GrapesJS Editor</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.13/css/grapes.min.css"
        integrity="sha512-wt37la6ckobkyOM0BBkCvrv+ozN/tGRe5BtR8DtGuxZ+m9kIy8B9hb8iLpzdrdssK2N07EMG7Tsw+/6uulUeyg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.13/grapes.min.js"
        integrity="sha512-vnAsqCtkvU3XqbVNK0pQQ6F8Q98PDpGMpts9I4AJdauEZQVbqZGvJdXfvdKEnLC4o7Z1YfnNzsx+V/+NXo/08g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <script src="/setup/grapejs/bs.js"></script>
    <script src="/setup/grapejs/grapejs-plugin-ai.js"></script>
    <script src="/setup/grapejs/grapejs-plugin-bs-icons.js"></script>
    <script src="/setup/grapejs/grapejs-plugin-data.js"></script>
    <script src="/setup/grapejs/grapejs-plugin-aos.js"></script>
    <script src="/setup/grapejs/grapejs-plugin-forms.js"></script>


    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }
    </style>
</head>

<body>

    <div id="gjs" style="height:0px; overflow:hidden">
        <?php
        if (isset($_GET['pagename'])) {
            $_GET['pagename'] = str_replace(" ", "-", $_GET['pagename']);
        }
        if (isset($_GET['pagename']) && !empty($_GET['pagename'])) {
            if (file_exists("../resources/parts/{$_GET['pagename']}.html")) {
                echo file_get_contents("../resources/parts/{$_GET['pagename']}.html");
            }
        }
        ?>
    </div>

    <script type="text/javascript">
        window.onload = () => {
            window.editor = grapesjs.init({
                height: '100%',
                showOffsets: true,
                noticeOnUnload: false,
                storageManager: false,
                container: '#gjs',
                fromElement: true,
                canvas: {
                    styles: [
                        "https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css",
                        "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css",
                        "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css",
                        "/resources/css/color.css",
                        "/resources/css/fonts.css",
                        "/resources/css/images.css",
                    ]
                },
                assetManager: {
                    // Disable uploading images as base64
                    upload: false,
                    uploadText: 'Upload your image here',
                    // Allow selecting images from a URL instead
                    assets: [
                        <?php
                        foreach (glob("../uploads/*") as $uploads) {
                            $filename = basename($uploads);
                            echo "'/uploads/$filename',";
                        }
                        ?>
                    ],
                },
                domComponents: {
                    type: 'image',
                    model: {
                        defaults: {
                            traits: [
                                {
                                    type: 'text',
                                    label: 'Image URL',
                                    name: 'src',
                                    placeholder: 'https://...',
                                    changeProp: 1,
                                },
                            ],
                        },
                    },
                },
                //'grapejs-plugin-aos'
                plugins: [,'grapejs-plugin-data', 'grapejs-plugin-ai', 'bs-class-selector', 'grapejs-plugin-forms', 'grapejs-plugin-bs-icons'],
                pluginsOpts: {
                    'bs-class-selector': {},
                    'grapejs-plugin-forms': {},
                    'grapejs-plugin-ai': {},
                    'grapejs-plugin-bs-icons': {},
                    'grapejs-plugin-data': {}


                },
            });

            const panelManager = editor.Panels;
            const commands = editor.Commands;


            panelManager.addButton('options', {
                id: 'back',
                className: 'fa fa-home',
                command: 'backtoMain',
                attributes: { title: 'Back To main' },
                active: false,
            });

            commands.add('backtoMain', {
                run(editor, sender) {
                    window.location = "/setup/index.php";
                },
            });


            panelManager.addButton('options', {
                id: 'myNewButton',
                className: 'fa fa-save',
                command: 'someCommand',
                attributes: { title: 'Some title' },
                active: false,
            });

            commands.add('someCommand', {
                run(editor, sender) {
                    const html = editor.getHtml();
                    const css = editor.getCss();
                    const page = '<?php echo $_GET['pagename'] ?? "new" ?>';
                    jQuery.ajax({
                        url: '<?php echo $thisDirJS; ?>/savepage.php',
                        type: 'POST',
                        data: {
                            html: html,
                            css: css,
                            page: page
                        },
                        success: function (response) {
                            console.log(response);
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                },
            });








            <?php echo allBlocks() ?>

            editor.on('block:drag:stop', (component) => {
                console.log(component);
            });


        }


    </script>

</body>

</html>

<?php
function allBlocks()
{
    foreach (glob("../setup/templates/*/*.html") as $filename) {
        $category = str_replace(["../setup/templates/", basename($filename), "/"], "", $filename);
        $blockname = str_replace(".html", "", basename($filename));
        $label = (file_exists(str_replace(".html", ".svg", $filename))) ? file_get_contents(str_replace(".html", ".svg", $filename)) : $blockname;
        $blockid = $category . "-" . str_replace(".html", "", basename($filename));
        $response = file_get_contents($filename);
        $rrr[] = "
            editor.BlockManager.add('$blockid', {
                id: '$blockid',
                label: `$label`,
                content: `$response`,
                category: {
                    label: '$category',
                    open: false 
                },
            })
    ";
    }


    $tmp = "";
    $json = json_decode(file_get_contents("../resources/json/business.json"), true);
    foreach ($json['address'] as $v) {
        $tmp .= '<div>' . $v . '</div>';
    }

    $blockid = "data-address";
    $blockname = "data-address";
    $category = "Site Data";
    $response = "<div>$tmp</div>";
    $rrr[] = "
    editor.BlockManager.add('$blockid', {
        id: '$blockid',
        label: '$blockname',
        content: `$response`,
        category: {
            label: '$category',
            open: false 
        },
    })
    ";

    $tmp = "";
    $tmp = '<div>Telephone</div><div>' . $json['telephone'] . '</div>';
    $blockid = "data-telephone";
    $blockname = "data-telephone";
    $category = "Site Data";
    $response = "<div>$tmp</div>";
    $rrr[] = "
    editor.BlockManager.add('$blockid', {
        id: '$blockid',
        label: '$blockname',
        content: `$response`,
        category: {
            label: '$category',
            open: false 
        },
    })
    ";


    echo implode($rrr);


}
?>