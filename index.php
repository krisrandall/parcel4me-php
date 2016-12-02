<?php 


    // In case one is using PHP 5.4's built-in server
    $filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if (php_sapi_name() === 'cli-server' && is_file($filename)) {
        return false;
    }

    // Require composer autoloader
    require __DIR__ . '/vendor/autoload.php';

    // Create a Router
    $router = new \Bramus\Router\Router();


    // Custom 404 Handler
    $router->set404(function () {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo '404, route not found!';
    });


    // Static route: / (homepage)
    $router->get('/', function () {
        echo '<h1>p4m-server</h1><p>Try these routes:<p><ul><li>/p4m/getP4MAccessToken</li><li>/p4m/isLocallyLoggedIn</li><li>/p4m/localLogin?currentPage=</li><li>/p4m/restoreLastCart</li></ul>';
    });


    // Define routes
    // as per : http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/host-server/
    // and : https://github.com/ParcelForMe/p4m-demo-shop/blob/master/OpenOrderFramework/Controllers/P4MTokenController.cs
    // Subrouting

    // Dynamic route: /hello/name
    $router->get('/p4m/(\w+)', function ($name) {
        echo 'Hello ' . htmlentities($name);
    });



    $router->run();


?>