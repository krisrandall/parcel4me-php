<?php

namespace P4M;

// Require composer autoloader
require_once __DIR__.'/vendor/autoload.php';

require_once 'HTTP/Request2.php';

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
    abstract public function getConsumerFromCurrentUser();
    abstract public function getCartOfCurrentUser();
    abstract public function localErrorPageUrl($message);



    private function somethingWentWrong($message) {
        error_log("somethingWentWrong("+$message+") :)");
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


        // Obtain a credentials token 

        $clientCredentials = false;

        shm_remove(\shm_attach(SHM_IDENTIFIER)); // Until we code it to expire the token, request a new one every time !

        // do we have a stored client ?
        if (\shm_has_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken)) {
            // is it about to expire ?
            /* TODO - check expiry of token "expires_in" .. but need to also save token fetched time */
            $clientCredentials = \shm_get_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken);
        }
   

        if (!$clientCredentials) {

            $oidc = new \OpenIDConnectClient(P4M_Shop_Urls::endPoint('oauth2_base_url'),
                                             Settings::getPublic('OpenIdConnect:ClientId'),
                                             Settings::getPublic('OpenIdConnect:ClientSecret') );
            $oidc->providerConfigParam(array('token_endpoint'=>P4M_Shop_Urls::endPoint('connect_token')));
            $oidc->addScope('p4mRetail');
            $oidc->addScope('p4mApi');

            $clientCredentials = $oidc->requestClientCredentialsToken();


            // check that it has the properties "access_token" and "token_type"
            if ( (!property_exists($clientCredentials, 'token_type')) ||
                 (!property_exists($clientCredentials, 'access_token')) 
            ) {
                $this->somethingWentWrong('Invalid OAUTH2 Client Credentials returned :'.json_encode($clientCredentials));
            }
                 
            /* TODO : check the token is valid */
            //\Firebase\JWT\JWT::decode($clientCredentials->access_token, ** need to get key from OAUTH2 end point **, array('HS256'));

            \shm_put_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken, $clientCredentials);

        }


        // Get the data to send to signup this consumer 

        $consumer = $this->getConsumerFromCurrentUser();


        $cart = $this->getCartOfCurrentUser();


        $consumerAndCartMessage = json_encode( array (

                'Consumer'  =>  $consumer,
                'Cart'      =>  $cart

        ));


        // Send the API request 

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => P4M_Shop_Urls::endPoint('registerConsumer'),
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $consumerAndCartMessage,
            CURLOPT_HTTPHEADER      => array(
                                            'Authorization' => $clientCredentials->token_type . ' ' . 
                                                               $clientCredentials->access_token,
                                            'Accept'        => 'application/json',
                                            'Content-Type'  => 'application/json'
                                        )
        ));

        // Send the request & save response to $resp
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $resp = curl_exec($curl);
        $information = curl_getinfo($curl);


echo $consumerAndCartMessage;
echo '<hr>';
        echo '<b>';
        echo P4M_Shop_Urls::endPoint('registerConsumer');
        echo '</b><br>';
        echo $clientCredentials->token_type . '  ' .$clientCredentials->access_token;
echo '<hr>';

        echo $resp;
        echo '<pre>';
        var_dump($information);
        echo '</pre>';
echo '<br>';
var_dump($clientCredentials);


        // Close request to clear up some resources
        curl_close($curl);
    }


    public function getP4MAccessToken() {

        echo "test!";
        /*

        TO DO : http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/getp4maccesstoken/#

        */
    }

}



?>