<div class="grid_12">
	<div class="module">
		<h2><span>New Event</span></h2>
		<div class="module-body">
            <form name="data_setting" action="" method="post"> 
				<table width="70%" style="border:none">
					<thead>
					<tr>
						<td align="center" style="border:none">
							<table style=" width:70%">
								<thead>
									<tr>
										<td colspan="2" align="left" style="border:none">
											<a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addexpense'),'default',true)?>" class="button back">
												<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
											</a>
										</td>
									</tr>
									<tr>
									<?php if($this->mode=='Add'){?>
										<th colspan="2">Add  Expense Head</th>
						    		</tr>
									<tr class="odd">
										<td>Expense Head Name</td>
										<td>
											<input name="head_name" type="text" class="input-medium" />
										</td>
									</tr>
									<tr class="even">
										<td>Expense Type</td>
										<td> 
											<select name="expense_type" class="input-medium">
												<option value="1">Actual Expense</option>
												<option value="2">Fixed Expense</option>
												<option value="3">Actual+Fixed Expense</option>
										  	</select>
										</td>
									</tr>
									<tr class="odd">
										<td>Including Salary Head</td>
										<td>
										<?php  $salaryheads = $this->ObjModel->getAllSalaryHead();?>
										   <select name="salary_head" class="input-medium">
										   <option value="">--Select--</option>
										     <?php foreach($salaryheads as $head){?>
												 <option value="<?php echo $head['salaryhead_id']?>"><?php echo $head['salary_title'];?></option>
												<?php } ?>
										  </select>
											</td>
							  			</tr>
										<tr class="odd"><td>Repeated In Month</td>
										<td>
										<select name="no_of_times" class="input-medium">
										<option value="1">Multi Times</option>
										<option value="2">One Time</option>
										</select>
										</td>
										</tr>
										<tr> <td colspan="2" align="center"><input type="submit" name="add" value="Add" class="submit-green" /></td></tr>
								  <?php } ?>	
								  		
										<?php if($this->mode=='Edit'){?>
										<th colspan="2">Edit Expense Head</th>
						    			</tr>
										<tr class="odd"><td>Expense Head Name</td><td><input name="head_name" type="text" class="input-medium" value="<?php echo $this->edithead['head_name']?>" /></td></tr>
										<tr class="even"><td>Expense Type</td><td> <select name="expense_type" class="input-medium">
												 <option value="1" <?php if($this->edithead['expense_type']==1){ echo 'selected="selected"';}?>>Actual Expense</option>
												 <option value="2" <?php if($this->edithead['expense_type']==2){ echo 'selected="selected"';}?>>Fixed Expense</option>
												  <option value="3" <?php if($this->edithead['expense_type']==3){ echo 'selected="selected"';}?>>Mixed Expense</option>
										  </select></td></tr>
										<tr class="odd"><td>Including Salary Head</td><td>
										<?php  $salaryheads = $this->ObjModel->getAllSalaryHead();?>
										   <select name="salary_head" class="input-medium">
										   <option value="">--Select--</option>
										     <?php foreach($salaryheads as $head){
											  $selected = '';
											  if($this->edithead['salary_head']==$head['salaryhead_id']){
											    $selected = 'selected="selected"';
											  }
											   ?>
												 <option value="<?php echo $head['salaryhead_id']?>" <?php echo $selected;?>><?php echo $head['salary_title'];?></option>
												<?php } ?>
										  </select>
											</td>
							  			</tr>
										<tr class="odd"><td>Repeated In Month</td>
										<td>
										<select name="no_of_times" class="input-medium">
										<option value="1"  <?php if($this->edithead['no_of_times']==1){ echo 'selected="selected"';}?>>Multi Times</option>
										<option value="2"  <?php if($this->edithead['no_of_times']==2){ echo 'selected="selected"';}?>>One Time</option>
										</select>
										</td>
										</tr>
										<tr> <td colspan="2" align="center"><input type="submit" name="Update" value="Update" class="submit-green" /></td></tr>
								  <?php } ?>	
				</thead>
			</table>
		</td>
	</tr>
</thead>
</table>
	</form>
 </div> <!-- End .module-body -->

</div>  <!-- End .module -->
<div style="clear:both;"></div>
</div>					   
