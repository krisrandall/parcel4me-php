<?php

namespace P4M;

define("ENV_MODE", "dev");
define("SRV_PORT", "44333");
define("API_PORT", "44321");


class P4M_Shop_Urls
{
    private static $p4mUrl  = 'https://'.ENV_MODE.'.parcelfor.me:'.SRV_PORT;

    private static $endPoints = array(

                'base_url'      => '',
                'signup'        => '/ui/signup',
                'connect_token' => '/connect/token'

    );


    public static function endPoint($endPointStr) {

        $ep = self::$p4mUrl . self::$endPoints[$endPointStr];
        return $ep;

    }


}


/*

THIS IS CURRENTLY JUST COPIED FROM THE C# CLASS AS A REMINDER FOR ME ... 
ACTUALLY THIS WILL BE EXTERNAL CONFIG THAT IS PASSED IN IN SOME WAY

namespace OpenOrderFramework.Models
{
    public class P4MUrls
    {
        public P4MUrls()
        {
            AppMode = System.Configuration.ConfigurationManager.AppSettings["appMode"];
            P4MUrl = $"https://{AppMode}.parcelfor.me";
            BaseIdSrvUrl = $"{P4MUrl}:44333";
            BaseApiAddress = $"{P4MUrl}:44321/api/v2/";
            BaseIdSrvUiUrl = $"{BaseIdSrvUrl}/ui/";
            AuthBaseUrl = $"{BaseIdSrvUrl}/connect/authorize";
            TokenEndpoint = $"{BaseIdSrvUrl}/connect/token";
            LogoutUrl = $"{BaseIdSrvUrl}/connect/endsession";

            

            RedirectUrl = System.Configuration.ConfigurationManager.AppSettings["redirectUrl"];
            LogoutForm = "logoutForm";

            ClientId = System.Configuration.ConfigurationManager.AppSettings["clientId"];
            ClientSecret = System.Configuration.ConfigurationManager.AppSettings["clientSecret"];
        }

        public string AuthBaseUrl { get; set; }
        public string AppMode { get; set; }
        public string P4MUrl { get; set; }
        public string BaseIdSrvUrl { get; set; }
        public string BaseApiAddress { get; set; }
        public string BaseIdSrvUiUrl { get; set; }
        public string TokenEndpoint { get; set; }
        public string ClientId { get; set; }
        public string ClientSecret { get; set; }
        public string RedirectUrl { get; set; }  
        public string LogoutForm { get; set; } 
        public string LogoutUrl { get; set; }  
    }
}

*/


?>