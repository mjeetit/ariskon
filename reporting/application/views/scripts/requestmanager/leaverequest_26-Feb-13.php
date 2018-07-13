 <div class="full_w">
                <div class="h_title">Leave History</div>
				<div class="entry">
              
                </div>
               <form method="post" action=""> 
                <table width="100%">
                    <thead>
                       <tr>
					   <td>&nbsp;</td>
						<td colspan="10">&nbsp;
					   </td>
							
						</tr>
						<tr>
						<th>Applied Date</th>
						<th>From Date</th>
						<th>To Date</th>
						<th>Leave Days</th>
						<th>Approve Status</th>
						<th>Leave Days</th>
						<th>Approved By</th>
						</tr>
						<?php 
						 if($this->salaryhistory){
						for($i=0;$i<count($this->salaryhistory);$i++){?>
						<tr>
						<td width="10px"><input type="checkbox" name="user_id" style="width:10px" value="<?php echo $this->salaryhistory[$i]['user_id'];?>" /></td>
						<td align="left"><?php echo $this->salaryhistory[$i]['name'];?></td>
						<td align="center"><?php echo $this->salaryhistory[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->salaryhistory[$i]['designation_name'];?></td>
						<td align="center"><?php echo $this->salaryhistory[$i]['release_date'];?></td>
						<td align="center"><?php echo '30';?></td>
						<td align="center"><?php echo '0';?></td>
						<td align="center"><?php echo $this->salaryhistory[$i]['earning_amount'];?></td>
						<td align="center"><?php echo $this->salaryhistory[$i]['deduction_amount'];?></td>
						<td align="center"><?php echo $this->salaryhistory[$i]['net_amount'];?></td>
						<td align="center"><a href="<?php echo Bootstrap::$baseUrl.'public/salaryslip/'.$this->salaryhistory[$i]['salary_slip_file'] ?>" target="_blank"><img src="<?php print Bootstrap::$baseUrl;?>public/admin_images/print.png" align="absmiddle" alt="Print Invoice" title="Print Invoice" border="0" class="changeStatus" /></a>
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