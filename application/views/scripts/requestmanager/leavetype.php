 <div class="full_w">
                <div class="h_title">Leave Request Notification</div><br>
				
               <form method="post" action=""> 
                <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
                    <thead>
						<tr>
							<th width="25%">Leave Type</th>
							<th width="25%">Available Days</th>
							<th width="25%">Apply Days</th>
							<th width="25%">Modify</th>
						</tr>
						
						<?php if(count($this->leavetype) > 0) { foreach($this->leavetype as $type) { ?>
						<tr>
							<td align="center"><?=$type['typeName']?></td>
							<td align="center"><?=(isset($this->availLeave[$type['typeID']])) ? $this->availLeave[$type['typeID']] : '0'?></td>
							<td align="center"><?=(isset($this->requesType[$type['typeID']])) ? $this->requesType[$type['typeID']] : '0'?></td>
							<td align="center">
								<input id="leaveDays_<?=$type['typeID']?>" class="required" type="text" name="leaveDays[]" onblur="$.checkLeave(<?=$type['typeID']?>);" size="3" value="<?=$this->requesType[$type['typeID']]?>">
								<input type="hidden" id="rest_<?=$type['typeID']?>" value="<?=$this->availLeave[$type['typeID']]?>">&nbsp;
								<span id="err_<?=$type['typeID']?>"></span>
							</td>
						</tr>
						<?php } } ?>
                    </thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>