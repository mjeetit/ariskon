 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Leave Requested</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
                                <tr>
									<th style="width:10%">Name</th>
									<th style="width:10%">Department</th>
									<th style="width:10%">Designation</th>
									<th style="width:10%">Leave From</th>
									<th style="width:10%">Leave To</th>
									<th style="width:10%">Approved By</th>
									<th style="width:10%">Rejected By</th>
									<th style="width:10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->leaverequested){
								for($i=0;$i<count($this->leaverequested);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
						<td align="center"><?php echo $this->leaverequested[$i]['first_name'].' '.$this->leaverequested[$i]['last_name']?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['designation_name'];?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['leave_from'];?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['leave_to'];?></td>
						<td align="center"><?php echo substr($this->ObjModel->LeaveApprovedByUsers($this->leaverequested[$i]['approved_approval']),0,-1);?></td>
						<td align="center"><?php echo substr($this->ObjModel->LeaveApprovedByUsers($this->leaverequested[$i]['rejected_approval']),0,-1);?></td>
						<td align="center">
                                                    <?php if($this->leaverequested[$i]['final_approval']==1){ echo 'Approved';}else{?>
						<a href="<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'approve','UserID'=>$this->leaverequested[$i]['user_id'],'request_id'=>$this->leaverequested[$i]['request_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/tick-circle.gif" title="Approve" /></a>&nbsp;
						<?php } ?>
                                                </td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="6">No Record Found!...</td>
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
 
        </div>
        <div class="clear"></div>
    </div>
<script type="text/javascript">
function fancyboxopenfor(url){
 $.fancybox({
        "width": "70%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
</script>	