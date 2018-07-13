 <div class="full_w">
                <div class="h_title">Designation To Department</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Datasetting','action'=>'d2d'),'default',true)?>" class="button add">Add Designation To Department</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</td>
						<th>Designation Name</th>
						<th>Department Name</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->desigtodepart){
						for($i=0;$i<count($this->desigtodepart);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="desig_to_depart_id" value="<?php echo $this->desigtodepart[$i]['desig_to_depart_id']?>" /></td>
						<td align="center"><?php echo $this->desigtodepart[$i]['designation_name']?></td>
						<td align="center"><?php echo $this->desigtodepart[$i]['department_name']?></td>
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