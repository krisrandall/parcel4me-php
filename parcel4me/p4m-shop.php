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

    // Your Shopping Cart must implement the following :

    abstract public function userIsLoggedIn();
    abstract public function createNewUser( $p4m_consumer );
    abstract public function loginUser( $localUserId );
    abstract public function logoutCurrentUser();
    abstract public function getCurrentUserDetails();
    abstract public function setCurrentUserDetails( $p4m_consumer );
    abstract public function getCartOfCurrentUser();
    abstract public function setCartOfCurrentUser( $p4m_cart );
    abstract public function getCheckoutPageHtml( $replacementParams );
    abstract public function updateShipping( $shippingServiceName, $amount, $dueDate );
    abstract public function getCartTotals();
    abstract public function updateWithDiscountCode( $discountCode );
    abstract public function localErrorPageUrl( $message );



    // Your Shopping Cart may implement the following :

    public function getCurrentSessionId() {
        // this may be overridden if the shopping cart uses a session id other than the PHP session id internally
        return session_id();
    }

    public function goHome() {
        // this may be overriden for the shopping cart to set a different home page
        header("Location: /");
        exit();
    }


    private function somethingWentWrong($message) {
        error_log("somethingWentWrong(" . $message . ") ". $this->localErrorPageUrl($message));
        header("Location: ".$this->localErrorPageUrl($message)); 
        exit();  
    }



    // Internal Class Functions : 


    private $bearerToken;
    private function setBearerToken($token) {
        $this->bearerToken = $token;
    }


    private function apiHttp($method, $endpoint, $data = null) {
        /*
        This does an HTTP request to the API, and calls somethingWentWrong() if the result does not contain a .Success property 
        It passes $this->$bearerToken as the auth header bearer token so call setBearerToken() first
        */

        $curl = curl_init();

        curl_setopt_array($curl, array(
            // CURLOPT_PORT => "44321",
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: " . 'Bearer ' . $this->bearerToken,
                "cache-control: no-cache",
                "content-type: application/json"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->somethingWentWrong("Error calling API : # " . $err . " <!-- ".$endpoint." -->");
        } elseif ($response=='') {
            $this->somethingWentWrong("Error calling API : returned blank (token could be expired)");
        } else {

            $rob = new \stdClass();
            $rob = json_decode($response);

            if ( (!is_object($rob)) || (!property_exists($rob, 'Success')) ) {
                $this->somethingWentWrong("Error calling API : No 'Success' property of response received");
            } 

        }

        // if we are here then the response has a .Success property, 
        // the calling function can check that and handle true or false success results

        return $rob; // return the response as an object  

    }



    // Public functions called by the cart when /p4m/ endpoints are accessed :

    public function signUp() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-register-widget/signup/

        if (!$this->userIsLoggedIn()) {
            $uiUrl = P4M_Shop_Urls::endPoint('signup');
            header("Location: {$uiUrl}"); 
            exit();
        }


        // Obtain a credentials token 

        $clientCredentials = false;

        // TODO - check for existing token ! (see TODO a few lines down also ..)
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
                 

            \shm_put_var(\shm_attach(SHM_IDENTIFIER), SHM_ClientCredentialsToken, $clientCredentials);

        }


        // Get the data to send to signup this consumer 
        $consumer = $this->getConsumerFromCurrentUser();
        $cart     = $this->getCartOfCurrentUser();
        $consumerAndCartMessage = json_encode( array (
                'Consumer'  =>  $consumer,
                'Cart'      =>  $cart
        ));


        // Send the register consumer API request 
        $this->setBearerToken($clientCredentials->access_token);
        $rob = $this->apiHttp('POST',  P4M_Shop_Urls::endPoint('registerConsumer'), $consumerAndCartMessage);

        if (!$rob->Success) {

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


    public function getP4MAccessToken() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/getp4maccesstoken/#

        if ($_COOKIE["p4mState"] != $_REQUEST['state']) {
            $this->somethingWentWrong('Authentication error (p4mState)');
        }

        try {
            $oidc = new \OpenIDConnectClient(P4M_Shop_Urls::endPoint('connect_token'),
                                            Settings::getPublic('OpenIdConnect:ClientId'),
                                            Settings::getPublic('OpenIdConnect:ClientSecret') );
            $oidc->providerConfigParam(array('token_endpoint'=>P4M_Shop_Urls::endPoint('connect_token')));
            $oidc->providerConfigParam(array('jwks_uri'=>P4M_Shop_Urls::endPoint('jwks')));
            $oidc->setProviderURL(P4M_OID_SERVER);
        
            $response = $oidc->authenticate();

            if (!$response) {
                $this->somethingWentWrong('OIDC auth returned false');
            }

        } catch (\OpenIDConnectClientException $oidcE) {
            $this->somethingWentWrong('OIDC Exception :'.$oidcE->getMessage());
        } catch (\Exception $e) {
            $this->somethingWentWrong('Exception doing OIDC auth:'.$e->getMessage());
        }

        // set the p4m cookie for this retailer's site
        $accessToken  = $oidc->getAccessToken();
        $cookieExpire = strtotime('+'.$response->expires_in.' seconds');
        $path         = '/';
        setcookie( "p4mToken",
                   $accessToken,
                   $cookieExpire,
                   $path );

            
        // close this popped up window
        echo '<script>window.close();</script>';

    }


    public function isLocallyLoggedIn() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/islocallyloggedin/

        if ($this->userIsLoggedIn()) {
            
            setcookie( "p4mLocalLogin",
                       true,
                       0,
                       '/' );
            echo '{ "Success": true, "Error": null }';

        } else {

            setcookie( "p4mLocalLogin",
                       false,
                       0,
                       '/' );
            echo '{ "Success": false, "Error": "Not logged in" }';

        }

    }


    public function localLogin() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/locallogin/

        setcookie( "p4mLocalLogin", false, 0, '/' );

        // Send the API request 
        $this->setBearerToken($_COOKIE["p4mToken"]);
        $rob = $this->apiHttp('GET',  P4M_Shop_Urls::endPoint('consumer'));

        if (!$rob->Success) {
            $this->somethingWentWrong('Unsuccessful fetching consumer: '.$rob->Error);
        } else {

            $consumer = $rob->Consumer;

            if ($consumer) {

                $cookieExpire = strtotime('+1 years');
                $path         = '/';

                setcookie( "p4mAvatarUrl",              $consumer->ProfilePicUrl,                       $cookieExpire, $path );
                setcookie( "p4mGivenName",              $consumer->GivenName,                           $cookieExpire, $path );
                setcookie( "p4mOfferCartRestore",       ( $rob->HasOpenCart ? "true" : "false" ),       $cookieExpire, $path );
                setcookie( "p4mLocalLogin",             "true",                                         $cookieExpire, $path );
                if (isset($consumer->PrefDeliveryAddress)) {
                    setcookie( "p4mDefaultPostCode",        $consumer->PrefDeliveryAddress->PostCode,       $cookieExpire, $path );
                    setcookie( "p4mDefaultCountryCode",     $consumer->PrefDeliveryAddress->CountryCode,    $cookieExpire, $path );
                }

            }


            /*
                Handle these possible scenereos 
                     Local User	    P4M User	                Action
                1	 Not logged in	Has no local Id 	        Create and login a new local user using the P4M details
                                                                Store the local Id in P4M Consumer.Extras["LocalId"]
                2	 Not logged in	Has a local Id 	            Login using the P4M local Id, update local details 
                3	 Logged in	    Has no local Id 	        Logout current user, proceed for 1
                4	 Logged in	    Has a different local Id 	Logout current user, proceed for 2 
                5	 Logged in	    Has matching local Id 	    Update local details from P4M if required 
            */

            $hasLocalId = ( isset($consumer->Extras) && isset($consumer->Extras->LocalId) && $consumer->Extras->LocalId);
            $loggedInUser = $this->userIsLoggedIn();
            if (!$loggedInUser) {

                if (!$hasLocalId) {
                    // case 1 
                    $extraDetails = $this->createNewUser($consumer); 
                    if (!isset($extraDetails->id)) throw new \Exception('No "id" field on local user');
                    $extraDetails->LocalId = $extraDetails->id;
                    $rob = $this->apiHttp('POST',  P4M_Shop_Urls::endPoint('consumerExtras'),  $extraDetails);
                } else {
                    // case 2 
                    $this->loginUser( $consumer->Extras->LocalId );
                }

            } else {

                if (!$hasLocalId) {
                    // case 3
                    $this->logoutCurrentUser();
                    $extraDetails = $this->createNewUser($consumer); 
                    if (!isset($extraDetails->id)) throw new \Exception('No "id" field on local user');
                    $extraDetails->LocalId = $extraDetails->id;
                    $rob = $this->apiHttp('POST',  P4M_Shop_Urls::endPoint('consumerExtras'),  $extraDetails);
                } elseif ( (property_exists($consumer, 'Extras')) && 
                           (property_exists($consumer->Extras, 'LocalId')) && 
                           (is_object($loggedInUser)) &&
                           (property_exists($loggedInUser, 'id')) &&
                           ($consumer->Extras->LocalId != $loggedInUser->id) ) 
                {
                    // case 4
                    $this->logoutCurrentUser();
                    $this->loginUser( $consumer->Extras->LocalId );
                } else {
                    // case 5
                    $this->setCurrentUserDetails( $consumer );
                }

            }

            $redirectTo = ($_GET['currentPage']?:null);
            echo '{ "RedirectUrl": "'.$redirectTo.'", "Success": true, "Error": null }';

        }


    }


    public function restoreLastCart() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-login-widget/restorelastcart/


        // Send the restoreLastCart API request 
        // Note that this endpoint will updated the saved P4M shopping cart with the passed in session id
        $localSessionId = $this->getCurrentSessionId();
        $endpoint       = P4M_Shop_Urls::endPoint('restoreLastCart') . '/' . $localSessionId;
        $this->setBearerToken($_COOKIE["p4mToken"]);

        $rob = $this->apiHttp('GET', $endpoint);

        if (!$rob->Success) {

            echo '{"Success": false, "Error": "'.$rob->Error.'" }';

        } else {

            $this->setCartOfCurrentUser( $rob->Cart );

            // delete the "p4mOfferCartRestore" cookie by setting it to have already expired
            if (isset($_COOKIE['p4mOfferCartRestore'])) {
                setcookie( "p4mOfferCartRestore", null, -1, '/');
            }

            echo  '{"Success": true, "Error": null }';

        }

    }


    public function checkout() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-checkout-widget/checkout/

        $currentCart = $this->getCartOfCurrentUser();

        if ( (!isset($currentCart->Items)) || (empty($currentCart->Items)) ) {
            $this->goHome();
        }

        if ( (!array_key_exists('gfsCheckoutToken', $_COOKIE)) || ($_COOKIE['gfsCheckoutToken']=='') ) {

            try {
                $oidc = new \OpenIDConnectClient(GFS_SERVER,
                                                Settings::getPublic('GFS:ClientId'),
                                                Settings::getPublic('GFS:ClientSecret') );
                $oidc->providerConfigParam(array('token_endpoint'=>P4M_Shop_Urls::endPoint('gfs_connect_token')));
                $oidc->addScope('read');
                $oidc->addScope('checkout-api');

                $response = $oidc->requestClientCredentialsToken();

                if (!$response) {
                    $this->somethingWentWrong('OIDC auth returned false');
                }

            } catch (\OpenIDConnectClientException $oidcE) {
                $this->somethingWentWrong('OIDC Exception :'.$oidcE->getMessage());
            } catch (\Exception $e) {
                $this->somethingWentWrong('Exception doing OIDC auth:'.$e->getMessage());
            }


            $accessToken  = $response->access_token;
            $encodeToken = base64_encode($accessToken);
            $cookieExpire = strtotime('+'.$response->expires_in.' seconds');
            $path         = '/';
            setcookie( "gfsCheckoutToken",
                    $encodeToken,
                    $cookieExpire,
                    $path );
            $_COOKIE['gfsCheckoutToken'] = $encodeToken;

        }

        $checkoutConfig = array (
            'sessionId'           => $this->getCurrentSessionId(),
            'gfsAccessToken'      => (array_key_exists('gfsCheckoutToken', $_COOKIE) ? $_COOKIE['gfsCheckoutToken'] : ''),
            'initialAddress'      => (array_key_exists('p4mInitialAddress', $_COOKIE) ? $_COOKIE['p4mInitialAddress'] : ''),
            'initialPostCode'     => (array_key_exists('p4mDefaultPostCode', $_COOKIE) ? $_COOKIE['p4mDefaultPostCode'] : ''),
            'initialCountryCode'  => (array_key_exists('p4mDefaultCountryCode', $_COOKIE) ? $_COOKIE['p4mDefaultCountryCode'] : '')
        );

        $checkoutHtml = $this->getCheckoutPageHtml( $checkoutConfig );

        echo $checkoutHtml;

    }


    public function getP4MCart() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-checkout-widget/getp4mcart/

        $resultObject = new \stdClass();

        try {
            $cartObject = $this->getCartOfCurrentUser();
            $resultObject->Success = true;
            $resultObject->Cart    = $cartObject;            
        } catch (\Exception $e) {
            $resultObject->Success = false;
            $resultObject->Error   = $e->getMessage();
        }

        $resultJson = json_encode($resultObject, JSON_PRETTY_PRINT);
        echo $resultJson;

    }


    public function udpShippingService() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-checkout-widget/updshippingservice/

        // update the local cart with the new shipping amt
        // recalculate cart totals (tax, discount, etc)

        $postBody = file_get_contents('php://input');
        $postBody = json_decode($postBody);
        
        $resultObject = new \stdClass();

        try {
            $this->updateShipping( $postBody->Service, $postBody->Amount, $postBody->DueDate );
            $totalsObject = $this->getCartTotals();

            $resultObject->Success  = true;
            $resultObject->Tax      = $totalsObject->Tax;
            $resultObject->Shipping = $totalsObject->Shipping;
            $resultObject->Discount = $totalsObject->Discount;
            $resultObject->Total    = $totalsObject->Total;
        } catch (\Exception $e) {
            $resultObject->Success = false;
            $resultObject->Error   = $e->getMessage();
        }

        $resultJson = json_encode($resultObject, JSON_PRETTY_PRINT);
        echo $resultJson; 

    }


    public function applyDiscountCode() {
        // http://developer.parcelfor.me/docs/documentation/parcel-for-me-widgets/p4m-checkout-widget/applydiscountcode/

        $postBody = file_get_contents('php://input');
        $postBody = json_decode($postBody);

        $resultObject = new \stdClass();

        try {
            $discountCodeDetails = $this->updateWithDiscountCode( $postBody->discountCode );
            $totalsObject = $this->getCartTotals();

            $resultObject->Success      = true;
            $resultObject->Tax          = $totalsObject->Tax;
            $resultObject->Shipping     = $totalsObject->Shipping;
            $resultObject->Discount     = $totalsObject->Discount;
            $resultObject->Total        = $totalsObject->Total;
            $resultObject->Code         = $discountCodeDetails->Code;
            $resultObject->Description  = $discountCodeDetails->Description;
            $resultObject->Amount       = $discountCodeDetails->Amount;
        } catch (\Exception $e) {
            $resultObject->Success = false;
            $resultObject->Error   = $e->getMessage();
        }

        $resultJson = json_encode($resultObject, JSON_PRETTY_PRINT);
        echo $resultJson;

    }


}



?>