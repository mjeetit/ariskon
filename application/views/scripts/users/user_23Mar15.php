 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>User List</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="get">
						  <table id="myTable1" class="tablesorter">
                        	<thead>
							<tr> 
							<th colspan="12" align="center">Search Option</th></tr>
							<tr><td>Employee</td><td><?php  echo $this->htmlSelect('user_id',CommonFunction::getAssociative($this->filteruser,'user_id','name'),$this->filter['user_id'],array('class'=>'input-medium'));?></td>
							<td>Designation</td><td><?php  echo $this->htmlSelect('designation_id',CommonFunction::getAssociative($this->filterdesignation,'designation_id','designation_name'),$this->filter['designation_id'],array('class'=>'input-medium'));?></td>
							<td>Department</td><td><?php  echo $this->htmlSelect('department_id',CommonFunction::getAssociative($this->filterdepartment,'department_id','department_name'),$this->filter['department_id'],array('class'=>'input-medium'));?></td>
							</tr><tr>
							<td>Headquater</td><td><?php  echo $this->htmlSelect('headquater_id',CommonFunction::getAssociative($this->filterheadquater,'headquater_id','headquater_name'),$this->filter['headquater_id'],array('class'=>'input-medium'));?></td>
							<td>Serch Word</td><td><input type="text" name="search_word" class="input-medium" value="<?php echo $this->filter['search_word']?>" /></td>
							<td><input type="submit" name="search" value="Serach"  class="submit-green" /></td></tr>
						</thead>
						</table>	
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
							<tr> 
							<td colspan="12"> <a href="<?php echo $this->url(array('controller'=>'Users','action'=>'adduser'),'default',true)?>" class="button">
                        	<span>Add New User<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add New User" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Name', 'UT.first_name');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Department', 'DEP.department_id');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Designation', 'DES.designation_id');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Employee Code', 'UT.employee_code');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Headquanter', 'EL.headquater_id');?></th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Email', 'UT.email');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Username', 'US.username');?></th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Password', 'US.passwowrd_text');?></th>
									<th style="width:7%; text-align:center"><?php echo CommonFunction::OrderBy('DOB', 'UT.dob');?></th>
									<th style="width:5%; text-align:center"><?php echo CommonFunction::OrderBy('Login Status', 'UT.login_status');?></th>
									<th style="width:5%; text-align:center"><?php echo CommonFunction::OrderBy('Admin Status', 'UT.user_status');?></th>
									<th style="width:12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 $users = $this->Users['Records'];
							 if($users){
								for($i=0;$i<count($users);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
						<td align="center"><?php echo $users[$i]['first_name'].' '.$users[$i]['last_name']?></td>
						<td align="center"><?php echo $users[$i]['department_name'];?></td>
						<td align="center"><?php echo $users[$i]['designation_name'];?></td>
						<td align="center"><?php echo $users[$i]['employee_code'];?></td>
						<td align="center"><?php echo $users[$i]['headquater_name'];?></td>
						<td align="center"><?php echo $users[$i]['email'];?></td>
						<td align="center"><?php echo $users[$i]['username'];?></td>
						<td align="center"><?php echo $users[$i]['passwowrd_text'];?></td>
						<td align="center"><?php echo $users[$i]['dob'];?></td>
						<td align="center"  id="statusportion<?php echo $users[$i]['user_id'];?>">
						<?php if($users[$i]['login_status']=='1'){?>
						   <img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Inactive" border="0" 
				onclick="changeStatus('<?php echo 'employee_personaldetail';?>','<?php print $users[$i]['user_id']; ?>','login_status','0','user_id');" title="Active" class="changeStatus" />
						<?php }else{?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
				onclick="changeStatus('<?php echo 'employee_personaldetail';?>','<?php print $users[$i]['user_id']; ?>','login_status','1','user_id');" title="Inactive"  class="changeStatus" />
						<?php }?>
						</td>
						<td align="center" id="user_portion<?php echo $users[$i]['user_id'];?>">
						<?php if($users[$i]['user_status']=='1'){?>
						   <img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Inactive" border="0" 
				onclick="changeStatusByportion('<?php echo 'employee_personaldetail';?>','<?php print $users[$i]['user_id']; ?>','user_status','0','user_id','user_portion');" title="Active" class="changeStatus" />
						<?php }else{?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
				onclick="changeStatusByportion('<?php echo 'employee_personaldetail';?>','<?php print $users[$i]['user_id']; ?>','user_status','1','user_id','user_portion');" title="Inactive"  class="changeStatus" />
						<?php }?>
						</td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Users','action'=>'edituser','user_id'=>$users[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit User" /></a>&nbsp;|
						<a onclick="fancyboxopenfor('<?php echo $this->url(array('controller'=>'Users','action'=>'view','user_id'=>$users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a>&nbsp;|
					 <a  onclick="fancyboxopenforpriv('<?php echo $this->url(array('controller'=>'Users','action'=>'privillage','user_id'=>$users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/privillage.png" title="Privillages" /></a>&nbsp;|
					 <a  onclick="fancyboxopenfordelete('<?php echo $this->url(array('controller'=>'Users','action'=>'changepassword','user_id'=>$users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/change-password-icon.png" title="Change Password" /></a>&nbsp;|
					 <a  onclick="fancyboxopenfordelete('<?php echo $this->url(array('controller'=>'Users','action'=>'delete','user_id'=>$users[$i]['user_id'],'designation_id'=>$users[$i]['designation_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" /></a>
					 &nbsp;|
					 <a  href="<?php echo $this->url(array('controller'=>'Users','action'=>'userhistory','user_id'=>$users[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/history.png" title="History" /></a>
						</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="6">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
							<tr>
							<th colspan="13" style="text-align:left"><?php echo CommonFunction::PageCounter($this->Users['Total'], $this->Users['Offset'], $this->Users['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext');?></th>
							</tr>
                        </table>
                        </form>
                        
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