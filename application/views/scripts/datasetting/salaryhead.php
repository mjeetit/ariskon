 <div class="full_w">
                <div class="h_title">Salary Head</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addnew','type'=>'Salaryhead'),'default',true)?>" class="button add">Add Salary Head</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</td>
						<th>Salary Title</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->salary){
						for($i=0;$i<count($this->salary);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="salaryhead_id" value="<?php echo $this->salary[$i]['salaryhead_id']?>" /></td>
						<td align="center"><?php echo $this->salary[$i]['salary_title']?></td>
						<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'editnew','type'=>'Salaryhead','salaryhead_id'=>$this->salary[$i]['salaryhead_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
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