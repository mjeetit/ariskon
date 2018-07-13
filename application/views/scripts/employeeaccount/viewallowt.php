 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>View Allot List</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr> 
							<td colspan="9"> 
							<a href="<?php echo $this->url(array('controller'=>'Employeeaccount','action'=>'allowt'),'default',true)?>" class="button">
                        	<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:5%">#</td>
									<th style="width:10%">Name</th>
									<th style="width:10%">Department</th>
									<th style="width:10%">Designation</th>
									<th style="width:10%">Item Name</th>
									<th style="width:10%">Item Value</th>
									<th style="width:10%">Refundable</th>
									<th style="width:10%">Refundable Period</th>
									<th style="width:5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->AccUsers){
								for($i=0;$i<count($this->AccUsers);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
						<td align="center"><input type="checkbox" name="user_id" value="<?php echo $this->AccUsers[$i]['user_id']?>" /></td>
						<td align="center"><?php echo $this->AccUsers[$i]['first_name'].' '.$this->Users[$i]['last_name']?></td>
						<td align="center"><?php echo $this->AccUsers[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->AccUsers[$i]['designation_name'];?></td>
						<td align="center"><?php echo $this->AccUsers[$i]['acceseries_name'];?></td>
						<td align="center"><?php echo $this->AccUsers[$i]['acceseries_value'];?></td>
						<td align="center"><?php echo ($this->AccUsers[$i]['refundable']=='1')?'Yes':'No';?></td>
						<td align="center"><?php echo $this->AccUsers[$i]['refundable_not_after'];?></td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Employeeaccount','action'=>'editallowt','emp_acc_id'=>$this->AccUsers[$i]['emp_acc_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit User" /></a>
						</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="9">No Record Found!...</td>
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
 
        </div>
        <div class="clear"></div>
    </div>
<script type="text/javascript">
function fancyboxopenfor(url){
 $.fancybox({
        "width": "100%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
</script>	