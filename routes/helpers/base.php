<?php
use SyntoraPHP\App\Models\Env;

/*
Application helpers
*/

// Load Views
function view($path, $data = [], $javascript = false)
{
    $env = new Env('.env');

    // Replace all . with /
    $path = str_replace('.', '/', $path);

    extract($data);

    // Include views folder path
    $viewPath = 'views/' . $path . '.php';

    // Check if the view file exists
    if (!file_exists($viewPath)) {
        // Handle the error, e.g., throw an exception or show a 404 error
        exit('View does not exist.');
    }

    // Wrap the view rendering code in a buffer
    ob_start();
    include_once $viewPath;
    $viewContent = ob_get_clean();

    // Inject JavaScript if $javascript is true
    if ($javascript) {
        $encodedData = json_encode($data);
        $viewContent = str_replace('<title>', "<script>let SyntoraPHP = JSON.parse('{$encodedData}');</script>\n<title>", $viewContent);
    }

    echo $viewContent;
    exit();
}