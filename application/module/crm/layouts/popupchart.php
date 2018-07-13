<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<title>Charting</title>
	<link href="<?=Bootstrap::$baseUrl?>javascript/javascript/charting/css/basic.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/enhance.js"></script><!-- Graph and Chart JS-->	
	<script type="text/javascript">
		// Run capabilities test
		enhance({
			loadScripts: [
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/excanvas.js',
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/jquery.min.js',
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/visualize.jQuery.js',
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/example.js'
			],
			loadStyles: [
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/css/visualize.css',
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/css/visualize-light.css'
			]	
		});   
    </script>
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
