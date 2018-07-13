<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
<title>Login</title>
<link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/login.css" media="screen" />
<style>
/** the magic icon trick ! **/
[data-icon]:after {
    content: attr(data-icon);
    font-family: 'FontomasCustomRegular';
    color: rgb(106, 159, 171);
    position: absolute;
    left: 10px;
    top: 35px;
	width: 30px;
}

</style>
</head>
<body>
<div class="container">
  <section>
    <div id="container_demo" >
      <div id="wrapper">
        <div id="login" class="animate form">
          <form id="login_form" action="" method="post"> 
              <div class="logo"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/login.png"/><figcaption>ISO:9001:2008 Company</figcaption></div>
            
            <p>
              <label for="username" class="uname"> Your username </label>
              <input id="username" name="username" required type="text" placeholder="myusername"/>
            </p>
            <p>
              <label for="password" class="youpasswd"> Your password </label>
              <input id="password" name="password" required type="password" placeholder="eg. X8df!90EO" />
            </p>
            <p class="keeplogin">
              <input type="checkbox" name="rememberme" id="rememberme" value="1" />
              <label for="loginkeeping">Remember Me</label>
            </p>
            <p class="login button">
              <input type="submit" name="Submit" value="Login" />
            </p>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
</body>
</html>
