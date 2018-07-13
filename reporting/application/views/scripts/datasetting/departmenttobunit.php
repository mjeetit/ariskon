 <div class="full_w">
                <div class="h_title">Department To Business Unit</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Datasetting','action'=>'d2b'),'default',true)?>" class="button add">Add Department To B.Unit</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</td>
						<th>Department Name</th>
						<th>Business Unit</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->departmenttobunit){
						for($i=0;$i<count($this->departmenttobunit);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="bunit_to_company_id" value="<?php echo $this->departmenttobunit[$i]['d2b_id']?>" /></td>
						<td align="center"><?php echo $this->departmenttobunit[$i]['bunit_name']?></td>
						<td align="center"><?php echo $this->departmenttobunit[$i]['department_name']?></td>
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