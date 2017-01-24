<html>

    <head>
        <title>P4M Stub</title>

        <script src="./basic-demo/lib/webcomponentsjs/webcomponents.min.js"></script>

        <link rel="import" href="./basic-demo/lib/p4m-widgets/p4m-login/p4m-login.html" />
        <link rel="import" href="./basic-demo/lib/p4m-widgets/p4m-register/p4m-register.html">

    </head>


    <body>
    
        <h1>p4m-server API</h1>
        These end points must be implemented in a shopping cart for it to use the Parcel4Me one-click checkout and delivery

        <ul>
        {foreach from=$supportedEndPoints item=endpoint}
            <a href="{$endpoint}"><li>{$endpoint}</li></a>
        {/foreach}
        </ul>
            
        <hr/>
        
        <h1>Cart UI P4M widgets </h1>
        These UI Widgets should be added to a shopping cart in the approprate places
        
        <br/>
        
        <h2>p4m-register</h2>
        <p4m-register></p4m-register>

        <br/>
        
        <h2>p4m-login</h2>
        <p4m-login id-srv-url="{$idSrvUrl}" 
                    client-id="{$clientId}" 
                    redirect-url="{$redirectUrl}" 
                    logout-form="logoutForm">
        </p4m-login>
        
        <br/>

        <h2>p4m-checkout</h2>
        <p>
            After logging on, checkout the <a href="/p4m/checkout">/p4m/checkout</a>
        </p>

        <br/>

    </body>

</html>