<!--Header Include Here -->
<?php include 'header.php';?>
 <!-- Sub navigation -->
            <?php /* ?><div id="subnav">
                <div class="container_12">
                    <div id="nav">
					 <ul>
					  <li><a href="<?php echo $this->url(array('controller'=>'Dashboard'),'default',true)?>">Dashboard</a></li> 
					<?php 
					  $ObjModel = new Zend_Custom();
					  $MainModules  = $ObjModel->AdminModuleAndSubModule();
					  foreach($MainModules as $module){
					  $class = '';
					   if(trim($module['module_action'])==trim(Bootstrap::$ActionName)){
					      $class = 'class="current";';
					   }
					  ?>
                     <li><a href="<?php echo $this->url(array('controller'=>$module['module_controller'],'action'=>$module['module_action']),'default',true)?>"  <?php echo $class;?>><?php //echo  $module['module_name']?></a></li> 
					  <?php }?>		
                        </ul>
                        
                    </div><!-- End. .grid_12-->
                </div><!-- End. .container_12 --><?php */ ?>

				<ul class="nav">
					<li>
						<!-- <a href="http://www.ariskon.jclifecare.com/Home">Main Dashboard</a> -->
						<a href="http://localhost/ariskon/Home">Main Dashboard</a>
					</li>
					  <li><a href="<?php echo $this->url(array('controller'=>'Dashboard'),'default',true)?>">Dashboard</a></li> 
					<?php 
					  $ObjModel =new Zend_Custom();
					  $MainModules  = $ObjModel->getModules();//echo "<pre>";print_r($MainModules);echo "<pre>";die;
					  foreach($MainModules as $module){
						  $subModules = $ObjModel->getModules($module['module_id']);//echo "<pre>";print_r($subModules);echo "<pre>";die;
						  $liClass = (count($subModules)>0) ? 'class="dropdown"' : '';
					  ?>
						<li <?php echo $liClass; ?>>
							<a href="<?php echo $this->url(array('controller'=>$module['module_controller'],'action'=>$module['module_action']),'default',true)?>"><?php echo  $module['module_name']?></a>
							<?php 
								//if($_SESSION['AdminLoginID']==1){
								if(count($subModules)>0) {
							?>
								<!--<div class="sub-nav-wrapper">
								<ul class="sub-nav">--><ul>
								<?php foreach($subModules as $subModule) { ?>
								<li>
								<?php
									//$sub_url = $this->url(array('controller'=>$sub_module['module_controller'], 'action' => $sub_module['module_action']), 'default', true);
									//$is_sub_privilege = $this->ModelbObj->checkUser_submodule_Privilege($sub_module['module_id']);
									//$sub_href = ($is_sub_privilege == 'privilege found') ? 'href="' . $sub_url . '"' : '';
								?>
									<!-- <a <?php echo $sub_href; ?> ><?php echo $sub_module['module_name']; ?></a> -->
									<a href="<?php echo $this->url(array('controller'=>$subModule['module_controller'],'action'=>$subModule['module_action']),'default',true)?>"><?php echo  $subModule['module_name']?></a>
								</li>
							<?php } ?>
							</ul><!--</div>-->
							<?php } //} ?>
							<!--<div class="sub-nav-wrapper"><ul class="sub-nav"><li>Check Sub-Module</li></ul></div>-->
						</li> 
					  <?php }?>		
                        </ul> 

                <div style="clear: both;"></div>
            </div> <!-- End #subnav -->
        </div> <!-- End #header -->
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
