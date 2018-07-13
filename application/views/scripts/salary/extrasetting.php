	<div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Processing Setting</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post" name="setting">
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<tr><th colspan="2">Set Salary head for perticular month</th></tr>
							<tr>
							<td><strong>Extra Earning Head</strong></td>
							<td><select id="" onchange="extrahead(this.value,1,'earnigtable');montheffective();">
							 <option value="">--Select Head--</option>
							 <?php foreach($this->ObjModel->getSalaryhead() as $earning){ ?>
							   <option value="<?php echo $earning['salaryhead_id'];?>"><?php echo $earning['salary_title'];?></option>   
							 <?php } ?>
							</select></td>
							</tr>
								
							<tr>
								<td colspan="2">
								<table id="earnigtable"></table>
								</td>
								</tr>
								
							<tr>
							<td><strong>Extra Deduction Head</strong></td>
							<td><select id="" onchange="extrahead(this.value,2,'deducttable');">
							 <option value="">--Select Head--</option>
							 <?php foreach($this->ObjModel->getDetectionSalaryhead() as $deduction){ ?>
							   <option value="<?php echo $deduction['salaryhead_id'];?>"><?php echo $deduction['salary_title'];?></option>   
							 <?php } ?>
							</select></td>
							</tr>
							
							<tr>
								<td colspan="2">
								<table id="deducttable"></table>
								</td>
								</tr>
								
							<tr class="odd">
								<td>Processing Period</td>
								<td><select name="period">
								<option value="1">Yearly</option>
								<option value="2">Half Yearly</option>
								<option value="3">Quaterly</option>
								</select></td>
							</tr>
							<tr class="even">
								<td>Start Processing Month</td>
								<td>
								<?php $months = array('1'=>'JAN','2'=>'FEB','3'=>'MAR','4'=>'APR','5'=>'MAY','6'=>'JUN','7'=>'JUL','8'=>'AUG','9'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'NOV');?>
								<select name="start_month">
								<?php foreach($months as $key=>$month){?>
								<option value="<?php echo $key;?>"><?php echo $month;?></option>
								<?php }?>
									</select></td>
							</tr>
						
					         <tr><td colspan="2" align="center"><input type="submit" name="update" class="submit-green" value="Update" /></td></tr>
                            </tbody>
                        </table>
                        </form>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> 