 <div class="full_w">
                <div class="h_title">Street</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'add','level'=>9),'default',true)?>" class="button add">Add Street</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</td>
						<th>Street</th>
						<th>City Name</th>
						<th>Area</th>
						<th>Region</th>
						<th>Zone</th>
						<th>Business Unit</th>
						<th>Action</th>
						</tr>
						<?php 
						if($this->street){
						for($i=0;$i<count($this->street);$i++){?>
						<tr>
				    <td><input type="checkbox" name="street_id" value="<?php echo $this->street[$i]['street_id']?>" /></td>
					<td align="center"><?php echo $this->street[$i]['street_name']?></td>
					<td align="center"><?php echo $this->street[$i]['city_name']?></td>
					<td align="center"><?php echo $this->street[$i]['area_name']?></td>
					<td align="center"><?php echo $this->street[$i]['region_name']?></td>
					<td align="center"><?php echo $this->street[$i]['zone_name']?></td>
					<td align="center"><?php echo $this->street[$i]['bunit_name']?></td>
					<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>9,'street_id'=>$this->street[$i]['street_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" />
						</td>
						</tr>
						<?php } }else{ ?>
						<tr>
						<td colspan="5" align="center">No Record Found...</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>