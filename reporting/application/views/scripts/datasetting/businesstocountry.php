 <div class="full_w">
                <div class="h_title">Business Unit To Country</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Datasetting','action'=>'bunittocountry'),'default',true)?>" class="button add">Add B.Unit To Country</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</td>
						<th>Business Unit</th>
						<th>Country</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->bunittocountry){
						for($i=0;$i<count($this->bunittocountry);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="bunit_to_country_id" value="<?php echo $this->bunittocountry[$i]['bunit_to_country_id']?>" /></td>
						<td align="center"><?php echo $this->bunittocountry[$i]['bunit_name']?></td>
						<td align="center"><?php echo $this->bunittocountry[$i]['country_name']?></td>
						<td align="center"><a href="#"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
						</td>
						</tr>
						<?php }} else{ ?>
						<tr>
						<td align="center" colspan="3">No Record Found!...</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>