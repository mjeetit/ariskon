<div class="grid_12">  
	<!-- Example table -->
	<div class="module">  
		<h2><span>Expense Setting</span></h2>
			
		<div class="module-table-body"> 
			<form action="" method="post">
				<table id="myTable" class="tablesorter" style="border:none">
					<thead>
					<tr>
						<td colspan="5">
							<a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'expense'),'default',true)?>" class="button add">
                        	<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back"/></span>
                        	</a>
                        </td>
					</tr>
					<tr> 
						<td style="border:none" align="center">
					 		<table style="width:70%">
					 			<tbody>
					     			<tr class="odd"> 
										<td>Business Unit</td> 
										<td> 
											<select name="bunit_id" id="bunit_id" onchange="changeStatusBusiness(this.value,'');"> 
							    				<option value="">--Select--</option>
									 			<?php foreach($this->ObjModel->getBusinessToCompany() as $bunits){
									 
									 				$selected = '';
									 
									 				if($this->ExpSett['bunit_id']==$bunits['bunit_id'])
									 				{
									   	   				$selected = 'selected="selected"';
									 				}?>
									  				<option value="<?php echo $bunits['bunit_id'];?>" <?php echo $selected;?>><?php echo $bunits['bunit_name']?></option><?php }?>
											</select>
										</td> 
									</tr> 

									<tr class="even">
										<td>Department</td>
										<td>
											<select name="department_id" id="department_id" onchange="changeStatusDepartment(this.value,'');">
								   				<option value="">--Select--</option>
											</select>
										</td>
									</tr>
						
									<tr class="odd">
										<td>Designation</td>
										<td>
											<select name="designation_id" id="designation_id" onchange="changeStatusdesignation(this.value,'');">
										    	<option value="">---Select--</option>
											</select>
										</td>
									</tr>
									<tr class="even">
										<td>Number Of Approve Authority</td>
										<td>
											<input type="text" name="number_of_approval" class="input-short" value="<?php echo $this->ExpSett['number_of_approval'];?>" />
										</td>
									</tr>

									<tr class="odd">
										<td>Expense Type</td>
										<td>
											<select name="expense_type" onchange="expsetting(this.value,'')" class="input-medium">
								  				<option value="">--Seletct--</option>
								 				<option value="1" <?php if($this->ExpSett['expense_type']==1){ echo 'selected="selected"';}?>>Actual Expense</option>
								 				<option value="2" <?php if($this->ExpSett['expense_type']==2){ echo 'selected="selected"';}?>>Fixed Expense</option>
								  				<option value="3" <?php if($this->ExpSett['expense_type']==3){ echo 'selected="selected"';}?>>Mixed Expense</option>
											</select>
										</td>
									</tr>
									<tr>
										<th>Expense Head</th>
										<th>Expense Amount</th>
									</tr>
									<tr>
										<td colspan="2">
						 					<table id="expense_table"></table>
										</td>
									</tr>	
									<tr>
										<td colspan="2">
											<input type="checkbox" name="updateemp"/>&nbsp;&nbsp;Do You Want To Update Employee Expense Template 
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="submit" name="submit" value="Add Setting"  class="submit-green"/>
										</td>
									</tr>		
					 			</tbody>
							</table>
				  		</td>
				 	</tr>
			   	</thead>
			</table>
			<div style="clear: both"></div>
		</div>  <!-- End .module-table-body -->
	</div>  
	<!-- End .module -->
</div>  <!-- End .grid_12 --> 
<script type="text/javascript">
/*function expsetting(exp_type){
   if(exp_type==2 || exp_type==3){
       $("#traveling").show();
	   $("#mobile").show();
   }else{
	  $("#mobile").hide();
	  $("#traveling").hide();
   }
}*/

changeStatusBusiness('<?php echo $this->ExpSett['bunit_id'];?>','<?php echo $this->ExpSett['department_id'];?>');

changeStatusDepartment('<?php echo $this->ExpSett['department_id'];?>','<?php echo $this->ExpSett['designation_id'];?>');
changeStatusdesignation('<?php echo $this->ExpSett['designation_id'];?>','');
expsetting('<?php echo $this->ExpSett['expense_type'];?>','<?php echo $this->ExpSett['exp_setting_id'];?>');
</script>