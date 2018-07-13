 <div class="full_w">
                <div class="h_title">Leave Management Information</div>
				<div class="entry">
                    <div class="sep"></div>
                 	<div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <!--<thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</th>
						<th>Company Name</th>
						<th>Company Address</th>
						<th>Action</th>
						</tr>
						<?php for($i=0;$i<count($this->company);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="company_code" value="<?php echo $this->company[$i]['company_code']?>" /></td>
						<td align="center"><?php echo $this->company[$i]['company_name']?></td>
						<td align="center"><?php echo $this->company[$i]['company_address']?></td>
						<td align="center"> <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>1,'company_code'=>$this->company[$i]['company_code']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
						</td>
						</tr>
						<?php } ?>
                    </thead>-->
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>