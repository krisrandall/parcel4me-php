# Work in Progress ... 

# parcel4me-php

*A PHP implementation of <a href="http://parcelfor.me/" target="_blank"> Parcel4Me</a>'s one-click checkout and delivery*

This repository is a template implementatin of Parcel4Me.    
It includes an "Abstract Class" package that can be used for connecting any PHP shopping cart, as well as a demonstration bare-bones *implementation*.  Creating a Parcel4Me plugin for any existing shopping cart requires simply swapping out the demo *implementation* here for a matching implimation for the existing cart.

This repo is divided into three sections :

* **[parcel4me-php](parcel4me/README.md) :** A reusable un-opinionated parcel4me-php package that can be used with any shopping cart    
  *(this may later be converted into an independent [composer](https://getcomposer.org/) package)*
* **[Demo client](demo-client/README.md) :** Client HTML as a simple demo shell   
* **[Demo server](demo-server/README.md) :** The server *implementation* layer as a demo shell    


## Quick Start

[PHP](http://php.net/manual/en/intro-whatis.php) (at least version 5.3) and [Composer](https://getcomposer.org/) are required.    
The Demo client also requires a basic webserver to run (eg. [serve](https://www.npmjs.com/package/serve)).   

**Start the server**

	php -S localhost:8000

**Start the client**

	serve --port 3000 demo-client/
	
**Open the server API**   
 
 * <a href="http://localhost:8000/">http://localhost:8000/</a>

**Open the client** 

 * <a href="http://localhost:3000/">http://localhost:3000/</a>

 
 