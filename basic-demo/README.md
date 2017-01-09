# [parcel4me-php](../README.md) : Most Basic Demo

### Setting up the P4M widgets

The client is the most basic implementation of the parcel4me web components.   
The P4M widgets (the UI) were set up by doing the following : 

 1. Create a `/lib` directory
 2. Get the <a href="https://github.com/ParcelForMe/p4m-widgets/">p4m-widgets repo</a> and save in the `/lib` directory
 3. Run `bower install` in the `/lib/p4m-widgets` directory
 4. Move the *contents* of the `bower_install` directory back into the `/lib` directory

### Handling the P4M Endpoints

`index.php` is a "demo" implementation of the parcel4me PHP abstract class, it has a router that handles each of the required P4M endpoints.
It has no UI (other than what the P4M widgets provide) and only hardcoded sample data.

### What do I do with this ?

The purpose of this demo is to provide a sample that can be copied to implement a P4M checkout into an existing PHP based shopping cart.   
Taking the code in `index.php` and replacing the router and the hardcoded sample method with methods that correctly access the database and methods of another existing shopping cart is all that is required to implement P4M checkout with that shopping cart.





