<?php 

    // Require composer autoloader
    require_once __DIR__.'/vendor/autoload.php';

    // Require the P4M Shop Abstract Class
    require_once __DIR__.'/../parcel4me/p4m-shop.php';


    /*       
        DDDDDDDDDDDDD                                                                     
        D::::::::::::DDD                                                                  
        D:::::::::::::::DD                                                                
        DDD:::::DDDDD:::::D                                                               
        D:::::D    D:::::D     eeeeeeeeeeee       mmmmmmm    mmmmmmm      ooooooooooo   
        D:::::D     D:::::D  ee::::::::::::ee   mm:::::::m  m:::::::mm  oo:::::::::::oo 
        D:::::D     D:::::D e::::::eeeee:::::eem::::::::::mm::::::::::mo:::::::::::::::o
        D:::::D     D:::::De::::::e     e:::::em::::::::::::::::::::::mo:::::ooooo:::::o
        D:::::D     D:::::De:::::::eeeee::::::em:::::mmm::::::mmm:::::mo::::o     o::::o
        D:::::D     D:::::De:::::::::::::::::e m::::m   m::::m   m::::mo::::o     o::::o
        D:::::D     D:::::De::::::eeeeeeeeeee  m::::m   m::::m   m::::mo::::o     o::::o
        D:::::D    D:::::D e:::::::e           m::::m   m::::m   m::::mo::::o     o::::o
        DDD:::::DDDDD:::::D  e::::::::e          m::::m   m::::m   m::::mo:::::ooooo:::::o
        D:::::::::::::::DD    e::::::::eeeeeeee  m::::m   m::::m   m::::mo:::::::::::::::o
        D::::::::::::DDD       ee:::::::::::::e  m::::m   m::::m   m::::m oo:::::::::::oo 
        DDDDDDDDDDDDD            eeeeeeeeeeeeee  mmmmmm   mmmmmm   mmmmmm   ooooooooooo   
                                                                                                                          
        _________ _______  _______  _        _______  _______  _______  _       _________ _______ __________________ _______  _       
        \__   __/(       )(  ____ )( \      (  ____ \(       )(  ____ \( (    /|\__   __/(  ___  )\__   __/\__   __/(  ___  )( (    /|
           ) (   | () () || (    )|| (      | (    \/| () () || (    \/|  \  ( |   ) (   | (   ) |   ) (      ) (   | (   ) ||  \  ( |
           | |   | || || || (____)|| |      | (__    | || || || (__    |   \ | |   | |   | (___) |   | |      | |   | |   | ||   \ | |
           | |   | |(_)| ||  _____)| |      |  __)   | |(_)| ||  __)   | (\ \) |   | |   |  ___  |   | |      | |   | |   | || (\ \) |
           | |   | |   | || (      | |      | (      | |   | || (      | | \   |   | |   | (   ) |   | |      | |   | |   | || | \   |
        ___) (___| )   ( || )      | (____/\| (____/\| )   ( || (____/\| )  \  |   | |   | )   ( |   | |   ___) (___| (___) || )  \  |
        \_______/|/     \||/       (_______/(_______/|/     \|(_______/|/    )_)   )_(   |/     \|   )_(   \_______/(_______)|/    )_)  
    */

    // Set Config

    P4M\HostServer\Settings::setProtected('OpenIdConnectClientId',      '10004');
    P4M\HostServer\Settings::setProtected('OpenIdConnectClientSecret',  'secret');


    // This is a bare bones demo implementation,
    // an empty shell to be filled out for a real shopping cart

    class DemoShop extends P4M\HostServer\P4M_Shop {

        function userIsLoggedIn() {
            //return false;
            return true;
        }

        function getConsumerFromLocalUser($user) {
            $consumer = new P4M\HostServer\Models\Consumer();
            $consumer->GivenName  = $user->first;
            $consuemr->FamilyName = $user->last;
            $consumer->Email      = $user->email;
            return $consumer;
        }

        function getRecentCart($user) {
            $cart = new P4M\HostServer\Models\Cart();
            /*
                some logic goes here to fetch my cart from 
                my shopping cart DB and put the details into 
                this $cart object 
            */
            return $cart;
        }

        function localErrorPageUrl($message) {
            return $_SERVER['HTTP_HOST'] + '/error/' + urlencode($message);
        }

    }


    /// Define the Instance :
    $my_shopping_cart = new DemoShop();
    /*
$usr = new stdClass();
$usr->first = 'f';
$usr->last='l';
$usr->email='e@mail.com';
$x = ( $my_shopping_cart->getConsumerFromLocalUser( $usr ) );
var_dump($x);
    */

    /*
        _______  _______          _________ _______  _______ 
        (  ____ )(  ___  )|\     /|\__   __/(  ____ \(  ____ )
        | (    )|| (   ) || )   ( |   ) (   | (    \/| (    )|
        | (____)|| |   | || |   | |   | |   | (__    | (____)|
        |     __)| |   | || |   | |   | |   |  __)   |     __)
        | (\ (   | |   | || |   | |   | |   | (      | (\ (   
        | ) \ \__| (___) || (___) |   | |   | (____/\| ) \ \__
        |/   \__/(_______)(_______)   )_(   (_______/|/   \__/
    */

    // In case one is using PHP 5.4's built-in server
    $filename = __DIR__ . '/../' . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if (php_sapi_name() === 'cli-server' && is_file($filename)) {
        return false;
    }

    // Create a Router
    $router = new \Bramus\Router\Router();


    // Custom 404 Handler
    $router->set404(function () {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo '404, route not found!';
    });


    // Static route: / (homepage)
    $router->get('/', function () {

        $supportedEndPoints = array( 
                '/p4m/signup',
                '/p4m/getP4MAccessToken',
                '/p4m/isLocallyLoggedIn',
                '/p4m/localLogin?currentPage',
                '/p4m/restoreLastCart',
                '/error/(message)'
        );
        echo '<h1>p4m-server</h1>
             <p>Try these routes:<p>
             <ul>';
        foreach($supportedEndPoints as $endPoint) {
            echo '<li>
                   <a href="'.$endPoint.'">'.$endPoint.'</a>
                 </li>';
        }
        echo '</ul>';

        echo '<h1>Cart UI P4M widgets :</h1>';
        echo 'todo..';
        
    });


    // Define routes
    // as per : http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/host-server/
    // and : https://github.com/ParcelForMe/p4m-demo-shop/blob/master/OpenOrderFramework/Controllers/P4MTokenController.cs
    // Subrouting

    // Dynamic route: p4m/*
    $router->get('/p4m/(\w+)', function ($p4mEndpoint) {

        global $my_shopping_cart;

        switch($p4mEndpoint) {

            case 'signup' :
                $my_shopping_cart->signUp();
                break;

            default:
                echo 'Hello unhandled endpoint : ' . htmlentities($p4mEndpoint);

        }
        
    });


    // Dynamic route: /hello/name
    $router->get('/error/(.*)', function ($msg) {
        echo 'Error: ' . htmlentities($msg);
    });
    $router->run();


?>