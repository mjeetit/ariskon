 <div class="full_w">
                <div class="h_title">Designation</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addnew','type'=>'Designation'),'default',true)?>" class="button add">Add Designation</a>
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
						<th>Action</th>
						</tr>
						<?php 
						 if($this->designation){
						for($i=0;$i<count($this->designation);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="designation_id" value="<?php echo $this->designation[$i]['designation_id']?>" /></td>
						<td align="center"><?php echo $this->designation[$i]['designation_name']?></td>
						<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'editnew','type'=>'Designation','designation_id'=>$this->designation[$i]['designation_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
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