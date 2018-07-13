	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Active Tour Plan</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="get" enctype="multipart/form-data">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td>Employee <select name="user_id">
							<option value="">-Select-</option>
							<?php foreach($this->allusers as $users){
							  if($users['designation_id']>4 && $users['designation_id']<9){?>
							 <option value="<?php echo $users['user_id'];?>"><?php echo $users['first_name']." ".$users['last_name']?></option>
							<?php }} ?>
							</select></td>
							
							<td>Headquater <select name="hedquater_id">
							<option value="">-Select-</option>
							<?php foreach($this->headquater as $hedquaters){ ?>
							
							 <option value="<?php echo $hedquaters['headquater_id'];?>"><?php echo $hedquaters['headquater_name']?></option>
							<?php }?>
							</select></td>
							<?php if($_SESSION['AdminLoginID']==1){?>
							<td><input type="submit" name="Approved" value="Admin Approval" /></td>
							<td><input type="submit" name="Export" value="Export" /></td>
							<?php }?>
							</tr>
						<tr>
						<th style="width:1px">#</th>
						<th>HQ</th>
						<th>City/Town</th>
						<th>Patch Name</th>
						<th>BE</th>
						<th>ABM</th>
						<th>RBM</th>
						<th>ZBM</th>
						<th>Approved By</th>
						<th>Call Date</th>
						<th>Call Type</th>
						</tr>
						<?php if(!empty($this->vistdetail)){ 
						foreach($this->vistdetail as $i=>$doctorvisit){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						 <td><input type="checkbox" name="approval_id[]" value="<?php echo $doctorvisit['approval_id'];?>" /></td>
						 <td><?php echo $doctorvisit['headquater_name'];?></td>
						  <td><?php echo $doctorvisit['city_name']?></td>
						 <td><?php echo $doctorvisit['patch_name']?></td>
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['be_visit']);?></td>
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['abm_visit']);?></td>
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['rbm_visit']);?></td>
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['zbm_visit']);?></td>
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['accepte_by']);?></td>
						 <td><?php echo $doctorvisit['tour_date'];?></td>
						 <td><?php echo ($doctorvisit['call_type']=='1')?"Non-Call":'call';?></td>
						 
						 </tr>
						 <?php } }?>
                            </thead>
                        </table>
                       
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 --> 
