 <div class="full_w">
                <div class="h_title">Salary Amount</div>
				<div class="entry">
                
                </div>
               <form method="post" action=""> 
                <table width="100%">
                    <thead>
					<input type="hidden" name="user_id" value="<?php echo $this->user_id;?>" />
						<?php 
						for($i=0;$i<count($this->salaryhead);$i++){?>
						<tr>
						<input type="hidden" name="salaryhead_id[]" value="<?php echo $this->salaryhead[$i]['salaryhead_id'];?>" />
						<td align="left" style="width:200px;"><?php echo $this->salaryhead[$i]['salary_title'];?></td>
						<td align="left"><input type="text"  name="amount[]" value="<?php  echo $this->amountdetail[$i]['amount']?>"/></td>
						</tr>
						<?php }?>
						<tr>
						<td align="center" colspan="2"><input type="submit" name="Addamount" value="Add Amount" /></td>
						</tr>
                    </thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>