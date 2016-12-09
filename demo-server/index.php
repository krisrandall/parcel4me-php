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
    // This is a bare bones demo implementation,
    // and empty shell to be filled out for a real shopping cart
    // we also have a few different dummy-profiles that can be switched in here :

    //require __DIR__.'/dummy-profiles/not-logged-in.php';
    require __DIR__.'/dummy-profiles/demo-user-1.php';

    class DemoShop extends P4M\HostServer\P4M_Shop {

        function userIsLoggedIn() {
            return $testProfileData['loggedIn'];            
        }

        function getConsumerFromLocalUser($user) {
            $consumer = array( 
                            'Id'                        =>	'', /*	 (read only) Assigned by P4M */
                            'Locale'                    =>	'', /*	 (read only) Identifies where the consumer's data is stored */
                            'Salutation'                =>  '', /*	 Mr, Ms, etc */
                            'GivenName'                 => 	$user->first,
                            'MiddleName'                =>	'',	 
                            'FamilyName'                =>	$user->last,	 
                            'Email'                     =>  $user->email,
                            'MobilePhone'               =>  '',
                            'PreferredCurrency'         => '', /* "GBP", "EUR", etc */
                            'Language'                  => '', /* "en", "fr", "de", etc */
                            'DOB'                       => '', /* date , not string */
                            'Gender'                    => '',
                            'Height'                    => '',
                            'Weight'                    => '',
                            'Waist'                     => '',
                            'PreferredCarriers'         => '', /* (read only) */
                            'PrefDeliveryAddressId'     => '', /* links to the addresses array below ? */
                            'BillingAddressId'          => '', 
                            'DefaultPaymentMethodId'    => '', 
                            'DeliveryPreferences'       => '', /* useMyDeliveryAddress, useMyDropPoints, useRetailerDropPoint */
                            'PreferSoonestDelivery'     => true,
                            'ProfilePicUrl'             => '', 
                            'ProfilePicHash'            => '', /* Can be used to check if the consumer's profile pic has changed */
                            'Addresses'                 => array (
                                // see : http://developer.parcelfor.me/docs/documentation/api-integration/models/address/
                            ),
                            'PaymentMethods'            => array ( /* (read only) */
                            ),
                            'Extras'                    => '' /* The Extras field contains a list of key/value pairs specific to the calling Retailer, and is available for them to store any additional information that may be needed */
            );

            return $consumer;
        }

        function getRecentCart($user) {

            $cart = array( 
                /*
                ConsumerId	String (read only)	 
                Id	String (read only) 	 
                SessionId	String	Consumer's session Id on retailer's site
                RetailerId	String (read only)	 
                RetailerName	String (read only)	 
                Reference	String	Retailer reference (usually order no.)
                AddressId	String 	see notes
                BillingAddressId	String 	see notes
                Date	UTC date	 
                Currency	String	ISO currency code
                ShippingAmt	Double	Calculated by retailer
                Tax	Double	Calculated by retailer
                Total	Double	 
                ServiceId	String	Shipping service Id
                ServiceName	String	Shipping service name e.g. standard, next day, etc
                ExpDeliveryDate	UTC date	 
                DateDelivered	UTC date	 
                Carrier	String 	 
                ConsignmentId	String 	 
                CarrierToken	String 	Used to grant access to the carrier of the delivery
                Status	String	Ordered, Despatched, etc
                RetailerRating	Integer	 Consumer's rating of the purchase
                CarrierRating	Integer	 Consumer's rating of the delivery
                PaymentType	String	"DB" or "PA" (see notes)
                PayMethodId	String	 The selected card token used for payment
                PaymentId	String (read only)	 P4M transaction Id
                AuthCode	String (read only)	 Used in back office transactions
                PurchaseConfirmedTS	UTC date (read only)	 Date and time purchased was confirmed by the PSP
                Items	List (CartItems) 	 
                Dicounts	List (Discounts)	 
                */
            );

            return $cart;
        }

        function localErrorPageUrl($message) {
            return $_SERVER['HTTP_HOST'] + '/error/' + urlencode($message);
        }

    }


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
                '/p4m/getP4MAccessToken',
                '/p4m/isLocallyLoggedIn',
                '/p4m/localLogin?currentPage',
                '/p4m/restoreLastCart'
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

    });


    // Define routes
    // as per : http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/host-server/
    // and : https://github.com/ParcelForMe/p4m-demo-shop/blob/master/OpenOrderFramework/Controllers/P4MTokenController.cs
    // Subrouting

    // Dynamic route: /hello/name
    $router->get('/p4m/(\w+)', function ($name) {
        echo 'Hello ' . htmlentities($name);
    });


    // Dynamic route: /hello/name
    $router->get('/error/(\w+)', function ($msg) {
        echo 'Error: ' . htmlentities($msg);
    });
    $router->run();


?>