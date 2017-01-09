# [parcel4me-php](../README.md) : Interface

parcel4me-php implements the Host Server methods required by the Parcel4Me Widgets.    
<a href="http://developer.parcelfor.me/docs/documentation" target="_blank">See the documentation</a>.

## Purpose

To facilitate implementing *<a href="http://parcelfor.me/" target="_blank"> Parcel4Me</a>'s* one-click checkout and delivery into an existing PHP shopping cart.

## Usage (how to modify an existing PHP shopping cart)

1. `require 'parcel4me/p4m-shop.php'` and implement the `P4M\P4M_Shop` abstract class, which means coding each of the methods listed in `parcel4me/p4m-shop-interface.php` to correctly interact with the shopping cart backend
2. add the Parcel4Me UI widgets into your shopping cart in the approprate places
3. implement the `p4m/*` API endpoints in the shopping carts router

(see the [basic demo implementation](../basic-demo/README.md))

## Existing Implementations

TODO :  (some implementations, and then list them here) !!


-----

## API to Implement

The following methods must be implemented to bring the Parcel4Me functionality into an existing shopping cart :


### p4m-login Widget

* getP4MAccessToken
* isLocallyLoggedIn
* localLogin
* restoreLastCart

### p4m-checkout Widget

* checkout
* getP4MCart
* shippingSelector
* updShippingService
* applyDiscountCode
* removeDiscountCode
* itemQtyChanged
* purchase
* paypalSetup
* paypalCancel
* purchaseComplete

### p4m-register Widget

* signup

