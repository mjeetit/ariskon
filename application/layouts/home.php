<!--Header Include Here -->
<?php include 'header.php';?>
 <!-- Sub navigation -->
            <div id="subnav">
                <div class="container_12">
                    <div id="nav">
					 <ul>
					  <li><a href="<?php echo $this->url(array('controller'=>'Home'),'default',true)?>">Dashboard</a></li> 
					<?php /*?><?php 
					  $ObjModel =new Zend_Custom();
					  $MainModules  = $ObjModel->AdminModuleAndSubModule();
					  foreach($MainModules as $module){
					  ?>
                     <li><a href="<?php echo $this->url(array('controller'=>$module['module_controller'],'action'=>$module['module_action']),'default',true)?>"><?php echo  $module['module_name']?></a></li> 
					  <?php }?>		
                        </ul><?php */?>
                        
                    </div><!-- End. .grid_12-->
                </div><!-- End. .container_12 -->
                <div style="clear: both;"></div>
            </div> <!-- End #subnav -->
        </div> <!-- End #header -->
<div id="content">
		<div class="container_12">
            <!-- Dashboard icons -->
            <div class="grid_7">
    			<a href="<?php echo $this->url(array('controller'=>'dashboard'),'default',true)?>" class="dashboard-module">
                	<img src="<?php echo IMAGE_LINK.'/hrm-icon.png';?>" width="64" height="64" alt="edit" />
                	<span>HRM</span>
                </a>
		
                <?php if(!in_array($_SESSION['AdminLoginID'],array(110,143,31))){?>		
    				<!-- <a href="http://www.ariskon.jclifecare.com/crm/Dashboard?tocken= -->

                    <!-- comment to remove tocken variable from the url of crm module by jm 12072018 
                    <a href="http://localhost/ariskon/crm/Dashboard?tocken=<?php //echo base64_encode($_SESSION['AdminLoginID']);?>"  class="dashboard-module"> -->

                    <a href="http://localhost/ariskon/crm/Dashboard" class="dashboard-module">
                    	<img src="<?php echo IMAGE_LINK.'/crm-icon.png'?>" width="64" height="64" alt="edit" />
                    	<span>CRM</span>
                    </a>
    				
    				<!-- <a href="http://www.ariskon.jclifecare.com/reporting/Dashboard?tocken= -->
                    
                    <!-- comment to remove tocken variable from the url of reporting module by jm 12072018
                    <a href="http://localhost/ariskon/reporting/Dashboard?tocken=<?php //echo base64_encode($_SESSION['AdminLoginID']);?>"  class="dashboard-module"> -->

                    <a href="http://localhost/ariskon/reporting/Dashboard" class="dashboard-module">
                    	<img src="<?php echo IMAGE_LINK.'/Mobile-icon.png';?>" width="64" height="64" alt="edit" />
                    	<span>Reporting</span>
                    </a>
                
				<?php } ?>	
                <div style="clear: both"></div>
            </div> <!-- End .grid_7 -->
            
           <?php echo $this->layout()->content; ?>
            <div style="clear:both;"></div>
             </div>          
            <div style="clear:both;"></div>
</div>
<?php include 'footer.php';?>
<!--Footer Include Here -->
<script>
function getlocatio(){
 // window.location.href = 'www.crmdemo.jclifecare.com';
  window.location.href = 'http://localhost/ariskon';
}
</script>
