 <div class="full_w">
                <div class="h_title">Invoices</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Invoices','action'=>'addinvoice'),'default',true)?>" class="button add">Add New User</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</td>
						<th>Name</th>
						<th>Department</th>
						<th>Designation</th>
						<th>Username</th>
						<th>Email</th>
						<th>DOB</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->Users){
						for($i=0;$i<count($this->Users);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="user_id" value="<?php echo $this->Users[$i]['user_id']?>" /></td>
						<td align="center"><?php echo $this->Users[$i]['first_name'].' '.$this->Users[$i]['last_name']?></td>
						<td align="center"><?php echo $this->Users[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->Users[$i]['designation_name'];?></td>
						<td align="center"><?php echo $this->Users[$i]['username'];?></td>
						<td align="center"><?php echo $this->Users[$i]['email'];?></td>
						<td align="center"><?php echo $this->Users[$i]['dob'];?></td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Users','action'=>'edituser','user_id'=>$this->Users[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit User" />&nbsp;
						<a href="<?php echo $this->url(array('controller'=>'Users','action'=>'employeesalary','user_id'=>$this->Users[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="Edit Salary Template" />&nbsp;
						<a href="<?php echo $this->url(array('controller'=>'Users','action'=>'edituser','user_id'=>$this->Users[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/privillage.png" title="Privillages" /></a>
						</td>
						</tr>
						<?php }} else{ ?>
						<tr>
						<td align="center" colspan="7">No Record Found!...</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>