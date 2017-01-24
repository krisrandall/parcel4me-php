<html>


    <head>
        <title>P4M Checkout</title>

        <script src="../basic-demo/lib/webcomponentsjs/webcomponents.min.js"></script>

        <link rel="import" href="../basic-demo/lib/p4m-widgets/p4m-login/p4m-login.html" />
        <link rel="import" href="../basic-demo/lib/p4m-widgets/p4m-register/p4m-register.html">
        <link rel="import" href="../basic-demo/lib/p4m-widgets/p4m-checkout/p4m-checkout.html">

    </head>

    <body>
        
        <h1>Checkout</h1>
        <p><a href="/">go back</a></p>

        <p4m-login id-srv-url="{$idSrvUrl}" 
                    client-id="{$clientId}" 
                    redirect-url="{$redirectUrl}" 
                    logout-form="logoutForm">
        </p4m-login>

        <br/>

        <p4m-checkout 
            use-paypal="true" 
            use-gfs-checkout="true"
            session-id="{$config.sessionId}"
            gfs-access-token="{$config.gfsAccessToken}" >
        </p4m-checkout>


    </body>

</html>