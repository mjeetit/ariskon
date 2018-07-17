<!--Header Include Here -->
<?php include 'header.php';?>
	<ul class="nav">
		<li>
			<!-- <a href="http://www.ariskon.jclifecare.com/Home">Main Dashboard</a> -->
			<a href="http://localhost/ariskon/Home">Main Dashboard</a>
		</li>
		<li>
			<a href="<?php echo $this->url(array('controller'=>'Dashboard'),'default',true)?>">Dashboard</a>
		</li> 
		<?php 
		 	$ObjModel =new Zend_Custom();
			/***********************************************************************
			getModule function name modified to make it separate to define in 
			custom class file in library by jm on 16072018
			***********************************************************************/
		  	//$MainModules  = $ObjModel->getModules();
		  	$MainModules  = $ObjModel->getModulesHRM();
			
			foreach($MainModules as $module){
			  	//$subModules = $ObjModel->getModules($module['module_id']);
			  	$subModules = $ObjModel->getModulesHRM($module['module_id']);
			  	
			  	$liClass = (count($subModules)>0) ? 'class="dropdown"' : '';
		  	?>
			<li <?php echo $liClass; ?>>
				<a href="<?php echo $this->url(array('controller'=>$module['module_controller'],'action'=>$module['module_action']),'default',true)?>"><?php echo  $module['module_name']?>
				</a>
				<?php 
					if(count($subModules)>0) {
				?>
				<ul>
				<?php foreach($subModules as $subModule) { ?>
					<li>
						<a href="<?php echo $this->url(array('controller'=>$subModule['module_controller'],'action'=>$subModule['module_action']),'default',true)?>"><?php echo  $subModule['module_name']?></a>
					</li>
				<?php } ?>
				</ul>
				<?php } ?>
			</li> 
		  	<?php }?>		
        </ul> 
	<div style="clear: both;"></div>   
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
<?php include 'footer.php';?>
<!--Footer Include Here -->