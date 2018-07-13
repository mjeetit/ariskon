<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin Login</title>
    <link rel="shortcut icon" href="/cPanel_magic_revision_1266556447/unprotected/cpanel/favicon.ico" />

    <!-- EXTERNAL CSS -->
    <link href="<?php echo Bootstrap::$baseUrl;?>/public/css/styles.css" rel="stylesheet" type="text/css" />

    <!--[if IE 6]>
    <style type="text/css">
        img {
            behavior: url(/cPanel_magic_revision_1333643897/unprotected/cp_pngbehavior_login.htc);
        }
    </style>
    <![endif]-->

    <script>
    window.DOM = { get: function(id) { return document.getElementById(id) } };
    </script>
	<style>
	.input-field-login.icon{height:32px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;-moz-box-shadow:inset 0 10px 10px#c1e5ef;-webkit-box-shadow:inset 0 10px 10px #c1e5ef;box-shadow:inset 0 10px 10px #c1e5ef;border:2px solid #024257;background-color:#fff;background-position:6px 6px;background-repeat:no-repeat}div.username-container,div.reset-pass-container{background-image:url(<?php echo Bootstrap::$baseUrl?>/public/admin_images/icon-username.png)}div.password-container{background-image:url(<?php echo Bootstrap::$baseUrl?>/public/admin_images/icon-password.png)}input.std_textbox[disabled]{background-color:#005270;box-shadow:none!important}#userform{text-align:center}
	</style>
</head>
<body>
<div id="login-wrapper" class="login-whisp">
    <div id="notify">
        <noscript>
            <div class="error-notice">
                <img src="/cPanel_magic_revision_1333643898/unprotected/cpanel/images/notice-error.png" alt="Error" align="left"/>
                JavaScript is disabled in your browser.
                For cPanel to function properly, you must enable JavaScript.
                If you do not enable JavaScript, certain features in cPanel will not function correctly.
            </div>
			</noscript>

        <div id='login-status' class="error-notice" style="visibility: hidden">
            <span class='login-status-icon'></span>
            <div id="login-status-message">You have logged out.</div>
        </div>
    </div>
    <div id="content-container">
        <div id="login-container">
            <div id="login-sub-container">
                <div id="login-sub-header">
                    <img src="<?php echo Bootstrap::$baseUrl?>/public/admin_images/lock.png" alt="logo" height="70" width="70" />
                </div>
                <div id="login-sub">
                    <div id="forms">

                        
                        <form id="login_form" action="" method="post">
                            <div class="input-req-login"><label for="user">Username</label></div>
                            <div class="input-field-login icon username-container">
                                <input name="username" id="username" autofocus="autofocus" value="" placeholder="Enter your username." class="std_textbox" type="text"  tabindex="1" required>
                            </div>
                            <div style="margin-top:30px;" class="input-req-login"><label for="pass">Password</label></div>
                            <div class="input-field-login icon password-container">
                                <input name="password" id="password" placeholder="Enter your account password." class="std_textbox" type="password" tabindex="2"  required>
                            </div>
                            <div style="width: 285px;">
                                <div class="login-btn">
                                    <button name="login" type="submit" id="login_submit" tabindex="3">Log in</button>
                                </div>

                                                            </div>
                            <div class="clear" id="push"></div>
                        </form>

                    <!--CLOSE forms -->
                    </div>

                <!--CLOSE login-sub -->
                </div>
            <!--CLOSE login-sub-container -->
            </div>
        <!--CLOSE login-container -->
        </div>

        <div id="locale-footer">
            <div class="locale-container">
                <noscript>
                   
                    <style type="text/css">#locales_list {display:none}</style>
                </noscript>
            </div>
        </div>
    </div>
<!--Close login-wrapper -->
</div>
 
    <div class="copyright">Copyright © 2012 </div>

</body>

</html>
