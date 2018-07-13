 <div class="full_w">
                <div class="h_title">Head Office</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'add','level'=>7),'default',true)?>" class="button add">Add Headoffice</a>
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
						<th>Region</th>
						<th>Area</th>
						<th>Headoffice Address</th>
						<th>Action</th>
						</tr>
						<?php 
						if($this->headoffice){
						for($i=0;$i<count($this->headoffice);$i++){?>
						<tr>
						<td><input type="checkbox" name="headoff_id" value="<?php echo $this->headoffice[$i]['headoff_id']?>" /></td>
						<td align="center"><?php echo $this->headoffice[$i]['company_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['bunit_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['country_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['zone_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['region_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['area_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['headoffice_address']?></td>
						<td align="center"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" />
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