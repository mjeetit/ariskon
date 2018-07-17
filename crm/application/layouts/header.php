<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>CRM : Ariskon Pharma</title>
        
		<link rel="stylesheet" type="text/css" media="screen" href="<?=Bootstrap::$baseUrl?>public/css/reset.css" /><!-- CSS Reset -->         
		<link rel="stylesheet" type="text/css" media="screen" href="<?=Bootstrap::$baseUrl?>public/css/grid.css" /><!-- Fluid 960 Grid System - CSS framework -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?=Bootstrap::$baseUrl?>javascript/datetimepicker/themes/base/jquery.ui.all.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?=Bootstrap::$baseUrl?>javascript/fancybox/jquery.fancybox-1.3.4.css" />        
        <link rel="stylesheet" type="text/css" media="screen" href="<?=Bootstrap::$baseUrl?>public/css/styles.css" /><!-- Main stylesheet -->        
        <link rel="stylesheet" type="text/css" media="screen" href="<?=Bootstrap::$baseUrl?>public/css/jquery.wysiwyg.css" /><!-- WYSIWYG editor stylesheet -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?=Bootstrap::$baseUrl?>public/css/thickbox.css" /><!-- Thickbox stylesheet -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?=Bootstrap::$baseUrl?>public/css/top_menu.css" /><!-- Top Menu stylesheet -->
		
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/jquery-1.4.3.min.js"></script><!-- JQuery engine script-->
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/jquery.wysiwyg.js"></script><!-- JQuery WYSIWYG plugin script -->
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/jquery.tablesorter.min.js"></script><!-- JQuery tablesorter plugin script-->
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/jquery.tablesorter.pager.js"></script><!--JQuery pager plugin script for tablesorter-->
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/jquery.pstrength-min.1.2.js"></script><!--JQuery password strength plugin script -->
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/thickbox.js"></script><!-- JQuery thickbox plugin script -->
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/jquery-1.8.0.js"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/Common.js.php"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/datetimepicker/js/jquery.ui.core.js"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/datetimepicker/js/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/datetimepicker/js/jquery.ui.datepicker.js"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/datetimepicker/js/jquery.ui.timepicker.js"></script>
		<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/datetimepicker/js/jquery.ui.timepicker.js"></script>
		
		<?php include 'headercss.php';?>
		<?=$this->headScript()?>
	</head>
		<body>
        <div id="wrapper">
    	<!-- Header -->
        <div id="header">
            <!-- Header. Status part -->
            <div id="header-status">
                <div class="container_12">
                    <div class="grid_8">
					<strong>Administration Section</strong>
                    </div>
                    <div class="grid_4">
                      <p>Welcome, <strong><?php echo ucfirst($_SESSION['AdminName'])?>&nbsp;</strong><img src="<?=Bootstrap::$baseUrl.'public/admin_images/user.gif'?>"/>  <a href="<?=$this->url(array('controller'=>'Admin','action'=>'logout'),'default',true)?>" id="logout">
                        Logout
                        </a>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div> <!-- End #header-status -->
            
            <!-- Header. Main part -->
            <div id="header-main">
                <div class="container_12">
                    <div class="grid_12">
                        <div id="logo">
						<img src="<?php echo Bootstrap::$baseUrl.'public/admin_images/final-logo-jcl-medium.png'?>" height="90" width="180" style="float:left" />
						<img src="<?php echo Bootstrap::$baseUrl.'public/admin_images/alancus_logo_blue2.png'?>" height="90" width="130" style="float:right" />
						<!-- End. #Logo -->
                    </div><!-- End. .grid_12-->
                    <div style="clear: both;"></div>
                </div><!-- End. .container_12 -->
            </div> <!-- End #header-main -->
            <div style="clear: both;"></div>