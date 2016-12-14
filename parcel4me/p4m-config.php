<?php
/*

    This config is specifically for the P4M server,
    it would only be changed for switching between
    a sandpit and live environment for example. 
    
*/

define("ENV_MODE", "dev");

define("SRV_PORT", "44333");
define("API_PORT", "44321");

define("BASE_SERVER", 'https://'.ENV_MODE.'.parcelfor.me');

define("ID_SERVER",  BASE_SERVER . ':' . SRV_PORT);
define("API_SERVER", BASE_SERVER . ':' . API_PORT . '/api/v2');

?>