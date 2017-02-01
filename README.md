# Work in Progress ... 

# parcel4me-php

*A PHP implementation of <a href="http://parcelfor.me/" target="_blank"> Parcel4Me</a>'s one-click checkout and delivery*

This repository is a template implementatin of Parcel4Me.    
It includes an "[Abstract Class](http://php.net/manual/en/language.oop5.abstract.php)" (and "[Interface](http://php.net/manual/en/language.oop5.interfaces.php)") package that can be used for connecting any PHP shopping cart, as well as a demonstration bare-bones *implementation*.  Creating a Parcel4Me plugin for any existing shopping cart requires simply swapping out the demo *implementation* here for a matching implimation for the existing cart.

This repo is divided into two sections :

* **[parcel4me-php](parcel4me/README.md) :** A reusable un-opinionated parcel4me-php interface that can be used with any shopping cart    
  *(this may later be converted into an independent [composer](https://getcomposer.org/) package)*
* **[Basic Demo](basic-demo/README.md) :** Client HTML and server PHP as a simple demo shell   
 


## Quick Start

[PHP](http://php.net/manual/en/intro-whatis.php) (at least version 7) and [Composer](https://getcomposer.org/) are required.    
  

### Install

**Composer Install**

    $ cd parcel4me;   composer install; cd ..
    $ cd basic-demo;  composer install; cd ..

**Widgets Install** *(requires bash, git and bower)*

    $ ./install-widgets.sh
  
*(Note that if the [p4m-widget repo](https://github.com/ParcelForMe/p4m-widgets) gets updated; use `$ ./update-widgets.sh`)*


  
### Run

**Start the server**

	$ php -S localhost:8000

	
**Open the basic demo**   
 
 * <a href="http://localhost:8000/">http://localhost:8000/</a>




 