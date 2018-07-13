 <div class="full_w">
                <div class="h_title">Zone</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'add','level'=>4),'default',true)?>" class="button add">Add zone</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</td>
						<th>Company Name</th>
						<th>Business Unit</th>
						<th>Country</th>
						<th>Zone</th>
						<th>Action</th>
						</tr>
						<?php for($i=0;$i<count($this->zones);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="zone_id" value="<?php echo $this->zones[$i]['zone_id']?>" /></td>
						<td align="center"><?php echo $this->zones[$i]['company_name']?></td>
						<td align="center"><?php echo $this->zones[$i]['bunit_name']?></td>
						<td align="center"><?php echo $this->zones[$i]['country_name']?></td>
						<td align="center"><?php echo $this->zones[$i]['zone_name']?></td>
						<td align="center"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" />
						</td>
						</tr>
						<?php } ?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>