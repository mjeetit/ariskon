<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pl" xml:lang="pl">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>HRM System</title>
        <!-- CSS Reset -->
		<link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/reset.css" media="screen" />
       
        <!-- Fluid 960 Grid System - CSS framework -->
		<link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/grid.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>javascript/datetimepicker/themes/base/jquery.ui.all.css"  media="screen"/>
		 <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>javascript/fancybox/jquery.fancybox-1.3.4.css"  media="screen" />
		
        <!-- IE Hacks for the Fluid 960 Grid System -->
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="ie6.css" tppabs="http://www.xooom.pl/work/magicadmin/css/ie6.css" media="screen" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="ie.css" tppabs="http://www.xooom.pl/work/magicadmin/css/ie.css" media="screen" /><![endif]-->
        
        <!-- Main stylesheet -->
        <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/styles.css"  media="screen" />
        
        <!-- WYSIWYG editor stylesheet -->
        <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/jquery.wysiwyg.css" media="screen" />
        
        <!-- Table sorter stylesheet -->
      <!--  <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/tablesorter.css"  media="screen" />-->
        
        <!-- Thickbox stylesheet -->
        <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/thickbox.css"  media="screen" />
        
        <!-- Themes. Below are several color themes. Uncomment the line of your choice to switch to different color. All styles commented out means blue theme. -->
       <!-- <link rel="stylesheet" type="text/css" href="<?php echo Bootstrap::$baseUrl;?>public/css/theme-blue.css" media="screen" />-->
        <!--<link rel="stylesheet" type="text/css" href="css/theme-red.css" media="screen" />-->
        <!--<link rel="stylesheet" type="text/css" href="css/theme-yellow.css" media="screen" />-->
        <!--<link rel="stylesheet" type="text/css" href="css/theme-green.css" media="screen" />-->
        <!--<link rel="stylesheet" type="text/css" href="css/theme-graphite.css" media="screen" />-->
        
		<!-- JQuery engine script-->
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/javascript/jquery-1.3.2.min.js"></script>
        
		<!-- JQuery WYSIWYG plugin script -->
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/javascript/jquery.wysiwyg.js"></script>
        
        <!-- JQuery tablesorter plugin script-->
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/javascript/jquery.tablesorter.min.js"></script>
        
		<!-- JQuery pager plugin script for tablesorter tables -->
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/javascript/jquery.tablesorter.pager.js"></script>
        
		<!-- JQuery password strength plugin script -->
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/javascript/jquery.pstrength-min.1.2.js"></script>
        
		<!-- JQuery thickbox plugin script -->
		<script type="text/javascript" src="<?php echo Bootstrap::$baseUrl;?>javascript/javascript/thickbox.js"></script>
		
		
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
		
		<?php include 'headercss.php';?>
		<?php echo $this->headScript();?>

    </head>
    <body>
 <div id="content">
		<div class="container_12">
		<?php if(isset($_SESSION[SUCCESS_MSG])){ ?>
				   <span class="notification n-success"><?php echo Bootstrap::showMessage(); ?></span>
		<?php } if(isset($_SESSION[ERROR_MSG])){ ?>
				 <span class="notification n-error"><?php echo Bootstrap::showMessage(); ?></span>   
		 <?php }?>
			<?php echo $this->layout()->content; ?>
            <div style="clear:both;"></div>
  </div>          
 <div style="clear:both;"></div>
</div>
</body>
</html>
