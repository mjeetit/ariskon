 <div class="full_w">
                <div class="h_title">Documents</div>
				<div class="entry">
                </div>
                
                <table width="100%">
                    <thead>
						<tr>
						<th>S.No.</th>
						<th>Document Name</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->Documents){
						for($i=0;$i<count($this->Documents);$i++){?>
						<tr>
						<td width="10%" align="center"><?php echo $i+1;?></td>
						<td align="center"><?php echo $this->Documents[$i]['type_name'];?></td>
						<td align="center"><a href="<?php echo Bootstrap::$baseUrl.'public/DocumentDirectory/'.$this->Documents[$i]['file_name'] ?>" target="_blank"><img src="<?php print Bootstrap::$baseUrl;?>public/admin_images/download-icon.png" align="absmiddle" alt="<?php echo $this->Documents[$i]['type_name'];?>" title="<?php echo $this->Documents[$i]['type_name'];?>" border="0" class="changeStatus" /></a>
						</td>
						</tr>
						<?php }} else{ ?>
						<tr>
						<td align="center" colspan="7">No Record Found!...</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>