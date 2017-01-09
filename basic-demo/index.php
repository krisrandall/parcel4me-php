<head>
    <title>P4M Shell</title>

    <script src="./basic-demo/lib/webcomponentsjs/webcomponents.min.js"></script>
    <link rel="import" href="./basic-demo/lib/p4m-widgets/p4m-login/p4m-login.html" />

    <link rel="import" href="./basic-demo/lib/p4m-widgets/p4m-register/p4m-register.html">

</head>


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

    P4M\Settings::setPublic('OpenIdConnect:ClientId',     '10004');
    P4M\Settings::setPublic('OpenIdConnect:ClientSecret', 'secret');
    P4M\Settings::setPublic('OpenIdConnect:RedirectUrl',  'http://localhost:8000/p4m/getP4MAccessToken');


    // This is a bare bones demo implementation,
    // an empty shell to be filled out for a real shopping cart

    class DemoShop extends P4M\P4M_Shop {

        function userIsLoggedIn() {
            //return false;
            return true;
        }

        function getConsumerFromCurrentUser() {
            /* 
                some logic goes here to fetch the 
                details of the current user 
            */
            $user = new stdClass();
            $user->first = 'First';
            $user->last  = 'Last';
            $user->email = 'new_person@mailinator.com';

            
            $p4m_address = new P4M\Models\Address();
            $p4m_address->AddressType   = 'Address';
            $p4m_address->Street1       = '21 Pine Street';
            $p4m_address->State         = 'Qld';
            $p4m_address->CountryCode   = 'AU';
            $p4m_address->removeNullProperties();

            // Convert the user from the shopping cart DB into a 
            // P4M Consumer
            $consumer = new P4M\Models\Consumer();
            $consumer->GivenName  = $user->first;
            $consumer->FamilyName = $user->last;
            $consumer->Email      = $user->email;
            $consumer->Addresses  = array ( $p4m_address ); 
            $consumer->removeNullProperties();

            return $consumer;
        }

        function getCartOfCurrentUser() {
            /*
                some logic goes here to fetch my cart from 
                my shopping cart DB and put the details into 
                this $cart object 
            */

            // Convert the shopping cart from the shopping cart DB into a 
            // P4M Cart
            $cart = new P4M\Models\Cart();
            return $cart;
        }

        function localErrorPageUrl($message) {
            return 'http://' . $_SERVER['HTTP_HOST'] . '/error/' . urlencode($message);
        }

    }


    /// Define the Instance :
    $my_shopping_cart = new DemoShop();



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
                '/error/(message)'
        );
        echo '<h1>p4m-server API</h1>
              These end points must be implemented in a shopping cart for it to use the Parcel4Me one-click checkout and delivery.

              <p><p>
              <ul>';
        foreach($supportedEndPoints as $endPoint) {
            echo '<li>
                   <a href="'.$endPoint.'">'.$endPoint.'</a>
                 </li>';
        }
        echo '</ul>';

        echo '<hr/>
              <h1>Cart UI P4M widgets </h1>
              These UI Widgets should be added to a shopping cart in the approprate places';
        
        echo '<h2>p4m-register</h2>
                <p4m-register></p4m-register>
              <br/>';

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

            case 'getP4MAccessToken' :
                $my_shopping_cart->getP4MAccessToken();
                break;

            default:
                echo 'Hello unhandled endpoint : ' . htmlentities($p4mEndpoint);

        }
        
    });


    // Dynamic route: /error/(message)
    $router->get('/error/(.*)', function ($msg) {
        echo '<h1>Error: <span style="color: red;">' . urldecode(htmlentities($msg)) . '</span></h1>';
    });
    $router->run();


?>