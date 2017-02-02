<?php

namespace P4M;

interface P4M_Shop_Interface {
    
    /**
        return currently logged on user if the user is logged into the shopping cart system, else false
    */
    public function userIsLoggedIn();


    /**
        create a new local user in the shopping cart DB and return the new local user object, else throw error
    */
    public function createNewUser( $p4m_consumer );


    /**
        do the local (ie. as per shopping cart logic) login process for this user 
    */
    public function loginUser( $localUserId );


    /**
        do the local logout process for the currently logged on user
    */
    public function logoutCurrentUser();


    /**
        return a populated consumer JSON object as defined by the model :
        http://developer.parcelfor.me/docs/documentation/api-integration/models/consumer/
    */
    public function getCurrentUserDetails();


    /**
        set the local user details based on the p4m consumer object 
    */
    public function setCurrentUserDetails( $p4m_consumer );


    /**
        return the users current shopping cart as a JSON object as defined here :
        http://developer.parcelfor.me/docs/documentation/api-integration/models/cart/
    */
    public function getCartOfCurrentUser();


    /**
        set the local shopping cart based on the p4m shopping cart details, passed in this format:
        http://developer.parcelfor.me/docs/documentation/api-integration/models/cart/
    */
    public function setCartOfCurrentUser( $p4m_cart );


    /**
        returns the full HTML of the checkout page of the shopping cart application

        These values come into the template :
        $replacementParams = array (
                            sessionId       => '',
                            gfsAccessToken  => ''
                        );

    */
    public function getCheckoutPageHtml( $replacementParams );


    /**
        update the shipping and tax on the current local cart   
    */
    public function updateShipping( $shippingServiceName, $amount, $dueDate );


    /**
        return an object with the following fields from the current local cart :
            ->Tax
            ->Shipping 
            ->Discount 
            ->Total
    */
    public function getCartTotals();


    /**
        apply this discount/coupon code and update the totals on the local cart
        return discount details object, which includes
            ->Code (same as passed in)
            ->Description 
            ->Amount 
        (if the coupon code is not valid, throw an exception)
    */
    public function updateWithDiscountCode( $discountCode );


    /**
        remove the discount code and update the totals on the local cart 
        return discount details object`
    */
    public function updateRemoveDiscountCode( $discountCode );





    /**
        return a URL to redirect to show the user an error when attempting to register them
        pass in an error message
    */
    public function localErrorPageUrl( $message );


}


?>