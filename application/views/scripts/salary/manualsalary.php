 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Edit Salary</span></h2>
                    
                    <div class="module-table-body">
                    	 <form name="form_salary" action="" method="post" id="form1"> 
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<tr>
							 <td>Select Employee &nbsp;<?php  echo $this->htmlSelect('user_id',CommonFunction::getAssociative($this->filteruserList,'user_id','name'),$this->filter['user_id'],array('class'=>'input-medium'));?></td>
                                                         <td>Salay Month&nbsp;<input name="date" id="date" value="<?php echo $this->filter['date']?>"  class="input-short" />&nbsp;&nbsp;
                                                         <input type="submit" name="submit" value="Submit" class="submit-green" />
                                                         </td>
							
							</tr>
                                  <?php if(!empty($this->currentctc)){  ?>
								         <tr>
										 <th>Salary Head</th>
										 <th>Current CTC Head Amount</th>
										 <th>Previus CTC Head Amount</th>
										 </tr>
                                       <?php   foreach($this->currentctc as $key=>$salaryheads){ 
                                              $class = ($key%2==0)?'even':'odd';?>
                                             <tr class="<?php echo  $class;?>">
                                                 <td>
												 <input type="hidden" name="prodata_status[<?php echo $salaryheads['salaryhead_id']?>]" value="<?php echo $salaryheads['prodata_status']?>">
												 <input type="hidden" name="salaryheade_type[<?php echo $salaryheads['salaryhead_id']?>]" value="<?php echo $salaryheads['salaryheade_type']?>">
												 <input type="hidden" name="salaryhead_id[<?php echo $salaryheads['salaryhead_id']?>]" value="<?php echo $salaryheads['salaryheade_type']?>"><?php echo $salaryheads['salary_title']?></td>
                                                 <td><input type="text" name="current_amount[<?php echo $salaryheads['salaryhead_id']?>]" value="<?php echo $salaryheads['amount']?>" class="input-short"></td>
												 <td><input type="text" name="previus_amount[<?php echo $salaryheads['salaryhead_id']?>]" value="<?php echo $salaryheads['amount']?>" class="input-short"></td>
                                             </tr>
                                   <?php } ?>
								   <tr><td>&nbsp;</td><td>Current CTC Days<input type="text" name="current_sal_days" class="input-short"/></td>
								   <td>Previus CTC Days<input type="text" name="previus_sal_days" class="input-short"/></td></tr>
                                         <tr><td colspan="2" align="right">
                                            <input type="submit" name="submit_arrear" value="Save" class="submit-green">
                                         <td> </tr>
                                      <?php } ?>
                                    
					  
						
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div>
			
<script type="text/javascript">
$(function() {
		$("#date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		
	});


</script>			
	