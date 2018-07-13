 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Notification List</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
                                <tr>
									<th style="width:3%;; text-align:center">S.No.</th>
									<th style="width:20%;; text-align:center">Notification</th>
									<th style="width:20%;; text-align:center">Create Date</th>
									<th style="width:20%;; text-align:center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->notlist){
								for($i=0;$i<count($this->notlist);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><?php echo ($i+1);?></td>
								<td align="center"><?php echo substr($this->notlist[$i]['description'],0,100).'....&nbsp;&nbsp;&nbsp;<a href="#">View</a>';?></td>
								<td align="center"><?php echo $this->notlist[$i]['notification_date'];?></td>
								<td align="center" id="statusportion<?php echo $this->notlist[$i]['notification_id'];?>">
								<?php if($this->notlist[$i]['status']==1) {?>
								<img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Active" border="0">
						<?php }else { ?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" />
		
				   <?php } ?></td>
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