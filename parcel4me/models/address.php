<?php

namespace P4M\Models;

/* 

    Address

    see : http://developer.parcelfor.me/docs/documentation/api-integration/models/address/
*/
class Address
{


    private $ConsumerId;            /* (read only)	 */
    public  $Id;                    /*  must be unique for each address for the consumer. If not assigned when added it will be assigned by P4M */
    public  $AddressType;           /*		"Address" or "Collect" */
    public  $Label;                 /*		e.g. Home, Work, etc */
    public  $CompanyName;	 	 
    public  $Street1;	 	 
    public  $Street2;	 	 
    public  $City;	 	 
    public  $PostCode;	 	 
    public  $State;	 	 
    public  $Country;	 	 
    public  $CountryCode;            /* ISO country code e.g. "UK", "US", "FR", etc */
    public  $Contact;               /*	Name of best contact person at address */
    public  $Phone;                 /* Phone at address or mobile of contact person */
    public  $Latitude	    = 0.0;	 
    public  $Longitude	    = 0.0;
    private $DropPointProviderId;   /*	Integer (read only)	Assigned */
    private $DropPointId;           /* 	 (read only)	Assigned */
    public  $CollectPrefOrder;      /*	Integer	Stores the preferred order for "Collect" addresses */


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