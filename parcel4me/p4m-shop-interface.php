<?php

namespace P4M;

interface P4M_Shop_Interface {
    
    /**
        return true if the user is logged onto your shopping cart, else false
    */
    public function userIsLoggedIn();

    /**
        return a populated consumer JSON object as defined by the model :
        http://developer.parcelfor.me/docs/documentation/api-integration/models/consumer/
    */
    public function getConsumerFromCurrentUser();
    
    /**
        return the users current shopping cart as a JSON object as defined here :
        http://developer.parcelfor.me/docs/documentation/api-integration/models/cart/
    */
    public function getCartOfCurrentUser();


    /**
        return a URL to redirect to show the user an error when attempting to register them
        pass in an error message
    */
    public function localErrorPageUrl($message);


}




?>