<div class="grid_12">  
		<!-- Example table -->
		<div class="module">  
			<h2><span>Expense List</span></h2>
			
			<div class="module-table-body"> 
				<table id="myTable" class="tablesorter" style="border:none">
					<thead> 
					
					<tr>   
					<td style="border:none" align="center"> 
					       <form action="" method="post">    		
						
						
					 <table style="width:90%">
					 <tbody>			   
						<tr>
						<th>#</th>
						<th>Employee Name</th>
						<th>Employee Code</th>
						<th>Desigation</th>
						<th>Heaquater</th>
						<th>Expense Amount</th>
						<th>Salary Month</th>
						</tr>
						<?php if(!empty($this->salaryexpense)){ 
					foreach($this->salaryexpense as $i=>$expenses){
					 $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
							<td><input type="checkbox" name="user_id[]" value="<?php echo $expenses['user_id']?>" /><input type="hidden" name="salary_date" value="<?php echo $expenses['date']?>" /></td>
						 <td><?php echo $expenses['first_name'].' '.$expenses['last_name'];?></td>
						<td><?php echo $expenses['employee_code'];?></td>
						<td><?php echo $expenses['designation_name']?></td>
						<td><?php echo $expenses['headquater_name']?></td>
						<td><input type="text" name="amount[<?php echo $expenses['user_id']?>]" value="<?php echo $expenses['amount']?>" class="input-medium" /></td>
						<td><?php echo date('F Y',strtotime($expenses['date']));?></td>
						</tr>
						   <?php }?>
						   <tr><td colspan="7" align="right"><input type="submit" name="updateexp" value="Update" class="submit-green" /></td></tr>
						   <?php }?>
						
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
                $("#filter_date1").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		
	});


</script>
