 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Company</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="4"> <a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'add','level'=>1),'default',true)?>" class="button add">
                        	<span>Add Company<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Compant" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:5%;; text-align:center">#</th>
									<th style="width:20%;; text-align:center">Company Name</th>
									<th style="width:20%;; text-align:center">Company Address</th>
									<th style="width:20%;; text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->company){
								for($i=0;$i<count($this->company);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="company_code" value="<?php echo $this->company[$i]['company_code']?>" /></td>
								<td align="center"><?php echo $this->company[$i]['company_name']?></td>
								<td align="center"><?php echo $this->company[$i]['company_address']?></td>
								<td align="center"> <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>1,'company_code'=>$this->company[$i]['company_code']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
								</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="4">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        </form>
                        <div class="pager" id="pager">
                            <form action="">
                                <div>
                                <a href="" class="button"><span><img src="<?php echo IMAGE_LINK;?>/arrow-180-small.gif" tppabs="http://www.xooom.pl/work/magicadmin/images/arrow-180-small.gif" height="9" width="12" alt="Previous" /> Prev</span></a>
                                <input type="text" class="pagedisplay input-short align-center"/>
								 <a href="" class="button"><span>Next <img src="<?php echo IMAGE_LINK;?>/arrow-000-small.gif" height="9" width="12" alt="Next" /></span></a> 
                                <select class="pagesize input-short align-center">
                                    <option value="10" selected="selected">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                </select>
                                </div>
                            </form>
                        </div>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->