<?php
/*

    This config is specifically for the P4M server,
    it would only be changed for switching between
    a sandpit and live environment for example. 
    
*/

define("P4M_ENV_MODE", "dev");

define("P4M_SRV_PORT", "44333");
define("P4M_API_PORT", "44321");

define("P4M_BASE_SERVER", 'https://'.P4M_ENV_MODE.'.parcelfor.me');

define("P4M_OID_SERVER", P4M_BASE_SERVER . ':' . P4M_SRV_PORT);
define("P4M_API_SERVER", P4M_BASE_SERVER . ':' . P4M_API_PORT . '/api/v2');


define("GFS_SERVER", "https://identity.justshoutgfs.com");


?>