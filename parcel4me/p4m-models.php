<?php

namespace P4M\Models;

// Bring in ALL of the P4M model Classes

// see : http://developer.parcelfor.me/docs/documentation/api-integration/models/overview/

/* 

Notes about the models :

  - I have used private properties for fields marked Read Only in the API,
    the only reason for this is that I was getting an error sending them to the API,
    it is a hacky approach really, but instead of changing it now, i'm putting this 
    note in here until I have to change it, or spontaneously think of a cleaner approach

*/

require 'models/consumer.php';
require 'models/cart.php';
require 'models/address.php';


?>