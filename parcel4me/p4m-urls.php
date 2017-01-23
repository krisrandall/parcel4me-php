<?php

namespace P4M;

require 'p4m-config.php';


class P4M_Shop_Urls
{

    private static $endPoints = array(


            // OAuth2 (aka. Open Id Connect) (aka. Id Server) endpoints

            'oauth2_base_url'           => P4M_OID_SERVER,
            'signup'                    => P4M_OID_SERVER . '/ui/signup',
            'connect_token'             => P4M_OID_SERVER . '/connect/token',
            'authorize'                 => P4M_OID_SERVER . '/connect/authorize',
            'logout'                    => P4M_OID_SERVER . '/connect/endsession',
            'jwks'                      => P4M_OID_SERVER . '/.well-known/openid-configuration/jwks',


            // Parcel 4 Me API endpoints

            'registerConsumer'          => P4M_API_SERVER . '/registerConsumer',
            'consumer'                  => P4M_API_SERVER . '/consumer',
            'consumerExtras'            => P4M_API_SERVER . '/consumerExtras',
            'restoreLastCart'           => P4M_API_SERVER . '/restoreLastCart'
            

    );


    public static function endPoint($endPointStr) {

        $ep = self::$endPoints[$endPointStr];
        return $ep;

    }


}

?>