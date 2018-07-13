 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Office</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="5">
						    <a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'addoffice','Mode'=>'Add'),'default',true)?>" class="button add">
                        	<span>Add Office<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Region" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:5%;; text-align:center">#</th>
									<th style="width:20%;; text-align:center">Office Name</th>
									<th style="width:20%;; text-align:center">Office Address</th>
									<th style="width:20%;; text-align:center">Office Type</th>
									<th style="width:5%;; text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->Region){
								for($i=0;$i<count($this->office);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="office_id" value="<?php echo $this->office[$i]['office_id']?>" /></td>
								<td align="center"><?php echo $this->office[$i]['office_name']?></td>
								<td align="center"><?php echo $this->office[$i]['office_address']?></td>
								<td align="center"><?php echo $this->office[$i]['office_type']?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'addoffice','Mode'=>'Edit','office_id'=>$this->office[$i]['office_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
								</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="5">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        </form>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->