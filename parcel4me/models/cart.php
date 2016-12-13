<?php

namespace P4M\Models;

/* 

    Cart

    see : http://developer.parcelfor.me/docs/documentation/api-integration/models/cart/
*/
class Cart
{

    private $ConsumerId;              /* (read only) */
    private $Id;                      /* (read only) */
    private $SessionId;               /* Consumer's session Id on retailer's site */
    private $RetailerId;              /* (read only) */
    private $RetailerName;            /* (read only) */
    private $Reference;               /* Retailer reference (usually order no.) */
    private $AddressId;               /* must be the Id of an existing consumer address or collection point, unless the consumer has selected a new collection point, in which case calls to the API must indicate this, and the new collection point details must be passed during the purchase call */
    private $BillingAddressId;        /* must be the Id of an existing consumer address, not a collection point */
    private $Date;                    /* UTC date */ 
    private $Currency;
    private $ShippingAmt;
    private $Tax;
    private $Total;
    private $ServiceId;
    private $ServiceName;             /* e.g. standard, next day, etc */
    private $ExpDeliveryDate;
    private $DateDelivered;
    private $Carrier;
    private $ConsignmentId;
    private $CarrierToken;            /* Used to grant access to the carrier of the delivery */
    private $Status;                  /* Ordered, Despatched, etc */
    private $RetailerRating;
    private $CarrierRating;
    private $PaymentType;             /* set to "DB" (Debit) to collect payment with purchase. Set to "PA" (payment authorisation) to authorise the purchase only, in which case the payment must be processed later via a back office "capture". */
    private $PayMethodId;             /* The selected card token used for payment */
    private $PaymentId;               /* (read only)	 P4M transaction Id */
    private $AuthCode;                /* (read only)	 Used in back office transactions */
    private $PurchaseConfirmedTS;     /* (read only)	 Date and time purchased was confirmed by the PSP */
    private $Items;	                /* List (CartItems) */
    private $Dicounts;                /* List (Discounts)	*/


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