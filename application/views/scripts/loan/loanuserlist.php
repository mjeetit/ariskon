 <div class="full_w">
                <div class="h_title">Loan Users List</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Loan','action'=>'addloanuser'),'default',true)?>" class="button add">Add Loan User</a>
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
						<th>Loan Amount</th>
						<th>Including Tax</th>
						<th>Amount Left</th>
						<th>Number Of EMI</th>
						<th>Deduct From Salary</th>
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
						<td align="center"><?php echo $this->Users[$i]['loan_amount'];?></td>
						<td align="center"><?php echo $this->Users[$i]['loan_including_tax'];?></td>
						<td align="center"><?php echo ($this->Users[$i]['loan_including_tax']-$this->Users[$i]['paid_amount']);?></td>
						<td align="center"><?php echo $this->Users[$i]['no_of_emi'];?></td>
						<td align="center"><?php echo ($this->Users[$i]['deduct_from_sal']==1)?'Yes':'No';?></td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Loan','action'=>'editloanuser','emp_loan_id'=>$this->Users[$i]['emp_loan_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit User" /></a>
						</td>
						</tr>
						<?php }} else{ ?>
						<tr onclick="fancyboxWindowopen('')">
						<td align="center" colspan="7">No Record Found!...</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<script>
function fancyboxWindowopen(url){alert('HI');
$.fancybox({
        "width": "60%",
        "height": "90%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    });
}	
</script>	
	