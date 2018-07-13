<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><?php print ADMIN_PAGE_TITLE; ?></title>
<!--<link href="<?php print CSS_LINK; ?>/style.css" rel="stylesheet" type="text/css" />-->
<link href="<?php print CSS_LINK; ?>/newstyle.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php print CSS_LINK; ?>/reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php print CSS_LINK; ?>/styles.css" media="screen" />

<script type="text/javascript">
	var imageUrl="editor_images/color.png"; // optionally, you can change path for images.
</script>

<script src="<?php print JAVASCRIPT_LINK; ?>/javascript/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php print JAVASCRIPT_LINK; ?>/javascript/shortcut.js"></script>
<script type="text/javascript" src="<?php print JAVASCRIPT_LINK; ?>/javascript/adminCommon.js.php"></script>
<script type="text/javascript" src="<?php print JAVASCRIPT_LINK; ?>/javascript/jquery-ajax.js"></script>


<script type="text/javascript" src="<?php print JAVASCRIPT_LINK; ?>/javascript/common.js"></script>
<script type="text/javascript" src="<?php print JAVASCRIPT_LINK; ?>/javascript/validation.js"></script>
<!-- Start of jquery light box -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquerys.min.js"></script>
<script>
	//!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
</script>
<script type="text/javascript" src="<?php print JAVASCRIPT_LINK; ?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php print JAVASCRIPT_LINK; ?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php print JAVASCRIPT_LINK; ?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<!-- End of jquery light box -->
 

<!-- End of Javascript & css for treeview  -->
<style type="text/css">
		
		label1 { display:block; float:left; width:180px;}
		input1 { display:block; float:left; border:1px solid #333; width:100px; padding:2px}
		code {margin:15px 0; color:#090; display:block; font-size:12px }
	</style>
<style>
.mid_content{background-color: <?php echo ADMIN_BOX_BG_COLOR; ?>;
	border:1px solid <?php echo ADMIN_BOX_BORDER_COLOR; ?>;width: 98%; margin-left:1%; margin-bottom:10px; float:left;}
	



#header-main { 	background: #ffffff url(<?php print IMAGE_LINK;?>/bg.png) repeat-x top left;	height: 80px;	}
#header-main #icon  h1{	background:url(<?php print IMAGE_LINK;?>/logout-icon.png) no-repeat; height: 35px; padding-left:40px; font-size:16px; font-weight:bold; color:#000000; margin:0px;padding:0px; float:right; width:17%}

div.module div.module-table-body { 
	background: url(<?php print IMAGE_LINK;?>/module-body-right-bg.gif) no-repeat scroll bottom right; 
	padding: 0; 
	float: right; 
	width: 100%; 
	}
div.module h2 { 
    /* Sliding right image */
    background: url(<?php print IMAGE_LINK;?>/module-header-left-bg.gif) no-repeat scroll top left; 
	display: block;
	float: left;
	height: 32px; /* CHANGE THIS VALUE ACCORDING TO IMAGE HEIGHT */
	margin-right: 0px;
	padding-right: 0px; /* CHENGE THIS VALUE ACCORDING TO RIGHT IMAGE WIDTH */
	/* FONT PROPERTIES */
	text-decoration: none;
	color: #444444;
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:bold;
	width: 100%;
	margin-bottom: 0;
	letter-spacing: normal
	}
div.module h2 span { 
	/* Background left image */ 
	background: url(<?php print IMAGE_LINK;?>/module-header-right-bg.gif) no-repeat top right; 
	display: block;
	line-height: 20px; /* CHANGE THIS VALUE ACCORDING TO BUTTONG HEIGHT */
	padding: 7px 0 5px 18px;
	}
#login{width:60%; margin:0 auto; padding:0px;}
.login{padding:20px 0px 0px 0px; margin:0px}

.centerpage{width:100%; float:left; margin:20px 0px 0px 0px; padding:0px}
.centerpage_left{width:60%; float:left; margin:20px 0px 0px 0px; padding:0px}
.centerpage_right{width:40%; float:right; margin:20px 0px 0px 0px; padding:0px}
div.module div.module-table-body { 
	background: url(<?php print IMAGE_LINK;?>/module-body-right-bg.gif) no-repeat scroll bottom right; 
	padding: 0; 
	float: right; 
	width: 100%; 
	}
div.module h2 { 
    /* Sliding right image */
    background: url(<?php print IMAGE_LINK;?>/module-header-left-bg.gif) no-repeat scroll top left; 
	display: block;
	float: left;
	height: 32px; /* CHANGE THIS VALUE ACCORDING TO IMAGE HEIGHT */
	margin-right: 0px;
	padding-right: 0px; /* CHENGE THIS VALUE ACCORDING TO RIGHT IMAGE WIDTH */
	/* FONT PROPERTIES */
	text-decoration: none;
	color: #444444;
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:bold;
	width: 100%;
	margin-bottom: 0;
	letter-spacing: normal
	}
div.module h2 span { 
	/* Background left image */ 
	background: url(<?php print IMAGE_LINK;?>/module-header-right-bg.gif) no-repeat top right; 
	display: block;
	line-height: 20px; /* CHANGE THIS VALUE ACCORDING TO BUTTONG HEIGHT */
	padding: 7px 0 5px 18px;
	}
div.module table {	width: 100%; 	margin: 0 0 10px 0;	border-left: 1px solid #d9d9d9;	border-bottom: 1px solid #d9d9d9; border-top: 1px solid #d9d9d9;}
div.module table.tr {	border-collapse: separate;	border-right: 1px solid #aaaaaa;	border-left: 1px solid #aaaaaa; border-top: 1px solid #d9d9d9;	}
div.module table th {	background-color: #eeeeee;	color: #444444;	padding: 5px;	text-align: center;	border-right: 1px solid #d9d9d9;	border-bottom: 1px solid #d9d9d9;	border-top: 1px solid #d9d9d9;}
div.module table td {	background-color: #ffffff;	padding: 5px;border-right: 1px solid #d9d9d9;	border-bottom: 1px solid #d9d9d9; border-top: 1px solid #d9d9d9; border-left: 1px solid #d9d9d9;}
div.module table tr.odd td { background-color: #f1f5fa;	}
div.module table tr:hover{ background:#000000}

.table-apply {	width: 40%; float: right; text-align: right; margin-right: 10px;	}
.bordernone{border:none}
a.button {
    /* Sliding right image */
    background: transparent url(<?php print IMAGE_LINK;?>/button-right-bg-m.gif) no-repeat scroll top right; 
	display: block;
	float: left;
	height: 26px; /* CHANGE THIS VALUE ACCORDING TO IMAGE HEIGHT */
	padding-right: 11px; /* CHENGE THIS VALUE ACCORDING TO RIGHT IMAGE WIDTH */
	/* FONT PROPERTIES */
	text-decoration: none;
	color: #444444;
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
	}
a.button span {
	/* Background left image */ 
	background: transparent url(<?php print IMAGE_LINK;?>/button-left-bg-m.gif) no-repeat top left; 
	display: block;
	line-height: 15px; /* CHANGE THIS VALUE ACCORDING TO BUTTONG HEIGHT */
	padding: 4px 0 7px 10px;
	}
a.button:hover { 	background-position: bottom right; color:#0063be; 	}
a.button:hover span{	background-position: bottom left;	color:#0063be;	}
/* button inside a module box */
div.module a.button {
    /* Sliding right image */
    background: transparent url(<?php print IMAGE_LINK;?>/button-right-bg.gif) no-repeat scroll top right; 
	display: block;
	float: left;
	height: 26px; /* CHANGE THIS VALUE ACCORDING TO IMAGE HEIGHT */
	padding-right: 11px; /* CHENGE THIS VALUE ACCORDING TO RIGHT IMAGE WIDTH */
	/* FONT PROPERTIES */
	text-decoration: none;
	color: #444444;
	font-family: Arial, Helvetica, sans-serif;
	font-size:12px;
	}
div.module a.button span {
	background: transparent url(<?php print IMAGE_LINK;?>/button-left-bg.gif) no-repeat top left; 
	display: block;
	line-height: 15px; /* CHANGE THIS VALUE ACCORDING TO BUTTONG HEIGHT */
	padding: 4px 0 7px 10px;
	}
a.dashboard-module, a.dashboard-module:visited { 	width: 142px; 	height: 142px; 	display: block;	float: left; 	background: url(<?php print IMAGE_LINK;?>/dashboard-module.gif) top left no-repeat; margin: 0 40px 15px 0;  	text-align: center; 	color: #444444; 	}
div.module { 
	background: url(<?php print IMAGE_LINK;?>/module-body-left-bg.gif) no-repeat scroll bottom left; 
	float: right; 
	width: 100%; 
	}
div.module div.module-body { 
	background: url(<?php print IMAGE_LINK;?>/module-body-right-bg.gif) no-repeat scroll bottom right; 
	padding: 20px 0px 20px 0; float: left; width: 100%;
	}

#subnav {	background: #ffffff url(<?php print IMAGE_LINK;?>/submenu-bg.gif) repeat-x bottom left; width:100%;		height: 48px;	}
#subnav1{ width:96%; margin:0px 2%;}
ul#nav { 	float: left; 	margin-top: 60px; 	list-style: none; 	font-size:14px; 	margin-bottom: 0;	}
ul#nav li {	background:transparent url(<?php print IMAGE_LINK;?>/tab-left.gif) no-repeat scroll left top;	float:left;	margin:0 5px 0 0;	padding:0 0 0 9px;	}
ul#nav li a, ul#nav li a:visited {background:transparent url(../images/tab-right.gif) no-repeat scroll right top;color:#FFFFFF;display:block;float:left;
	padding:7px 15px 6px 6px;text-decoration:none;}
ul#nav li a:hover {	padding:8px 15px 5px 6px;	}
ul#nav li#current { background:transparent url(<?php print IMAGE_LINK;?>/tab-active-left.gif) no-repeat scroll left top;}
ul#nav li#current a {	background:transparent url(<?php print IMAGE_LINK;?>/tab-active-right.gif) no-repeat scroll right top;	color:#444444;	}

/* Percentage padding in the module dependant on the cell width */
#linkpath{width:99%; margin:0px 0px 0px 1%; padding:0px;}
.crumb_navigation{
width:100%;
height:15px;
padding:5px 0px 0 0px;
color:#333333;
background:url(../images/navbullet.png) no-repeat left;
background-position:0px 11px;
}
.blue #slatenav ul li a:hover,.blue #slatenav ul li a.current{color:#fff;background:transparent url(<?php print IMAGE_LINK;?>/blueslate_backgroundOVER.gif) no-repeat top center;}

a.action_link:link, a.action_link:visited, a.action_link:active{
	font-size: 12px;
	color: #2E75A3;
	font-family: tahoma, verdana, arial, helvetica, sans-serif;
	line-height: 20px;
	padding: 0 40px 0 20px;
	margin: 0 0 0 0;
	font-weight:bold;
	text-decoration: none;
	background: url("<?php print IMAGE_LINK;?>/ico_menu_on.gif") 2px 2px no-repeat;
}

a.action_link:hover{
	font-size: 12px;
	color: #000000;
	font-family: tahoma, verdana, arial, helvetica, sans-serif;
	line-height: 20px;
	padding: 0 40px 0 20px;
	margin: 0 0 0 0;
	font-weight:bold;
	text-decoration: none;
	background: url("<?php print IMAGE_LINK;?>/ico_menu_off.gif") 2px 2px no-repeat;
}
</style>
<?php
$this->jQuery()->setLocalPath(JAVASCRIPT_LINK.'/jquery/js/jquery-1.6.2.min.js')
               ->addStylesheet(JAVASCRIPT_LINK.'/jquery/js/ui.datepicker.css');
echo $this->jQuery();

?>
</head>
<body>
<?php include 'headercode.php'?>