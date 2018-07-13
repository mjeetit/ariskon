	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Chemist Vist</span></h2>
                    
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
							<td><input type="submit" name="submit" value="Search" /></td>
							</tr>
						<tr>
						<th>Employee Name</th>
						<th>Employee Code</th>
                        <th>Designation</th>
						<th>Headquater</th>
						<th>Call Average</th>
						<th>Month</th>
						<th>Action</th>
						</tr>
						<?php if(!empty($this->vistdetail)){ 
						foreach($this->vistdetail as $i=>$chemistvisit){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $chemistvisit['first_name'].' '.$chemistvisit['last_name']?></td>
						 <td><?php echo $chemistvisit['employee_code']?></td>
                         <td><?php echo $chemistvisit['designation_name'];?></td>
						 <td><?php echo $chemistvisit['headquater_name'];?></td>
						 <td><?php echo $chemistvisit['CNT']/$chemistvisit['DAY_CNT'];?></td>
						 <td><?php echo date('M-Y',strtotime($chemistvisit['visit_month']))?></td>
						 <td align="center">
						 <a href="<?php echo $this->url(array('controller'=>'Reporting','action'=>'repordetail','user_id'=>$chemistvisit['user_id'],'Mode'=>'Chemist'));?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
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
