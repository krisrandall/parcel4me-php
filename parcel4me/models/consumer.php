<?php

namespace P4M\Models;

/*
    
    Consumer
    
    see : http://developer.parcelfor.me/docs/documentation/api-integration/models/consumer/
*/
class Consumer {
    
    private $Id;                      /*	 (read only) Assigned by P4M */
    private $Locale;                  /*	 (read only) Identifies where the consumer's data is stored */
    private $Salutation;              /*	 Mr, Ms, etc */
    private $GivenName;
    private $MiddleName;
    private $FamilyName;
    private $Email;
    private $MobilePhone;
    private $PreferredCurrency;       /* "GBP", "EUR", etc */
    private $Language;                /* "en", "fr", "de", etc */
    private $DOB;                     /* date , not string */
    private $Gender;
    private $Height;
    private $Weight;
    private $Waist;
    private $PreferredCarriers;       /* (read only) */
    private $PrefDeliveryAddressId;   /* links to the addresses array below ? */
    private $BillingAddressId;
    private $DefaultPaymentMethodId;
    private $DeliveryPreferences;     /* useMyDeliveryAddress, useMyDropPoints, useRetailerDropPoint */
    private $PreferSoonestDelivery;
    private $ProfilePicUrl;
    private $ProfilePicHash;          /* Can be used to check if the consumer's profile pic has changed */
    private $Addresses;               /* this is an array,   see : http://developer.parcelfor.me/docs/documentation/api-integration/models/address/ */
    private $PaymentMethods;          /* (read only)  this is an array */
    private $Extras;                  /* The Extras field contains a list of key/value pairs specific to the calling Retailer, and is available for them to store any additional information that may be needed */
            

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