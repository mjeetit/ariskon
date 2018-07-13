 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>User List</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
							</tr>
                                <tr>
									<th style="width:3%; text-align:center">#</td>
									<th style="width:10%; text-align:center">Name</th>
									<th style="width:10%; text-align:center">Department</th>
									<th style="width:10%; text-align:center">Designation</th>
									<th style="width:8%; text-align:center">Employee Code</th>
									<th style="width:10%; text-align:center">Email</th>
									<th style="width:7%; text-align:center">DOB</th>
									<th style="width:5%; text-align:center">Login Status</th>
									<th style="width:5%; text-align:center">Admin Status</th>
									<th style="width:7%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->Users){
								for($i=0;$i<count($this->Users);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="user_id" value="<?php echo $this->Users[$i]['user_id']?>" /></td>
						<td align="center"><?php echo $this->Users[$i]['first_name'].' '.$this->Users[$i]['last_name']?></td>
						<td align="center"><?php echo $this->Users[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->Users[$i]['designation_name'];?></td>
						<td align="center"><?php echo $this->Users[$i]['employee_code'];?></td>
						<td align="center"><?php echo $this->Users[$i]['email'];?></td>
						<td align="center"><?php echo $this->Users[$i]['dob'];?></td>
						<td align="center">
						<?php if($this->Users[$i]['login_status']=='1'){?>
						   <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/icon_active.gif" title="Active" />
						<?php }else{?>
						  <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/icon_inactive.gif" title="Inactive" />
						<?php }?>
						</td>
						<td align="center">
						<?php if($this->Users[$i]['user_status']=='1'){?>
						   <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/icon_active.gif" title="Active" />
						<?php }else{?>
						  <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/icon_inactive.gif" title="Inactive" />
						<?php }?>
						</td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Users','action'=>'edituser','user_id'=>$this->Users[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit User" /></a>&nbsp;|
						<a onclick="fancyboxopenfor('<?php echo $this->url(array('controller'=>'Users','action'=>'view','user_id'=>$this->Users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a>&nbsp;|
					 <a  onclick="fancyboxopenforpriv('<?php echo $this->url(array('controller'=>'Users','action'=>'privillage','user_id'=>$this->Users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/privillage.png" title="Privillages" /></a>&nbsp;|
					 <a  onclick="fancyboxopenfordelete('<?php echo $this->url(array('controller'=>'Users','action'=>'delete','user_id'=>$this->Users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" /></a>
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

function fancyboxopenforpriv(url){
 $.fancybox({
        "width": "40%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
function fancyboxopenfordelete(url){
 $.fancybox({
        "width": "40%",
        "height": "40%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
</script>	