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
        //error_log("somethingWentWrong(" . $message . ") ". $this->localErrorPageUrl($message));
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
            // CURLOPT_PORT => "44321",
            CURLOPT_URL => P4M_Shop_Urls::endPoint('registerConsumer'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $consumerAndCartMessage,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: " . $clientCredentials->token_type . ' ' . 
                                    $clientCredentials->access_token,
                "cache-control: no-cache",
                "content-type: application/json"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->somethingWentWrong("cURL Error #:" . $err);
        } else {

            $rob = new \stdClass();
            $rob = json_decode($response);

            if ( (!is_object($rob)) || (!property_exists($rob, 'Success')) ) {

                $this->somethingWentWrong("Error registering with P4M : No 'Success' property of response received");

            } elseif (!$rob->Success) {

/*
TODO :

if (registerResult.Error.Contains("registered"))
      redirectUrl = idSrvUiUrl+"alreadyRegistered?firstName={consumer.GivenName}&email={consumer.Email}"
      Redirect(redirectUrl)
else

*/

                $this->somethingWentWrong("Error registering with P4M : " . $rob->Error);

            } else {

                echo "HOORAY!!";
                echo $response;

/*
TODO :

    redirectUrl = idSrvUiUrl+"registerConsumer/{registerResult.ConsumerId}"
    Redirect(redirectUrl)

*/

            }
        }


/*
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
*/

    }


    public function getP4MAccessToken() {

        echo "test!";
        /*

        TO DO : http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/getp4maccesstoken/#

        */
    }

}



?>