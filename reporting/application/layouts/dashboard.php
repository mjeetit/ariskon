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
			/********************************************************************************
			function name modified to separate it from HRM and CRM to define its working on 
			same page custom class by jm on 16072018
			*********************************************************************************/
			//$MainModules  = $ObjModel->AdminModuleAndSubModule();
			$MainModules  = $ObjModel->AdminModuleAndSubModuleReporting();
			
			foreach($MainModules as $module){

				//$subModules = $ObjModel->getModules($module['module_id']);
				$subModules = $ObjModel->getModulesReporting($module['module_id']);
				$liClass = (count($subModules)>0) ? 'class="dropdown"' : '';
				?>
				<li <?php echo $liClass; ?>>
					<a href="<?php echo $this->url(array('controller'=>$module['module_controller'],'action'=>$module['module_action']),'default',true)?>"><?php echo  $module['module_name']?>		
					</a>
					<?php if(count($subModules)>0) { ?>
                    <ul>
					<?php foreach($subModules as $subModule) { 
	
					//$childModules = $ObjModel->getModules($subModule['module_id']);	
					$childModules = $ObjModel->getModulesReporting($subModule['module_id']);
					
					$lichildClass = (count($childModules)>0) ? 'class="dropdown"' : '';
					?>
					<li <?php echo $lichildClass; ?>>
						<a href="<?php echo $this->url(array('controller'=>$subModule['module_controller'],'action'=>$subModule['module_action']),'default',true)?>"><?php echo  $subModule['module_name']?></a>
                    <?php if(count($childModules)>0) { ?>
                    <ul>
					<?php foreach($childModules as $childModule) { ?>
                    <li>
						<a href="<?php echo $this->url(array('controller'=>$childModule['module_controller'],'action'=>$childModule['module_action']),'default',true)?>"><?php echo  $childModule['module_name']?></a></li>
                    <?php } ?>
                    </ul>
                    <?php } ?>
					</li>
				<?php } ?>
				</ul>
				<?php } ?>
			</li> 
		    <?php }?>		
            </ul>
    		<div style="clear: both;"></div>
 		</div> <!-- End #subnav -->
    </div> <!-- End #header -->
<div id="content">
		<div class="container_12">
            <!-- Dashboard icons -->
            <div class="grid_7">
			<?php
			  foreach($MainModules as $module){
			?>
            	<a href="<?php echo $this->url(array('controller'=>$module['module_controller'],'action'=>$module['module_action']),'default',true)?>" class="dashboard-module">
                	<img src="<?php echo IMAGE_LINK.'/'.$module['module_icon'];?>" width="64" height="64" alt="edit" />
                	<span><?php echo  $module['module_name']?></span>
                </a>
			<?php }?>	
                <div style="clear: both"></div>
            </div> <!-- End .grid_7 -->
            
           <?php echo $this->layout()->content; ?>
            <div style="clear:both;"></div>
             </div>          
            <div style="clear:both;"></div>
</div>
<?php include 'footer.php';?>
<!--Footer Include Here -->
