<style type="text/css">
.TB_overlayMacFFBGHack {background: url(<?php echo IMAGE_LINK;?>/macFFBgHack.png) repeat;}
/* -------------------- Logo -------------------- */

#header-main #logo { 
	background: #5ac3ce no-repeat left 10px; 
	width: 100%; 
	height: 70px; 
	}



	
/* -------------------- Header background -------------------- */
	
#header-main { 
	background: #5ac3ce repeat-x top left; 
	}




/* -------------------- Tabs -------------------- */

#nav {
    background: none repeat scroll 0 0 transparent;
    display: block;
    font-family: "Trebuchet MS";
    font-size: 12px;
    font-weight: bold;
    position: relative;
    text-transform: capitalize;
}
#nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: auto;
}
#nav ul li {
    display: block;
    float: left;
	color:#666666;
}
#nav ul li a:hover,#nav ul li a.current{color:#fff;background:transparent url(<?php print IMAGE_LINK;?>/blueslate_backgroundOVER.gif) no-repeat top center;}

/* tables */
table.tablesorter {
	/*font-family:arial;
	/*background-color: #CDCDCD;*/
	/*margin:10px 0pt 15px;*/
	/*font-size: 8pt;*/
	width: 100%;
	text-align: left;
}
table.tablesorter thead tr th, table.tablesorter tfoot tr th {
	/*background-color: #e6EEEE;
	border: 1px solid #FFF;
	font-size: 8pt;
	padding: 4px;*/
}
table.tablesorter thead tr .header {
	background-image: url(<?php echo IMAGE_LINK;?>/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}
table.tablesorter tbody td {
	color: #3D3D3D;
	padding: 4px;
	background-color: #FFF;
	vertical-align: top;
}
table.tablesorter tbody tr.odd td {
	background-color:#f1f5fa;
}
table.tablesorter thead tr .headerSortUp {
	background-image: url(<?php echo IMAGE_LINK;?>/asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(<?php echo IMAGE_LINK;?>/desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
background-color: #dddddd;
}
#message-notification {
	display: block; 
	float: left; 
	background: url(<?php echo IMAGE_LINK;?>/mail-q-bg.jpg) top left no-repeat; 
	color: #6dc6e7; 
	padding: 9px 0 11px 0; 
	text-decoration: none; 
	margin-left: 10px;
	}
a#logout, 
a#logout:visited { 
	display: block; 
	float: right; 
	background: url(<?php echo IMAGE_LINK;?>/icon-logout.gif) center right no-repeat; 
	color: #6dc6e7; 
	padding: 9px 20px 11px 0px; 
	text-decoration: none; 
	}
	
#subnav {
	background: #ffffff url(<?php echo IMAGE_LINK;?>/submenu-bg.gif) repeat-x bottom left;
		height: 48px;
	}
ul#nav li {
	background:transparent url(<?php echo IMAGE_LINK;?>/tab-left.gif) no-repeat scroll left top;
	float:left;
	margin:0 5px 0 0;
	padding:0 0 0 9px;
	}

ul#nav li a, ul#nav li a:visited {
	background:transparent url(<?php echo IMAGE_LINK;?>/tab-right.gif) no-repeat scroll right top;
	color:#FFFFFF;
	display:block;
	float:left;
	padding:7px 15px 6px 6px;
	text-decoration:none;
	}
ul#nav li#current { /* give the id="current" to the currently selected tab */
	background:transparent url(<?php echo IMAGE_LINK;?>/tab-active-left.gif) no-repeat scroll left top;
	}

ul#nav li#current a {
	background:transparent url(<?php echo IMAGE_LINK;?>/tab-active-right.gif) no-repeat scroll right top;
	color:#444444;
	}




/* -------------------- Box module -------------------- */

div.module { 
	background: url(<?php echo IMAGE_LINK;?>/module-body-left-bg.gif) no-repeat scroll bottom left; 
	float: left; 
	width: 100%; 
	margin-bottom: 20px; 
	}

div.module div.module-body { 
	background: url(<?php echo IMAGE_LINK;?>/module-body-right-bg.gif) no-repeat scroll bottom right; 
	padding: 20px 3% 20px 3%; float: left; width: 94%;
	}
div.module div.module-table-body { 
	background: url(<?php echo IMAGE_LINK;?>/module-body-right-bg.gif) no-repeat scroll bottom right; 
	padding: 0; 
	float: left; 
	width: 100%; 
	}

div.module h2 { 
    /* Sliding right image */
    background: url(<?php echo IMAGE_LINK;?>/module-header-left-bg.gif) no-repeat scroll top left; 
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
	background: url(<?php echo IMAGE_LINK;?>/module-header-right-bg.gif) no-repeat top right; 
	display: block;
	line-height: 20px; /* CHANGE THIS VALUE ACCORDING TO BUTTONG HEIGHT */
	padding: 7px 0 5px 18px;

	}
/* button outside a module box */
a.button {
    /* Sliding right image */
    background: transparent url(<?php echo IMAGE_LINK;?>/button-right-bg-m.gif) no-repeat scroll top right; 
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
	background: transparent url(<?php echo IMAGE_LINK;?>/button-left-bg-m.gif) no-repeat top left; 
	display: block;
	line-height: 15px; /* CHANGE THIS VALUE ACCORDING TO BUTTONG HEIGHT */
	padding: 4px 0 7px 10px;
	}
/* button inside a module box */
div.module a.button {
    /* Sliding right image */
    background: transparent url(<?php echo IMAGE_LINK;?>/button-right-bg.gif) no-repeat scroll top right; 
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
	background: transparent url(<?php echo IMAGE_LINK;?>/button-left-bg.gif) no-repeat top left; 
	display: block;
	line-height: 15px; /* CHANGE THIS VALUE ACCORDING TO BUTTONG HEIGHT */
	padding: 4px 0 7px 10px;
	}
input.input-short,
input.input-medium,
input.input-long, 
select, 
textarea {
	background: url(<?php echo IMAGE_LINK;?>/input-bg.gif) top left repeat-x #f6f6f6;
	border: 0;
	border: 1px solid #cccccc;
	}

input.input-short:focus,
input.input-medium:focus,
input.input-long:focus, 
select:focus, 
textarea:focus {
	background: url(<?php echo IMAGE_LINK;?>/input-bg-focus.gif) top left repeat-x #ffffff;
	border-color: #a9c2d1;
	}


input.submit-green { 
	background: url(<?php echo IMAGE_LINK;?>/submit-green-bg.gif) top left repeat-x; 
	border: 0; 
	border-top: 1px solid #6bd091; 
	border-left: 1px solid #6bd091; 
	border-right: 1px solid #349c5c; 
	border-bottom: 1px solid #349c5c; 
	color: #ffffff; 
	font-size: 14px; 
	padding: 2px 12px; 
	margin: 0px 10px 0 0;
	cursor: pointer;
	}

input.submit-green-hover { 
	background-image: url(<?php echo IMAGE_LINK;?>/submit-green-bg-hover.gif);
	}

input.submit-gray { 
	background: url(<?php echo IMAGE_LINK;?>/submit-gray-bg.gif) top left repeat-x; 
	border: 0; 
	border-top: 1px solid #cccccc; 
	border-left: 1px solid #cccccc; 
	border-right: 1px solid #888888; 
	border-bottom: 1px solid #888888; 
	color: #ffffff; 
	font-size: 14px; 
	padding: 2px 12px; 
	margin: 0px 10px 0 0;
	cursor: pointer;
	}

input.submit-gray-hover { 
	background-image: url(<?php echo IMAGE_LINK;?>/submit-gray-bg-hover.gif); 
	}
.ni-correct {
	background-image:url(<?php echo IMAGE_LINK;?>/tick-on-white.gif);
	color: #00ae42;
	}

.ni-error {
	background-image:url(<?php echo IMAGE_LINK;?>/cross-on-white.gif);
	color: #c9282d;
	}
.n-success {
	background-color: #a3e6bd;
	border-color: #68d59b;
	background-image: url(<?php echo IMAGE_LINK;?>/notification-tick.gif);
	}

.n-information {
	background-color: #9fddea;
	border-color: #5fceea;
	background-image: url(<?php echo IMAGE_LINK;?>/notification-information.gif);
	}

.n-attention {
	background-color: #f9e497;
	border-color: #ffcb4f;
	background-image: url(<?php echo IMAGE_LINK;?>/notification-exclamation.gif);
	}

.n-error {
	background-color: #ffc6ca;
	border-color: #efb9c3;
	background-image: url(<?php echo IMAGE_LINK;?>/notification-slash.gif);
	}

/* -------------------- Indicators -------------------- */


.indicator {
	width: 220px;
	height: 12px;
	background: url(<?php echo IMAGE_LINK;?>/indicator-bg.gif) no-repeat top left;
	}

.indicator div {
	height: 12px;
	background: url(<?php echo IMAGE_LINK;?>/indicator-green-to-red.gif) no-repeat top left;
	}

.indicator div.reverse {
	background: url(<?php echo IMAGE_LINK;?>/indicator-red-to-green.gif) no-repeat top left;
	}
a.removable:hover, 
a.removable:active {
	background: url(<?php echo IMAGE_LINK;?>/cross-small.gif) no-repeat center right;
	}
a.checkable:hover, 
a.checkable:active {
	background: url(<?php echo IMAGE_LINK;?>/tick-small.gif) no-repeat center right;
	}
.user { 
	display: block; 
	padding-left: 22px; 
	background: url(<?php echo IMAGE_LINK;?>/user.gif) left 50% no-repeat; 
	font-size: 14px; color: #666666; 
	font-weight: normal;  
	}
	
.user-female { 
	display: block; 
	padding-left: 22px; 
	background: url(<?php echo IMAGE_LINK;?>/user-female.gif) left 50% no-repeat; 
	font-size: 14px; 
	color: #666666; 
	font-weight: normal;  
	}
	
.reply { 
	padding-left: 20px; 
	background: url(<?php echo IMAGE_LINK;?>/arrow-curve-180-left.gif) left 50% no-repeat; 
	margin-right: 7px;
	}
	
.forward { 
	padding-left: 20px; 
	background: url(<?php echo IMAGE_LINK;?>/arrow-curve-000-left.gif/) left 50% no-repeat; 
	margin-right: 7px; 
	}
	
a.delete, 
a.delete:visited { 
	padding-left: 16px;
	background: url(<?php echo IMAGE_LINK;?>/cross-small.gif) left 50% no-repeat; 
	color: #C00;  
	}

h3.mail { 
	display: block; 
	padding: 20px 0px 20px 70px; 
	background: url(<?php echo IMAGE_LINK;?>/Crystal_Project_mail_open.gif) left 50% no-repeat;
	}
form.login {
	background: url(<?php echo IMAGE_LINK;?>/Crystal_Clear_locked.gif) 87% 10px no-repeat;
}

a.dashboard-module,a.dashboard-module:visited { 
	width: 142px; 
	height: 142px; 
	display: block; 
	float: left; 
	background: url(<?php echo IMAGE_LINK;?>/dashboard-module.gif) top left no-repeat; 
	margin: 0 8px 8px 0;  
	text-align: center; 
	color: #444444; 
	}
div.wysiwyg ul.panel li a { opacity: 0.6; display: block; width: 16px; height: 16px; background: url(<?php echo IMAGE_LINK;?>/jquery.wysiwyg.gif) no-repeat -64px -80px; border: 0; cursor: pointer; padding: 1px; }

/* ---------------------- New Menu Design Blueslate nav ---------------------- */
/*.blue #slatenav {
	background: transparent;
	display: block;
	font-family: "Trebuchet MS";
	font-size: 11px;
	font-weight: bold;
	position: relative;
	text-transform: capitalize; position:relative;
}
.blue #slatenav ul
{
	list-style-type: none;
	margin: 0px;
	padding: 0;
	width: auto;
}

.blue #slatenav li {
margin: 0 1px 0 0;
position: relative;
list-style: none;
display: inline-block;
}

.blue #slatenav li a {

	color: #404040;
	display: block;
	height: 30px;
	padding: 10px 12px 0 12px;
	text-decoration: none; font-size: 12px !important;
	text-align: left;
}
.blue #slatenav ul li a:hover
{
	background: transparent url(<?php echo IMAGE_LINK;?>/blueslate_backgroundOVER.gif) no-repeat top center;
	color: #fff;
}

.blue #slatenav li:hover .main {
color: #ee4e1d;
}

.blue #slatenav li .sub-nav-wrapper {
display: block;
position: absolute;
z-index: 30;
margin: 0px;
}

.blue #slatenav li .sub-nav-wrapper .sub-nav {
width:207px;
margin: 10px 0px 0px 0px;
background: #fff;
border-top: 1px solid #fff;

box-shadow: 0 1px 2px rgba(0,0,0,0.35);
-moz-box-shadow: 0 1px 2px rgba(0,0,0,0.35);
-webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.35);
}

.blue #slatenav li:hover .sub-nav-wrapper {
display: block;
}

.blue #slatenav li .sub-nav-wrapper .sub-nav li {
list-style: none;
display: block;
margin: 0;
padding: 0;
text-align: left;
border-bottom: 1px solid #d7d7d7;
}

.blue #slatenav li .sub-nav-wrapper .sub-nav li:first-child {
}

.blue #slatenav li .sub-nav-wrapper .sub-nav li:last-child {
border: none;
}

.blue #slatenav li .sub-nav-wrapper .sub-nav li a {
display: block;
padding: 0px 10px;
font-size: 12px;
font-weight: 600;
box-shadow: inset 0 0 2px rgba(255,255,255,1.0);
-moz-box-shadow: inset 0 0 2px rgba(255,255,255,1.0);
-webkit-box-shadow: inset 0 0 2px rgba(255,255,255,1.0); line-height:30px;
}

.blue #slatenav li .sub-nav-wrapper .sub-nav li:hover {
background: #f5f5f5; background-image:none;
border-bottom: 1px solid #3b3b3b; width:207px;
}

.blue #slatenav li .sub-nav-wrapper .sub-nav li {
transition: all 0.5s;
-moz-transition: all 0.5s;
-webkit-transition: all 0.5s;
}

.blue #slatenav li .sub-nav-wrapper {
pointer-events: none;
opacity: 0;
filter: alpha(opacity=0);
top: 0;

transition: all 0.35s ease-in-out;
-moz-transition: all 0.35s ease-in-out;
-webkit-transition: all 0.35s ease-in-out;
}

.blue #slatenav li:hover .sub-nav-wrapper {
pointer-events: auto;
opacity: 1;
filter: alpha(opacity=100);
top: 30px;
}*/
/* ---------------------- END Blueslate nav ---------------------- */

/* START: New Pull DOwn CSS*/
/* Some stylesheet reset */
.nav, .nav ul {
	margin: 0;
	padding: 0;
	list-style: none;
	line-height: 1;
}

/* The main container */
.nav {
	/* Layout and positioning */
	display: block;
	position: relative;
	height: 34px;
	/*width: 1330px;*/ /* CHANGE this if you want another width or remove it if you want the width of the container */
	border-radius: 3px;
	border: 1px solid #c1c1c1;

	/* Background and effects */
	background: #eaeaea; /* Background for IE9 and older browsers */
	background: -webkit-linear-gradient(bottom, #e1e1e1, #f4f4f4); /* Background for Chrome & Safari */
	background: -moz-linear-gradient(bottom, #e1e1e1, #f4f4f4); /* Background for Firefox */
	background: -o-linear-gradient(bottom, #e1e1e1, #f4f4f4); /* Background for Opera */
	background: -ms-linear-gradient(bottom, #e1e1e1, #f4f4f4); /* Background for Internet Explorer 10 */
	box-shadow: inset 0 1px 0 #fff;
}

.nav>li {
	display: block;
	position: relative;
	float: left;
	margin: 0;
	padding: 0 1px 0 0;
}

/* The main navigation links */
.nav>li>a {
	/* Layout */
	display: block;
	padding: 10px 10px;

	/* Typography */
	font-family: Helvetica, Arial, sans-serif;
	font-size: 12px;
	font-weight: normal;
	text-decoration: none;
	color: #000000;/*#9a9a9a;*/
	text-shadow: 0 1px 0 #fff;

	/* Effects */
	-webkit-transition: all .3s;
	-moz-transition: all .3s;
	-ms-transition: all .3s;
	-o-transition: all .3s;
	transition: all .3s;
}

/* The hover state of the navigation links */
.nav>li>a:hover, .nav>li:hover>a {
	background: #fff;
	background: rgba(255, 255, 255, .6);
	color: #000000;/*#999;*/
}

.nav>li:first-child>a {
	border-top-left-radius: 3px;
	border-bottom-left-radius: 3px;
}

.nav>.dropdown>a {
	padding-right: 26px;
}

/* The arrow indicating a dropdown menu */
.nav>.dropdown>a::after {
	 content: "";
	 position: absolute;
	 top: 14px;
	 right: 11px;
	 width: 4px;
	 height: 4px;
	 border-bottom: 1px solid #9a9a9a;
	 border-right: 1px solid #9a9a9a;
	 -webkit-transform: rotate(45deg);
	 -ms-transform: rotate(45deg);
	 -moz-transform: rotate(45deg);
	 -o-transform: rotate(45deg);
	}

/* Changing the color of the arrow on hover */
.nav>.dropdown>a:hover::after, .nav>.dropdown:hover>a::after {
	border-color: #999;
}

.nav ul {
	position: relative;
	position: absolute;
	left: -9999px;
	display: block;
	box-shadow: 0 1px 2px rgba(0, 0, 0, .1);
}

/* Level 1 submenus */
.nav>li>ul {
	padding-top: 0px;
	z-index: 99;
	border-top: 1px solid #c9c9c9;
	top: 32px;
}

/* Making the level 1 submenu to appear on hover */
.nav>li:hover>ul {
	left: -1px;
}

/* Level 2+ submenus */
.nav ul ul {
	left: -9999px;
	top: 0px;
	z-index: 999;
}

/* Making the level 2+ submenu to appear on hover */
.nav ul>li:hover>ul {
	left: 120px;
	top: -1px;
}

/* The submenu link containers */
.nav ul li {
	position: relative;
	display: block;
	border-left: 1px solid #c1c1c1;
	border-right: 1px solid #c1c1c1;

	/* Creating the slide effect. The list elements which contain the links have 0 height. On hover, they will expand */
	height: 0px;
	-webkit-transition: height .3s;
	-moz-transition: height .3s;
	-o-transition: height .3s;
	-ms-transition: height .3s;
}

/* Expanding the list elements which contain the links */
.nav li:hover>ul>li {
	height: 25px;
}

.nav ul li:hover>ul>li:first-child {
	height: 26px;
}

/* The links of the submenus */
.nav ul li a {
	/* Layout */
	display: block;
	width: 180px;
	padding: 6px 10px 6px 20px;
	border-bottom: 1px solid #e1e1e1;

	/* Typography */
	font-size: 12px;
	color: #000000;/*#a6a6a6;*/
	font-family: Helvetica, Arial, sans-serif;
	text-decoration: none;
	
	/* Background & effects */
	background: #fff;
	-webkit-transition: background .3s;
	-moz-transition: background .3s;
	-ms-transition: background .3s;
	-o-transition: background .3s;
	transition: background .3s;
}

/* The hover state of the links */
.nav ul li>a:hover, .nav ul li:hover>a {
	background: #e9e9e9;
	color: #000000;/*#a1a1a1;*/;
}

.nav ul ul>li:first-child>a {
	border-top: 1px solid #c1c1c1;
}

.nav ul>li:last-child>a {
	border-bottom: 1px solid #c1c1c1;
}


/* The arrow indicating a level 2+ submenu */
.nav ul>.dropdown>a::after {
	content: "";
	 position: absolute;
	 top: 10px;
	 right: 8px;
	 width: 4px;
	 height: 4px;
	 border-bottom: 1px solid #a6a6a6;
	 border-right: 1px solid #a6a6a6;
	 -webkit-transform: rotate(-45deg);
	 -ms-transform: rotate(-45deg);
	 -moz-transform: rotate(-45deg);
	 -o-transform: rotate(-45deg);
}

.nav ul>.dropdown:hover>a::after, .nav ul>.dropdown>a:hover::after {
	border-color: #a1a1a1;
}
/* END: New Pull DOwn CSS*/

</style>