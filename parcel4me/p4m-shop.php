<?php

namespace P4M;

// Require composer autoloader
require_once __DIR__.'/vendor/autoload.php';

require 'p4m-shop-interface.php';
require 'p4m-urls.php';
require 'p4m-models.php';
require 'settings.php';

define ('SHM_IDENTIFIER', 1);
define ('SHM_ClientCredentialsToken', 1);


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

        if (!$this->userIsLoggedIn()) {
            $uiUrl = P4M_Shop_Urls::endPoint('signup');
            header("Location: {$uiUrl}"); 
            exit();
        }

        $oidc = false;


        \shm_remove(SHM_IDENTIFIER); // DEBUGGING ONLY ..

        // do we have a stored client ?
        if (\shm_has_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken)) {
            // is it about to expire
            // TODO - check expiry of token
            $oidc = \shm_get_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken);
            echo " existing OKEN :<pre>";
            var_dump($oidc);
            echo "</pre>";


        }
   

        if (!$oidc) {

            $oidc = new \OpenIDConnectClient(P4M_Shop_Urls::endPoint('base_url'),
                                             Settings::getPublic('OpenIdConnect:ClientId'),
                                             Settings::getPublic('OpenIdConnect:ClientSecret') );
            //$oidc->providerConfig(array('token_endpoint'=>P4M_Shop_Urls::endPoint('connect_token')));
            $oidc->addScope('p4mRetail');

            //$oidc->authenticate();

            echo " HERE THE TOKEN :<pre>";
            var_dump($oidc);
            echo "</pre>";



            \shm_put_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken, $oidc);

        }

        //$clientToken = request_Client_Credentials(TokenEndpoint, ClientId, ClientSecret, scope: "p4mRetail")
        // NEXT : PHP Open Id connect
        // should be able to use this : ? https://github.com/jumbojett/OpenID-Connect-PHP

    }

    public function getP4MAccessToken() {

        echo "test!";
        /*

        TO DO : http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/getp4maccesstoken/#

        */
    }

}



?>