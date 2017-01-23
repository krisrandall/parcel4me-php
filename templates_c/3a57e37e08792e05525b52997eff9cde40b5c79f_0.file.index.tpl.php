<?php
/* Smarty version 3.1.31, created on 2017-01-23 04:52:53
  from "/Users/newnrg/Projects/parcel4me/parcel4me-php/basic-demo/view/template/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_58858c25dd08e2_13808113',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3a57e37e08792e05525b52997eff9cde40b5c79f' => 
    array (
      0 => '/Users/newnrg/Projects/parcel4me/parcel4me-php/basic-demo/view/template/index.tpl',
      1 => 1485147170,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58858c25dd08e2_13808113 (Smarty_Internal_Template $_smarty_tpl) {
?>
<html>

    <head>
        <title>P4M Stub</title>

        <?php echo '<script'; ?>
 src="./basic-demo/lib/webcomponentsjs/webcomponents.min.js"><?php echo '</script'; ?>
>

        <link rel="import" href="./basic-demo/lib/p4m-widgets/p4m-login/p4m-login.html" />
        <link rel="import" href="./basic-demo/lib/p4m-widgets/p4m-register/p4m-register.html">

    </head>


    <body>
    
        <h1>p4m-server API</h1>
        These end points must be implemented in a shopping cart for it to use the Parcel4Me one-click checkout and delivery

        <ul>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['supportedEndPoints']->value, 'endpoint');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['endpoint']->value) {
?>
            <a href="<?php echo $_smarty_tpl->tpl_vars['endpoint']->value;?>
"><li><?php echo $_smarty_tpl->tpl_vars['endpoint']->value;?>
</li></a>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

        </ul>
            
        <hr/>
        
        <h1>Cart UI P4M widgets </h1>
        These UI Widgets should be added to a shopping cart in the approprate places
        
        <br/>
        
        <h2>p4m-register</h2>
        <p4m-register></p4m-register>

        <br/>
        
        <h2>p4m-login</h2>
        <p4m-login id-srv-url="<?php echo $_smarty_tpl->tpl_vars['idSrvUrl']->value;?>
" 
                    client-id="<?php echo $_smarty_tpl->tpl_vars['clientId']->value;?>
" 
                    redirect-url="<?php echo $_smarty_tpl->tpl_vars['redirectUrl']->value;?>
" 
                    logout-form="logoutForm">
        </p4m-login>
        
        <br/>

    </body>

</html><?php }
}
