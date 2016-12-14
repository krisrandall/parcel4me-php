<?php

namespace P4M;

require 'p4m-config.php';


class P4M_Shop_Urls
{

    private static $endPoints = array(

                'oauth2_base_url'           => ID_SERVER,
                'signup'                    => ID_SERVER . '/ui/signup',
                'connect_token'             => ID_SERVER . '/connect/token',
                'authorize'                 => ID_SERVER . '/connect/authorize',
                'logout'                    => ID_SERVER . '/connect/endsession',

                'registerConsumer'         => API_SERVER . '/registerConsumer'
                

    );


    public static function endPoint($endPointStr) {

        $ep = self::$endPoints[$endPointStr];
        return $ep;

    }


}

?>