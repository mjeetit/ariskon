	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Stockist Vist</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="get">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td>Patch <select name="patch_id">
							<option value="">-Select-</option>
							<?php foreach($this->patchlists as $patch){ ?>
							 <option value="<?php echo $patch['patch_id'];?>"><?php echo $patch['patch_name']?></option>
							<?php } ?>
							</select></td>
							
							<td colspan="3">From<input type="text" name="from" id="from" />To<input type="text" name="to_date" id="to_date" /></td>
							<td><input type="submit" name="search" value="Search" /></td>
							</tr>
						<tr>
						<th>Employee Name</th>
						<th>Employee Code</th>
						<th>Patch Name</th>
						<th>Work With</th>
						<?php if($this->filter['Mode']=='Doctor'){?>
						<th>Doctor</th>
						<th>Product Promoted</th>
							<?php }elseif($this->filter['Mode']=='Chemist'){?>
							<th>Chemist</th>
						<th>RCPA</th>
							<?php } ?>
						<th>Call Date</th>
						<th>Action</th>
						</tr>
						<?php if(!empty($this->vistdetail)){ 
						foreach($this->vistdetail as $i=>$detail){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $detail['first_name']." ".$detail['last_name']?></td>
						 <td><?php echo $detail['employee_code']?></td>
						 <td><?php echo $detail['patch_name']?></td>
						 <td><?php echo $this->ObjModel->visitWithdetail($detail);?></td>
                         <td><?php echo $detail['doctor_name'];?></td>
						 <td><?php echo $this->ObjModel->productunitdetail($detail);?></td>
						 <td><?php echo date('d-M-Y',strtotime($detail['call_date']))?></td>
						 <td align="center">
						 <a href="#"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
						 </td>
						 </tr>
						 <?php } }?>
                            </thead>
                        </table>
                       
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 --> 
