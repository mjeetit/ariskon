 <div class="full_w">
                <div class="h_title">Salary Template</div>
				<div class="entry">
                  
                </div>
               <form method="post" action=""> 
                <table width="100%">
                    <thead>
                       <tr>
						<td colspan="6">
						<table>
							<tr>
							  <td>Department Name
							   </td>
								<td><select name="department_id">
							   <option value="">--Select--</option>
							   </select>
							   </td>
								<td>Department Name</td>
							   <td><select name="department_id">
							   <option value="">--Select--</option>
							   </select>
							   </td>
							</tr>
						</table>
						</td>
						</tr>
						<tr>
						<th>Employee Name</th>
						<th>Department</th>
						<th>Designation</th>
						<th>Paid Days</th>
						<th>Leave Days</th>
						<th>Actual Salary</th>
						<th>Detection</th>
						<th>Net Salary</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->finalsalary){
						for($i=0;$i<count($this->finalsalary);$i++){?>
						<tr>
						<td align="left"><?php echo $this->finalsalary[$i]['Name']?></td>
						<td align="center"><?php echo $this->finalsalary[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->finalsalary[$i]['designation_name'];?></td>
						<td align="center"><?php echo $this->finalsalary[$i]['paid_days'];?></td>
						<td align="center"><?php echo $this->finalsalary[$i]['leave_days'];?></td>
						<td align="center"><?php echo $this->finalsalary[$i]['actual_salary'];?></td>
						<td align="center"><?php echo $this->finalsalary[$i]['detection'];?></td>
						<td align="center"><?php echo $this->finalsalary[$i]['net_salary'];?></td>
						<td align="center"><a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'salaryprocessing','user_id'=>$this->finalsalary[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" /></a>
						</td>
						</tr>
						<?php }} else{ ?>
						<tr>
						<td align="center" colspan="7">No Record Found!...</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>