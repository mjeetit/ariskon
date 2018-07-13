 <?php
 	//print_r($this->expensesrep['Records']);die;
 ?>
 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Expense Report</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="get">
                        <table id="myTable" class="tablesorter">
                        	<thead>
								<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'expensereport'))?>" class="button">
								<span>Back<img src="<?php echo IMAGE_LINK;?>/minus-circle.gif"  width="12" height="9" alt="All Report" /></span></a>
                              <?php if($_SESSION['AdminLoginID']==1){  //echo "<pre>";print_r(CommonFunction::getAssociative($this->empandHQ['RBM'],'user_id','name'));die;?>
                                <tr>
                                 <td colspan="2">From Date&nbsp;<input name="from_date" id="from_date" value="<?php echo $this->filter['from_date']?>"  class="input-short" /></td>
				 				 <td colspan="2">To Date&nbsp;<input name="to_date" id="to_date" value="<?php echo $this->filter['to_date']?>"  class="input-short" /></td>
                                 <td><input type="submit" name="search" class="submit-green" value="Search" /></td>
                                
                               </tr>
                              <?php } ?>
                                <tr>
									<th style="width:3%; text-align:center">#</th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Name', 'EH.head_name');?></th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Expense Amount', 'EE.expense_amount');?></th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Fare', 'EE.fare');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Travel Destination', 'EE.travel_destination');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Detail', 'EE.expense_detail');?></th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Total Expense', 'EE.expense_amount');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Total Approve', 'EE.approve_amount');?></th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Month Of Expense', 'EE.expense_date');?></th>
									<th style="width:8%; text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->expensesrep['Records']){
								for($i=0;$i<count($this->expensesrep['Records']);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="user_id" value="<?php echo $this->expensesrep['Records'][$i]['user_id']?>" /></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['head_name'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['expense_amount'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['fare']+$this->expensesrep['Records'][$i]['mixed_amount'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['travel_destination'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['expense_detail'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['expense_amount']+$this->expensesrep['Records'][$i]['fare']+$this->expensesrep['Records'][$i]['mixed_amount'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['approve_amount'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['expense_date'];?></td>
							<!--<td align="center"><?php 
							if($this->expensesrep['Records'][$i]['approve_status']){
								echo "Approved";
							}else{
								echo "Disapproved";
							};?></td>-->
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="6">No Record Found!...</td>
								</tr>
								<?php }?>
								<tr>
							<th colspan="13" style="text-align:left"><?php echo CommonFunction::PageCounter($this->expensesrep['Total'], $this->expensesrep['Offset'], $this->expensesrep['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext');?></th>
							</tr>
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
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