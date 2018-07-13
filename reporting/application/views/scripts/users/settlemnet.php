 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Final Settlement</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
                        <table id="myTable" class="tablesorter">
                        	<tbody>
                             
                               <tr>

						  <th colspan="3" align="left">Final Settlement</th>

						</tr>

						<tr class="odd"> 
							<td>Enter Employee Code</td>
                                                        <td><input type="text" name="employee_code" class="input-medium"></td>
                                                        <td><input type="submit" name="submit" value="Submit" class="submit-green"></td>
						</tr>
                                  <tr><td colspan="3" align="center">
				 <?php if(!empty($this->detail)){?>
                                          <table>
                                        <tr class="odd">
							<td>Employee Name</td>
                            <td colspan="4"><?php echo $this->detail[0]['first_name'].' '.$this->detail[0]['last_name']?></td>
						  </tr>
						  <tr>
						  <th>Action</th><th>Item Name</th><th>Item Value</th><th>Allotment Date</th>
						  </tr>
						  <?php foreach($this->detail as $detail){ ?>
						  <input type="hidden" name="user_id" id="user_id" value="<?php echo $detail['user_id']?>" />
						   <tr>
						    <td><input type="radio" name="final_settlement[<?php echo $detail['emp_acc_id']; ?>]" value="1" <?php if($detail['final_settlement']==1){ echo 'checked="checked"';}?> />Refund
								<input type="radio" name="final_settlement[<?php echo $detail['emp_acc_id']; ?>]" value="2" <?php if($detail['final_settlement']==2){ echo 'checked="checked"';}?> />Not Requred
								<input type="radio" name="final_settlement[<?php echo $detail['emp_acc_id']; ?>]" value="3" <?php if($detail['final_settlement']==3){ echo 'checked="checked"';}?> />Not Return</td>
								<td> <?php echo $detail['acceseries_name']?></td>
							<td> <?php echo $detail['acceseries_value']?></td><td> <?php echo $detail['allowt_date']?></td>
						   </tr>
						  <?php }?>
						  <tr><td colspan="4" align="right"><input type="submit" name="submit" value="Update" class="submit-green" /></td></tr>
                                         </table>
                                <?php } ?>
                                                </td></tr>
					<tr><td>Resign Date:<input type="text" name="resign_date" id="resign_date" class="input-medium"/></td>
					<td>Notice Period:<input type="text" name="notice_day" id="notice_day" class="input-medium"/></td>
					<td>Final Salary:<input type="text" name="sett_amount" id="sett_amount" class="input-medium"/></td>
					</tr>
					<tr>
					<td>Final Salary:<input type="text" name="sett_amount" id="sett_amount" class="input-medium"/></td>
					<td>Cheque Number:<input type="text" name="cheque_number" id="cheque_number" class="input-medium" /></td>
					<td>Salary Day:<input type="text" name="settlement_sal_day" id="settlement_sal_day" class="input-medium" /></td>
					</tr>
					<?php if(!empty($this->salarylist)){
					foreach($this->salarylist as $salarylist){?>
					<tr><td><?php echo $salarylist['salary_title']?></td><td><input type="text" class="input-medium" name="salaryhead_id[<?php echo $salarylist['salaryhead_id']?>]" value="<?php echo $salarylist['amount']?>" /></td></tr>
					<?php } }?>							
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
 
        </div>
        <div class="clear"></div>
    </div>
	<script type="text/javascript">
$(function() {
		$("#resign_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
	});


</script>