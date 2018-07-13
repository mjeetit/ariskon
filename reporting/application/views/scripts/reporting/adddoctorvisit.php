	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Doctor Vist</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="get" enctype="multipart/form-data">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
								<th colspan="2" align="center">Visit With</th>
							</tr>
							
							<?php if(!empty($this->zbmvisit)){ ?>
							<tr><td>ZBM List
							  <select name="zbm_visit">
							   <?php  foreach($this->zbmvisit as $key=>$zbm){?>
							   <option value="<?php echo $zbm['user_id']?>"><?php echo $zbm['name']?></option>
							   <?php }?>
							 </select>
							 </td></tr>
							<?php }?>
							<?php if(!empty($this->rbmvisit)){ ?>
							<tr><td>RBM List
							  <select name="rbm_visit">
							   <?php  foreach($this->rbmvisit as $key=>$rbm){?>
							   <option value="<?php echo $rbm['user_id']?>"><?php echo $rbm['name']?></option>
							   <?php }?>
							 </select>
							 </td></tr>
							<?php }?>
							<?php if(!empty($this->zbmvisit)){ ?>
							<tr><td>ABM List
							  <select name="abm_visit">
							   <?php  foreach($this->abmvisit as $key=>$abm){?>
							   <option value="<?php echo $abm['user_id']?>"><?php echo $abm['name']?></option>
							   <?php }?>
							 </select>
							 </td></tr>
							<?php }?>
							<?php if(!empty($this->bevisit)){ ?>
							<tr><td>BE List
							  <select name="be_visit">
							   <?php  foreach($this->bevisit as $key=>$belist){?>
							   <option value="<?php echo $belist['user_id']?>"><?php echo $belist['name']?></option>
							   <?php }?>
							 </select>
							 </td></tr>
							<?php }?>
							</td></tr>
                            </thead>
                        </table>
                      </form> 
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 --> 
