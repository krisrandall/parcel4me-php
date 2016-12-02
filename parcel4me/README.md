# [parcel4me-php](../README.md) : Abstract Class

parcel4me-php implements the Host Server methods required by the Parcel4Me Widgets.    
<a href="http://developer.parcelfor.me/docs/documentation" target="_blank">See the documentation</a>.

## Usage 

TODO ... this is how I bring it into the demo server that I make


  
## API to Implement

The following methods must be implemented bring the Parcel4Me functionality into an existing shopping cart :

TODO : implement all of the following :


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


(see the [demo server implementation](../demo-server/README.md))
