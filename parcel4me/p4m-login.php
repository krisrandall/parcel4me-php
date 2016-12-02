<?php


namespace P4M\HostServer;

require_once __DIR__.'/vendor/autoload.php';




/* 
WIP - porting the following from : https://github.com/ParcelForMe/p4m-demo-shop/blob/master/OpenOrderFramework/Controllers/P4MTokenController.cs

    [HttpGet]
    [Route("p4m/getP4MAccessToken")]
    public async Task<ActionResult> GetToken(string code, string state)
    {
        // state should be validated here - get from cookie
        string stateFromCookie, nonceFromCookie;
        GetTempState(out stateFromCookie, out nonceFromCookie);
        if (!state.Equals(stateFromCookie, StringComparison.Ordinal))
            throw new Exception("Invalid state returned from ID server");
        this.Response.Cookies["p4mState"].Expires = DateTime.UtcNow;

        var client = new OAuth2Client(new Uri(_urls.TokenEndpoint), _urls.ClientId, _urls.ClientSecret);
        var tokenResponse = await client.RequestAuthorizationCodeAsync(code, _urls.RedirectUrl);
        if (!tokenResponse.IsHttpError && ValidateToken(tokenResponse.IdentityToken, nonceFromCookie) && !string.IsNullOrEmpty(tokenResponse.AccessToken))
        {
            //var parsedToken = ParseJwt(response.AccessToken);
            this.Response.Cookies["p4mToken"].Value = tokenResponse.AccessToken;
            this.Response.Cookies["p4mToken"].Expires = DateTime.UtcNow.AddYears(1);
            //PostXMLData();
            return View("ReturnToken");
        }
        return View("error");
    }
*/

class TokenController
{

    

}

?>