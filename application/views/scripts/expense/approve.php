<div class="grid_12">  
		<!-- Example table -->
		<div class="module">  
			<h2><span>Expense List</span></h2>
			
			<div class="module-table-body"> 
				<form action="" method="post" name="form_approve" id="form_approve">
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
					 <table style="width:90%">
					 <tbody>			   
						<!--Employee Information-->
					 	<tr>
						<th colspan="2">Employee Code</th>
						<td colspan="2" style="vertical-align:middle"><?=$this->empInfo['Emp_Code']?></td>
						<th colspan="2">Employee Name</th>
                        <td colspan="2" style="vertical-align:middle"><?=$this->empInfo['Emp_Name']?></td>
                        <th colspan="2">Headquarter</th>
						<td colspan="3" style="vertical-align:middle"><?=$this->empInfo['headquater_name']?></td>
						</tr>
						<tr>
						<th colspan="13"></th>
						</tr>
						<!--End of Employee Information-->

						<tr>
						<th><input type="checkbox" name="checkall" id="checkall"></th>
						<th>Expense Date</th>
						<th>Expense Head</th>
						<th>Travel Destination</th>
						<th>Expense Detail</th>
						<th>Expense Amount</th>
                        <th>Fare</th>
						<th>Approve Amount</th>
						<th>Approved By</th>
						<th>Remarks</th>
						<th>Date Added</th>
						<th>Approved Log</th>
						<th>Action</th>
						</tr>
						<?php if(!empty($this->getList)){ 
						foreach($this->getList as $i=>$expenses){
						 $class = ($i%2==0)?'even':'odd'; ?>
						<tr class="<?php echo  $class;?>">
						 <input type="hidden" name="expense_id[<?php echo $expenses['expense_id'];?>]" value="<?php echo $expenses['expense_id'];?>" />
						 <input type="hidden" name="approve_by[<?php echo $expenses['expense_id'];?>]" value="<?php echo $expenses['approve_by'];?>" />
						 <?php 
						 $readonly = '';
						 if(trim($expenses['approve_status'])==1){
						   $readonly = 'disabled="disabled"';
						 }?>
						<td align="center"><input type="checkbox" name="expense_id11[]" value="<?php echo $expenses['expense_id']?>" <?php echo $readonly;?> /></td>
						<td><?php echo $expenses['expense_date']?></td>
						<td><?php echo $expenses['head_name']?></td>
						<td><?php echo $expenses['travel_destination'];?></td>
						<td><?php echo $expenses['expense_detail']?></td>
						<td><?php echo $expenses['expense_amount'];if($expenses['mixed_amount']>0){ echo "<br>Mixed:".$expenses['mixed_amount'];}?>
						<input type="hidden" name="emp_amount[<?php echo $expenses['expense_id'];?>]" id="emp_amount<?php echo $i;?>" value="<?php echo $expenses['expense_amount']?>" class="input-medium" />
						<input type="hidden" name="emp_mixedamount[<?php echo $expenses['expense_id'];?>]" id="emp_mixedamount<?php echo $i;?>" value="<?php echo $expenses['mixed_amount']?>" class="input-medium" />
						</td>
						<td><?php echo $expenses['fare'];?><input type="hidden" name="emp_fare[<?php echo $expenses['expense_id'];?>]" id="emp_fare<?php echo $i;?>" value="<?php echo $expenses['fare']?>" class="input-medium" /></td>
						<td><input type="text" name="approve_amount[<?php echo $expenses['expense_id'];?>]" id="app_amount<?php echo $i;?>" value="<?php echo ($expenses['approve_amount']>0 || $expenses['approve_by']!='')?$expenses['approve_amount']:($expenses['expense_amount'] +$expenses['fare']+$expenses['mixed_amount']);?>" class="input-medium" onkeypress="validamount('<?php echo $i;?>')"  onblur="validamount('<?php echo $i;?>')" <?php echo $readonly;?>/><br />
						<span style="display:none" id="err<?php echo $i;?>" class="error">Approve Amount should <br />not Greater than Expense Amount</span></td>
						<td><?php echo $this->ObjModel->ExpenseApprovedByUsers($expenses['approve_by']);?></td>
						<td><textarea name="remarks[<?php echo $expenses['expense_id'];?>]" id="remarks<?php echo $i;?>" ><?php echo $expenses['remarks']?></textarea></td>
						<td><?php echo date('d-m-Y',strtotime($expenses['date_added']));?></td>
						<td><?php  echo $this->ObjModel->getExpenseLog($expenses['expense_id']);?></td>
						<?php 
							if(($expenses['approve_status']==0) && ($_SESSION['AdminLoginID']==1)){
						?>
						<td>
							<a href="<?php echo $this->url(array('controller'=>'Expense','action'=>'delete','method'=>'approve','user_id'=>$expenses['user_id'],'expense_id'=>$expenses['expense_id'],'month'=>$expenses['expense_date']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" onclick="return confirm('Are you sure want to delete this Expense?')"/></a>
						</td>
						<?php } ?>
						</td>
						</tr>
					  <?php }}?>
					    <tr class="odd"><td colspan="12" align="right" id="app_tr"><input type="submit" name="Approve" id="Approve" value="Approve Expense" class="submit-green" /></td></tr>
						<span id="em"></span><span id="am"></span> 
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
<script>
function validamount(id){
 var empAmount = parseFloat($("#emp_amount"+id).val());
 var AppAmount = parseFloat($("#app_amount"+id).val());
 var fare = parseFloat($("#emp_fare"+id).val());
 var mixedamount = parseFloat($("#emp_mixedamount"+id).val());
 if(AppAmount>(empAmount + fare + mixedamount)){
    $("#err"+id).show();
	$('#app_amount'+id).css('border', '1px solid #FF0000');
	$('#app_amount'+id).css('color','#FF0000');
	$('#err_'+id).css('color','#FF0000');
	$('#Approve').hide();
 }else{
   $("#err"+id).hide();
   $('#app_amount'+id).css('border', '1px solid #DDDDDD');
   $('#app_amount'+id).css('color', '#000000');
   $('#Approve').show();
  }  
}

$(function() {
    $('#checkall').change(function(){
      if (document.getElementById("checkall").checked==false) {
		$("form input[type='checkbox']").attr ( "checked" , false );
     }else{
	     $("form input[type='checkbox']").attr ( "checked" , true ); 
	 }
 });
});
</script>
