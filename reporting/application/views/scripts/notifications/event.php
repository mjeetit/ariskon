 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Message</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="4"> 
							<a href="<?php echo $this->url(array('controller'=>'Notifications','action'=>'addevent'),'default',true)?>" class="button">
                        	<span>Add Event<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Event" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:3%;; text-align:center">S.No.</th>
									<th style="width:15%;; text-align:center"> Event Name</th>
									<th style="width:25%;; text-align:center">Event Description</th>
									<th style="width:10%;; text-align:center">Event Date</th>
									<th style="width:5%;; text-align:center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->EventList){
								for($i=0;$i<count($this->EventList);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><?php echo ($i+1);?></td>
								<td align="center"><?php echo $this->EventList[$i]['event_name'];?></td>
								<td align="center"><?php echo substr($this->EventList[$i]['event_description'],0,100).'....&nbsp;&nbsp;&nbsp;<a href="#">View</a>';?></td>
								<td align="center"><?php echo $this->EventList[$i]['event_date'];?></td>
								<td align="center" id="statusportion<?php echo $this->EventList[$i]['event_id'];?>">
								<?php if($this->EventList[$i]['status']==1) {?>
								<img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Active" border="0" 
		
				onclick="javascript: changeStatus('<?php echo 'events';?>','<?php print $this->EventList[$i]['event_id']; ?>','status','0','event_id');" title="Active" 
		
				 class="changeStatus" />
						<?php }else { ?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
		
				onclick="javascript: changeStatus('<?php echo 'events';?>','<?php print $this->EventList[$i]['event_id']; ?>','status','1','event_id');" title="Inactive" 
		
				 class="changeStatus" />
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