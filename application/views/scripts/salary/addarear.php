 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Edit Salary</span></h2>
                    
                    <div class="module-table-body">
                    	 <form name="form_salary" action="" method="post" id="form1"> 
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<tr><td><a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'arriercurrentsalarylist'),'default',true)?>" class="button">
							 <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a></td></tr>
                           <tr>
							 <td>Select Employee &nbsp;<?php  echo $this->htmlSelect('user_id',CommonFunction::getAssociative($this->filteruserList,'user_id','name'),$this->filter['user_id'],array('class'=>'input-medium'));?></td>
                                                         <td>Salay Month&nbsp;<input name="date" id="date" value="<?php echo $this->filter['date']?>"  class="input-short" />&nbsp;&nbsp;
                                                         <input type="submit" name="submit" value="Submit" class="submit-green" />
                                                         </td>
							
							 <tr>
                                  <?php if(!empty($this->usersalaryhead)){ 
                                          foreach($this->usersalaryhead as $key=>$salaryheads){ 
                                              $class = ($key%2==0)?'even':'odd';?>
                                             <tr class="<?php echo  $class;?>">
                                                 <td><input type="hidden" name="salaryhead_id[<?php echo $salaryheads['salaryhead_id']?>]" value="<?php echo $salaryheads['salaryheade_type']?>"><?php echo $salaryheads['salary_title']?></td>
                                                 <td>
												 <?php 
												   if($salaryheads['salaryhead_id']==2 && $this->userinfo['provident']==1 && $this->userinfo['provident_pecentage']>0){
														if($this->userinfo['provident_type']=='0'){
														   $salaryheads['amount'] =  $this->usersalaryhead[0]['amount']*str_replace('%','',$this->userinfo['provident_pecentage'])/100;
														}else{
														   $salaryheads['amount'] =  $this->userinfo['provident_pecentage'];
														}
												  }?>
												  <input type="text" name="amount[<?php echo $salaryheads['salaryhead_id']?>]" value="<?php echo $salaryheads['amount']?>" class="input-medium">
												
												 </td>
                                             </tr>
                                   <?php } ?>
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
	