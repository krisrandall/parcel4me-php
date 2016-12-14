<?php

namespace P4M\Models;

/*
    
    Consumer
    
    see : http://developer.parcelfor.me/docs/documentation/api-integration/models/consumer/
*/
class Consumer {
    
    private $Id;                      /*	 (read only) Assigned by P4M */
    private $Locale;                  /*	 (read only) Identifies where the consumer's data is stored */
    public  $Salutation;              /*	 Mr, Ms, etc */
    public  $GivenName;
    public  $MiddleName;
    public  $FamilyName;
    public  $Email;
    public  $MobilePhone;
    public  $PreferredCurrency;       /* "GBP", "EUR", etc */
    public  $Language;                /* "en", "fr", "de", etc */
    public  $DOB;                     /* date , not string */
    public  $Gender;
    public  $Height;
    public  $Weight;
    public  $Waist;
    private $PreferredCarriers;       /* (read only) */
    public  $PrefDeliveryAddressId;   /* links to the addresses array below ? */
    public  $BillingAddressId;
    public  $DefaultPaymentMethodId;
    public  $DeliveryPreferences;     /* useMyDeliveryAddress, useMyDropPoints, useRetailerDropPoint */
    public  $PreferSoonestDelivery  = false;
    public  $ProfilePicUrl;
    public  $ProfilePicHash;          /* Can be used to check if the consumer's profile pic has changed */
    public  $Addresses;            //  = [];              /* this is an array,   see : http://developer.parcelfor.me/docs/documentation/api-integration/models/address/ */
    public  $PaymentMethods;       //   = [];              /* (read only)  this is an array */
    public  $Extras;              //   = [];              /* The Extras field contains a list of key/value pairs specific to the calling Retailer, and is available for them to store any additional information that may be needed */
            

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

}


?>