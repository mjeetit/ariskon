 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Salary History</span></h2>
                    
                    <div class="module-table-body">
                    <form method="post" action="" name="salary" id="salary"> 
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="6">
							<a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'providenttransactions','Mode'=>'ExportProvident'),'default',true)?>" class="button add">
                        	   <span>Export Transaction<img src="<?php echo IMAGE_LINK;?>/download.png"  width="12" height="9" alt="Download Pay Summary" /></span>
                           </a>
							</td>
							</tr>
                                <tr>
									<th style="width:5%"><input type="checkbox" name="checkall" style="width:10px" /></td>
									<th style="width:10%">Employee Name</th>
									<th style="width:10%">Department</th>
									<th style="width:10%">Designation</th>
									<th style="width:10%">Deduct Amount</th>
									<th style="width:10%">Given By Company</th>
									<th style="width:10%">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->providenttransaction){
								for($i=0;$i<count($this->providenttransaction);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td width="10px" <?php echo $color;?>><input type="checkbox" name="user_id[]" style="width:10px" value="<?php echo $this->providenttransaction[$i]['user_id'];?>" /></td>
								<td align="left"><?php echo $this->salaryhistory[$i]['name'];?></td>
								<td align="center"><?php echo $this->salaryhistory[$i]['department_name'];?></td>
								<td align="center"><?php echo $this->salaryhistory[$i]['designation_name'];?></td>
								<td align="center"><?php echo $this->salaryhistory[$i]['deduct_from_sal'];?></td>
								<td align="center"><?php echo $this->salaryhistory[$i]['earn_by_comp'];?></td>
								<td align="center"><?php echo $this->salaryhistory[$i]['total_pf'];?></td>
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
