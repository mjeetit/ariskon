<div class="grid_12">  
		<!-- Example table -->
		<div class="module">  
			<h2><span>Expense List</span></h2>
			
			<div class="module-table-body"> 
				
				<table id="myTable" class="tablesorter" style="border:none">
					<thead>
					<tr><td colspan="4">
					 <a href="<?php echo $this->url(array('controller'=>'Expense','action'=>'expenselist'),'default',true)?>" class="button">
							 <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
					</td>
					</tr>
					<tr> 
					<td style="border:none" align="center">
					<form action="" method="post" id="expenseform">	
					 <table style="width:70%"> 
					 <tbody>
					   <tr>
					   <th>Users List</th>
						<th>Expense Title</th>
						<th>Expense Amount</th>
						<th>Travel Destination</th>
						<th>Fare</th>
						<th>Expense Detail</th>
						<th>Expense Date</th>
						</tr> 	
					   			   
						
						
						
						<tr class="even">
						<td>
						<select name="user_id" id="user_id">
						<option value="">--Select User--</option>
						<?php foreach($this->userslist as $users){?>
						 <option value="<?php echo $users['user_id']?>"><?php echo $users['first_name'].' '.$users['last_name'].'-'.$users['employee_code'];?></option>
						 <?php } ?>
						</select>
						</td>
						<td>
						<input type="hidden" name="expense_type" value="1" />
						<select name="head_id" id="head_id" onchange="getExpenseAmount(this.value);">
						<option value="">--Select Allowence--</option>
						<?php foreach($this->expensehead as $expensehead){?>
						 <option value="<?php echo $expensehead['head_id']?>"><?php echo $expensehead['head_name']?></option>
						 <?php } ?>
						</select>
						<br /><span id="headerror" class="error" style="display:none"></span>
						</td>
						<?php $readonly='';
						//if($this->userinfo['expense_type']==1){ $readonly = 'readonly="readonly"';}?>
						<td><input type="text" name="expense_amount" id="expense_amount" class="input-medium"  /></td>
						<td><input type="text" name="travel_destination" id="travel_destination" class="input-medium" /></td>
						<td><input type="text" name="fare" id="fare" class="input-medium"  /></td>
						<td>
						<textarea name="expense_detail" id="expense_detail" class="input-medium"></textarea></td>
						<td><input type="text" name="expense_date" id="expense_date" class="input-medium"  />
						<br /><span id="dateerror"  class="error" style="display:none"></span></td>
						
					 </tr>
					 <tr class="odd"><td colspan="6" align="right"><input type="submit"  name="Addexpense" value="Add Expense" class="submit-green" /></td></tr>
					
					 </tbody>
					</table>
					 </form>
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
$(function() {
		$("#expense_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		$("#filter_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		
	});


</script>
