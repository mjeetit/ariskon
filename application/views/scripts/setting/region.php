 <div class="full_w">
                <div class="h_title">Region</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'add','level'=>5),'default',true)?>" class="button add">Add Region</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</th>
						<th>Region Name</th>
						<th>Zone Name</th>
						<th>Business Unit</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->Region){
						for($i=0;$i<count($this->Region);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="resion_id" value="<?php echo $this->Region[$i]['resion_id']?>" /></td>
						<td align="center"><?php echo $this->Region[$i]['region_name']?></td>
						<td align="center"><?php echo $this->Region[$i]['zone_name']?></td>
						<td align="center"><?php echo $this->Region[$i]['bunit_name']?></td>
						<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>5,'region_id'=>$this->Region[$i]['region_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" />
						</td>
						</tr>
						<?php } }else{ ?>
						<tr>
						   <td colspan="5" align="center">No Record Found!..</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>