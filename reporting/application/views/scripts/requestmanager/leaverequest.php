 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Leave Request</span></h2>

                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="7">
							  <a href="<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'addleave'),'default',true)?>" class="button add">
                        	<span>Add Leave Request<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Leave Request" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
                                    <th style="text-align:center">Applied Date</th>
									<th style="text-align:center">Leave From</th>
									<th style="text-align:center">Leave To</th>
									<th style="text-align:center">Leave Days</th>
									<th style="width:10%">Approved By</th>
									<th style="width:10%">Rejected By</th>
                                    <th style="width:10%">Remarks</th>
									<th style="text-align:center">Request Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(count($this->leavelists) > 0) { foreach($this->leavelists as $leavelist) { ?>
								<tr>
								<td align="left"><?=$leavelist['request_date'];?></td>
								<td align="center"><?=$leavelist['leave_from'];?></td>
								<td align="center"><?=$leavelist['leave_to'];?></td>
								<td align="center"><?=$leavelist['total_days'];?></td>
								<td align="center"><?php echo substr($this->ObjModel->LeaveApprovedByUsers($leavelist['approved_approval']),0,-1);?></td>
						<td align="center"><?php echo substr($this->ObjModel->LeaveApprovedByUsers($leavelist['rejected_approval']),0,-1);?></td>
                                                                <td align="center"><?=wordwrap($leavelist['remarks'],20);?></td>
								<td align="center"><?=$leavelist['status_name']?></td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="7">No leave request found till now !!</td>
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