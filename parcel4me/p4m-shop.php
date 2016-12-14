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


    private function somethingWentWrong($message) {
        header("Location: ".$this->localErrorPageUrl($message)); 
        exit();  
    }

    public function signUp() {
        // PHP version of this psuedo code :
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-register-widget/signup/

        if (!$this->userIsLoggedIn()) {
            $uiUrl = P4M_Shop_Urls::endPoint('signup');
            header("Location: {$uiUrl}"); 
            exit();
        }

        $clientCredentials = false;


        // shm_remove(\shm_attach(SHM_IDENTIFIER)); // DEBUGGING ONLY !


        // do we have a stored client ?
        if (\shm_has_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken)) {
            // is it about to expire ?
            // TODO - check expiry of token
            $clientCredentials = \shm_get_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken);
        }
   

        if (!$clientCredentials) {

            $oidc = new \OpenIDConnectClient(P4M_Shop_Urls::endPoint('base_url'),
                                             Settings::getPublic('OpenIdConnect:ClientId'),
                                             Settings::getPublic('OpenIdConnect:ClientSecret') );
            $oidc->providerConfigParam(array('token_endpoint'=>P4M_Shop_Urls::endPoint('connect_token')));
            $oidc->addScope('p4mRetail');

            $clientCredentials = $oidc->requestClientCredentialsToken();

            // check that it has the properties "access_token" and "token_type"
            if ( (!property_exists($clientCredentials, 'token_type')) ||
                 (!property_exists($clientCredentials, 'access_token')) 
            ) {
                $this->somethingWentWrong('Invalid Client Credentials returned :'.json_encode($clientCredentials));
            }
                 
            /* TODO : check the token is valid */
            //\Firebase\JWT\JWT::decode($clientCredentials->access_token, ** need to get key from OAUTH2 end point **, array('HS256'));

            \shm_put_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken, $clientCredentials);

        }

            echo " HERE THE TOKEN :<pre>";
            var_dump($clientCredentials);
            echo "</pre>";
echo($clientCredentials->access_token);

        // check the token is valid
  //      $decodedToken = \Firebase\JWT\JWT::decode($clientCredentials->access_token, '', array('HS256'));
//echo $decodedToken;
/*

      
*/

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