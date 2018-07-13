 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Leave Report</span></h2>
                    
                    <div class="module-table-body">
                    	<form method="post" action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<?php if($_SESSION['AdminLoginID']!=1){?>
							<tr>
							
							<td colspan="2">
							<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'leavereport','Mode'=>'All'))?>" class="button">
							<span>All Report<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="All Report" /></span></a>
							&nbsp;
							<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'leavereport','Mode'=>'self'))?>" class="button">
							<span>Self Report<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="button" /></span></a></td>
							</tr>
						<?php }?>
						
						  <?php if($_SESSION['AdminLoginID']==1){?>
                                <tr>
                                 <td colspan="2">Employee &nbsp;<?php  echo $this->htmlSelect('user_id',CommonFunction::getAssociative($this->filteruserList,'user_id','name'),$this->filter['user_id'],array('class'=>'input-medium'));?></td>
                                 <td>Department &nbsp;<?php  echo $this->htmlSelect('department_id',CommonFunction::getAssociative($this->filterdapartment,'department_id','department_name'),$this->filter['department_id'],array('class'=>'input-medium'));?></td>
                                 <td>Designation &nbsp;<?php  echo $this->htmlSelect('designation_id',CommonFunction::getAssociative($this->filterdesignation,'designation_id','designation_name'),$this->filter['designation_id'],array('class'=>'input-medium'));?></td>
                                 <td>From Date&nbsp;<input name="from_date" id="from_date" value="<?php echo $this->filter['from_date']?>"  class="input-short" /></td>
				 <td>To Date&nbsp;<input name="to_date" id="to_date" value="<?php echo $this->filter['to_date']?>"  class="input-short" /></td>
                                 <td><input type="submit" name="search" class="submit-green" value="Search" />&nbsp;&nbsp;<input type="submit" name="export" class="submit-green" value="Export" /></td>
                               </tr>
                              <?php } ?>
							  	
                                <tr>
									<th style="width:3%; text-align:center">#</td>
									<th style="width:10%; text-align:center">Employee Code</th>
									<th style="width:8%; text-align:center">Department</th>
									<th style="width:8%; text-align:center">Designation</th>
									<th style="width:10%; text-align:center">Leave Request Date</th>
									<th style="width:10%; text-align:center">Leave Day</th>
									
									<th style="width:12%">Leave Status</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->leavereport){
								for($i=0;$i<count($this->leavereport);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="user_id" value="<?php echo $this->leavereport[$i]['user_id']?>" /></td>
						<td align="center"><?php echo $this->leavereport[$i]['employee_code'];?></td>
						<td align="center"><?php echo $this->leavereport[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->leavereport[$i]['designation_name'];?></td>
						
						<td align="center"><?php echo $this->leavereport[$i]['request_date'];?></td>
						<td align="center"><?php echo $this->leavereport[$i]['total_days'];?></td>
						<td align="center"><?php if($this->leavereport[$i]['rejected_approval']>0){ echo 'Rejected'; }elseif($this->leavereport[$i]['approved_approval']>0){ echo 'Approved'; }else{ echo 'Pending';}?></td>
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
                        <?php /*?></div>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
 
        </div>
        <div class="clear"></div>  
    </div><?php */?>
	<script type="text/javascript">
$(function() {
		$("#from_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		$("#to_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});

	});


</script>