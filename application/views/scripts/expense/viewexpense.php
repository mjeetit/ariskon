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
					       <form action="" method="get">		
							<table style="width:70%">
							<tr>
							 <td>Select Head</td>
							 <td><?php  echo $this->htmlSelect('filter_head',CommonFunction::getAssociative($this->filterHead,'head_id','head_name'),$this->filter['filter_head'],array('class'=>'input-medium'));?></td>
							 <td>From Date&nbsp;<input name="filter_date" id="filter_date" value="<?php echo $this->filter['filter_date']?>"  class="input-short" /></td>
                                                         <td>To Date&nbsp;<input name="filter_date1" id="filter_date1" value="<?php echo $this->filter['filter_date1']?>"  class="input-short" /></td>
							 <td><input type="submit" name="search" class="submit-green" value="Search" /></td>
							</tr>
						 </table>
						 </form>
					 <table style="width:70%">
					 <tbody>			   
						<tr>
						<th>Expense Title</th>
						<th>Expense Amount</th>
						<th>Travel Destination</th>
						<th>Fare</th>
						<th>Expense Detail</th>
						<th>Expense Date</th>
						<th>Action</th>
						</tr>
						<?php if(!empty($this->currentexpense)){ 
					foreach($this->currentexpense as $i=>$expenses){
					 $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
							
						 <td><?php echo $expenses['head_name']?></td>
						<td><?php echo $expenses['expense_amount'];if($expenses['mixed_amount']>0){ echo "<br>Mixed:".$expenses['mixed_amount'];}?></td>
						<td><?php echo $expenses['travel_destination']?></td>
						<td><?php echo $expenses['fare']?></td>
						<td><?php echo $expenses['expense_detail']?></td>
						<td><?php echo $expenses['expense_date']?></td>
						<td>
						<?php 
						 if($expenses['approve_status']=='1' || $expenses['salary_status']=='1' || preg_match('/[1-9]/',$expenses['approve_by'])=='1'){ 
						  echo 'Approved';
						} else{?>
<!--						  <a  id="editbtn<?php echo $i;?>" onclick="showhideeditexpense('<?php echo $i;?>','1');">
						 <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/edit.png" title="Edit"  /></a>-->
                                                 <?php
                                                    echo 'Pending';
						 } ?>
						 </td>
						</tr>
						<tr class="<?php echo  $class;?>" id="previousexptr<?php echo $i;?>" style="display:none">
						  <td>
						<input type="hidden" name="expense_id<?php echo $i;?>" id="expense_id<?php echo $i;?>" value="<?php echo $expenses['expense_id'];?>" />
						<select name="head_id<?php echo $i;?>" id="head_id<?php echo $i;?>" onchange="getViewExpenseAmount(this.value,'<?php echo $i;?>');">
						<option value="">--Select Site--</option>
						<?php foreach($this->expensehead as $expensehead){
						  $selected = '';
						  if($expensehead['head_id']==$expenses['head_id']){
						    $selected = 'selected="selected"';
						  }
						  ?>
						 <option value="<?php echo $expensehead['head_id']?>" <?php echo $selected;?>><?php echo $expensehead['head_name']?></option>
						 <?php } ?>
						</select>
						<br /><span id="headerror<?php echo $i;?>" class="error" style="display:none"></span>
						</td>
						<td><input name="expense_amount<?php echo $i;?>" id="expense_amount<?php echo $i;?>" class="input-medium" readonly="readonly" value="<?php echo $expenses['expense_amount'];?>" /></td>
						<td><input name="travel_destination<?php echo $i;?>" id="travel_destination<?php echo $i;?>" class="input-medium" readonly="readonly" value="<?php echo $expenses['travel_destination'];?>" /></td>
						<td><input name="fare<?php echo $i;?>" id="fare<?php echo $i;?>" class="input-medium" readonly="readonly" value="<?php echo $expenses['fare'];?>" /></td>
						<td>
						<textarea name="expense_detail<?php echo $i;?>" id="expense_detail<?php echo $i;?>" class="input-medium"><?php echo $expenses['expense_detail'];?></textarea></td>
						<td><input name="expense_date<?php echo $i;?>" id="expense_date<?php echo $i;?>" class="input-medium" value="<?php echo $expenses['expense_date'];?>"  />
						<br /><span id="dateerror<?php echo $i;?>"  class="error" style="display:none"></span></td>
						<script>
						dynamiccalender('<?php echo $i;?>');
						</script>
						<td>
						
						 <a  onclick="UpdateExpanse('<?php echo $i;?>');">
						  <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/save.png" title="Save"/></a>
						   <a  onclick="showhideeditexpense('<?php echo $i;?>','2');">
						  <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/cancel.png" title="Cancel" /></a>
						</td>
						</tr>
						   <?php }}?>
						
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
