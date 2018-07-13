 <div class="full_w">
                <div class="h_title">Loan History</div>
				<div class="entry">
               <div class="sep"></div>
                  <a href="<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'loanrequest'),'default',true)?>" class="button">Back</a>
				  <a href="<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'addloanrequest'),'default',true)?>" class="button">Apply Request</a>
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
						<th>Applied Amount</th>
						<th>Applied Date</th>
						<th>Approve Date</th>
						<th>Remarks</th>
						<th>Status</th>
						<th>Approve By</th>
						</tr>
						<?php 
						$sataus = array(1=>'Not Approve',2=>'Processing',3=>'Approved',4=>'Rejected');
						 if($this->loanrequest){
						for($i=0;$i<count($this->loanrequest);$i++){?>
						<tr>
						<td align="left"><?php echo $this->loanrequest[$i]['loan_amount'];?></td>
						<td align="center"><?php echo $this->loanrequest[$i]['apply_date'];?></td>
						<td align="center"><?php echo $this->loanrequest[$i]['approve_date'];?></td>
						<td align="center"><?php echo $this->loanrequest[$i]['remarks'];?></td>
						<td align="center"><?php echo $sataus[$this->loanrequest[$i]['status']];?></td>
						<td align="center"><?php echo $this->loanrequest[$i]['approve_by'];?></td>
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