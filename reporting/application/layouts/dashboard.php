<!--Header Include Here -->
<?php include 'header.php';?>
 <!-- Sub navigation -->
            <!--<div id="subnav"><div class="container_12"><div id="nav">
			<div id="subnav"><div id="subnav1"><div class="blue"><div id="slatenav">-->

					 <ul class="nav">
					  <li>
					  	<!-- <a href="http://www.ariskon.jclifecare.com/Home">Main Dashboard</a> -->
						<a href="http://localhost/ariskon/Home">Main Dashboard</a>				 
					  </li> 
				          <li><a href="<?php echo $this->url(array('controller'=>'Dashboard'),'default',true)?>">Dashboard</a></li> 
					<?php 
					  $ObjModel =new Zend_Custom();
					  $MainModules  = $ObjModel->AdminModuleAndSubModule();//echo "<pre>";print_r($MainModules);echo "<pre>";die;
					  foreach($MainModules as $module){
						  $subModules = $ObjModel->getModules($module['module_id']);//echo "<pre>";print_r($subModules);echo "<pre>";die;
						  $liClass = (count($subModules)>0) ? 'class="dropdown"' : '';
					  ?>
						<li <?php echo $liClass; ?>>
							<a href="<?php echo $this->url(array('controller'=>$module['module_controller'],'action'=>$module['module_action']),'default',true)?>"><?php echo  $module['module_name']?></a>
							<?php if(count($subModules)>0) { ?>
                                <ul>
								<?php foreach($subModules as $subModule) { 
								$childModules = $ObjModel->getModules($subModule['module_id']);
								//if($subModule['module_id']==160) {print_r($childModules);die; }
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
                       <!--</div></div>-->
                    <!--</div> End. .grid_12-->
                <!--</div> End. .container_12 -->
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
