<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>HRM System</title>
        <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/style.css" type="text/css" media="screen"/> 
	   <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>javascript/datetimepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen"/> 
	   <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>javascript/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen"/> 
        
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/javascript/jquery-1.8.0.js"></script>
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/javascript/Common.js.php" type="text/javascript"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/datetimepicker/js/jquery.ui.core.js" type="text/javascript"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/datetimepicker/js/jquery.ui.widget.js" type="text/javascript"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/datetimepicker/js/jquery.ui.datepicker.js" type="text/javascript"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/datetimepicker/js/jquery.ui.timepicker.js" type="text/javascript"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/datetimepicker/js/jquery.ui.timepicker.js" type="text/javascript"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/fancybox/jquery.mousewheel-3.0.4.pack.js" type="text/javascript"></script>
		<script src="<?php echo Bootstrap::$baseUrl;?>javascript/fancybox/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>
		
        <script type="text/javascript">
            $(function(){
                $(".box .h_title").not(this).next("ul").hide("normal");
                $(".box .h_title").not(this).next("#home").hide("normal");
                $(".box").children(".h_title").click( function() { $(this).next("ul").slideToggle(); });
            });
        </script>
<style>
body{
	color: #484848;
    font-family: Tahoma,Arial,Helvetica,sans-serif;
    font-size: 12px;
    line-height: 1.5em;
    position: relative;
}
#header {
	background: url("<?php echo Bootstrap::$baseUrl;?>/public/admin_images/bg_head.png") no-repeat scroll 0 0 transparent;
    color: #FFFFFF;
    height: 120px;
    position: relative;

	margin:0 auto; width:97%;
	
	}

</style>		
    </head>
    <body>
      <div class="wrap">
	<div id="content">     
