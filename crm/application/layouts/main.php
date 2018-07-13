<!--Header Include Here -->
<?php include 'header.php';?>
 <!-- Sub navigation -->
            <ul class="nav">
				<li>
					<!-- <a href="http://www.ariskon.jclifecare.com/Home">Main Dashboard</a> -->
					<a href="http://localhost/ariskon/Home">Main Dashboard</a>
				</li>
				<li><a href="<?=$this->url(array('controller'=>'Dashboard'),'default',true)?>">Dashboard</a></li> 
				<?php 
					$menuItems = Bootstrap::$menuPrivilege;
					$menus = (isset($menuItems['Names'])) ? $menuItems['Names'] : array();
					if(count($menus) > 0) { 
						foreach($menus as $menu) {
							$subModules = $menu['submodule'];
						  	$liClass = (count($subModules)>0) ? 'class="dropdown"' : '';	
				?>
					<li <?=$liClass?>>
						<a href="<?=$this->url(array('controller'=>$menu['controller'],'action'=>$menu['action']),'default',true)?>"><?=$menu['name']?></a>
						<?php if(count($subModules) > 0) { ?>
						<ul>
							<?php foreach($subModules as $submodule) { ?>
								<li>
									<a href="<?=$this->url(array('controller'=>$submodule['module_controller'],'action'=>$submodule['module_action']),'default',true)?>"><?=$submodule['module_name']?></a>
									</li>
							<?php } ?>
						</ul>
						<?php } ?>
					</li>
				<?php } } ?>	
            </ul> 

                <div style="clear: both;"></div>
            </div> <!-- End #subnav -->
        </div> <!-- End #header -->
<div id="content">
		<div class="container_12">
		<br/>
		<br/>
		<?php if(isset($_SESSION[SUCCESS_MSG])){ ?>
				   <span class="notification n-success"><?=Bootstrap::showMessage()?></span>
		<?php } if(isset($_SESSION[ERROR_MSG])){ ?>
				 <span class="notification n-error"><?=Bootstrap::showMessage()?></span>   
		 <?php }?>
			<?=$this->layout()->content?>
            <div style="clear:both;"></div>
  </div>          
 <div style="clear:both;"></div>
</div>
<?php include 'footer.php';?><!--Footer Include Here -->