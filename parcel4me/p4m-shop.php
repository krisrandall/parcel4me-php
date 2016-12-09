<?php

namespace P4M\HostServer;

require 'p4m-shop-interface.php';
require 'p4m-urls.php';


abstract class P4M_Shop implements P4M_Shop_Interface
{
    // Your Shopping Cart must implement the following
    abstract public function userIsLoggedIn();
    abstract public function getConsumerFromLocalUser($user);
    abstract public function getRecentCart($user);
    abstract public function localErrorPageUrl($message);


    public function signUp() {
        // PHP version of this psuedo code :
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-register-widget/signup/

        if (!userIsLoggedIn()) {
            $uiUrl = P4M_Shop_Urls.endPoint('signup');
            header('Location: https://www.google.com'); 
            exit();
        }

        //$clientToken = request_Client_Credentials(TokenEndpoint, ClientId, ClientSecret, scope: "p4mRetail")


    }


}

?>