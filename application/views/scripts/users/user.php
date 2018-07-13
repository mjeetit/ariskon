 <div class="grid_12">
	<!-- Example table -->
	<div class="module">
		<h2><span>User List</span></h2>
		<div class="module-table-body">
			<form action="" method="get">
				<table id="myTable1" class="tablesorter">
					<thead>
						<tr> 
							<th colspan="12" align="center">Search Option</th>
						</tr>
						<tr>
							<td>Employee</td>
							<td><?php  echo $this->htmlSelectUser('user_id',$this->filteruser,$this->filter['user_id'],array('class'=>'input-medium'));?></td>
							<td>Designation</td>
							<td><?php  echo $this->htmlSelect('designation_id',CommonFunction::getAssociative($this->filterdesignation,'designation_id','designation_name'),$this->filter['designation_id'],array('class'=>'input-medium'));?></td>
							<td>Department</td>
							<td><?php  echo $this->htmlSelect('department_id',CommonFunction::getAssociative($this->filterdepartment,'department_id','department_name'),$this->filter['department_id'],array('class'=>'input-medium'));?></td>
						</tr>
						<tr>
							<td>Headquater</td>
							<td><?php  echo $this->htmlSelect('headquater_id',CommonFunction::getAssociative($this->filterheadquater,'headquater_id','headquater_name'),$this->filter['headquater_id'],array('class'=>'input-medium'));?></td>
							<td>Serch Word</td>
							<td><input type="text" name="search_word" class="input-medium" value="<?php echo $this->filter['search_word']?>" /></td>
							<td><input type="submit" name="search" value="Serach"  class="submit-green" /></td>
						</tr>
						<tr>
							<td colspan="6">
								<input type="submit" name="export" class="submit-green" value="Export Ongano Gram Report"/>
							</td>
						</tr>
					</thead>
				</table>	
				<table id="myTable" class="tablesorter">
					<thead>
						<tr> 
							<td colspan="12"> 
								<a href="<?php echo $this->url(array('controller'=>'Users','action'=>'adduser'),'default',true)?>" class="button">
								<span>Add New User<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add New User" />
								</span>
								</a>
							</td>
						</tr>
						<tr>
							<th style="width:8%;text-align:center">Employee Code</th>
							<th style="width:10%;text-align:center">Name</th>
							<th style="width:8%;text-align:center">Designation</th>
							<th style="width:8%;text-align:center">Department</th>
							<th style="width:8%;text-align:center">Headquanter</th>
							<th style="width:10%;text-align:center">Email</th>
							<th style="width:8%;text-align:center">Username</th>
							<th style="width:10%;text-align:center">Password</th>
							<!--<th style="width:7%;text-align:center">DOB</th>-->
							<th style="width:5%;text-align:center" id="noClass1">Login Status</th>
							<th style="width:5%;text-align:center" id="noClass2">Admin Status</th>
							<th style="width:12%;text-align:center" id="noClass3">Action</th>
						</tr>
						<tr id="filterrow">
							<th style="width:8%;text-align:center">Employee Code</th>
							<th style="width:10%;text-align:center">Name</th>
							<th style="width:8%;text-align:center">Designation</th>
							<th style="width:8%;text-align:center">Department</th>
							<th style="width:8%;text-align:center">Headquanter</th>
							<th style="width:10%;text-align:center">Email</th>
							<th style="width:8%;text-align:center">Username</th>
							<th style="width:10%;text-align:center">Password</th>
							<!--<th style="width:7%;text-align:center" id="date"></th>-->
							<td style="width:5%;background-color:#eee"></td>
							<td style="width:5%;background-color:#eee"></td>
							<td style="width:12%;background-color:#eee"></td>
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
						<td align="center"><?php echo $users[$i]['employee_code'];?></td>
						<td align="center"><?php echo $users[$i]['first_name'].' '.$users[$i]['last_name']?></td>
						<td align="center"><?php echo $users[$i]['designation_name'];?></td>
						<td align="center"><?php echo $users[$i]['department_name'];?></td>
						<td align="center"><?php echo $users[$i]['headquater_name'];?></td>
						<td align="center"><?php echo $users[$i]['email'];?></td>
						<td align="center"><?php echo $users[$i]['username'];?></td>
						<td align="center"><?php echo $users[$i]['passwowrd_text'];?></td>
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
					 &nbsp;|
					 <a  onclick="fancyboxopenforpriv('<?php echo $this->url(array('controller'=>'Users','action'=>'crmprivilege','token'=>$users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/setting.png" title="CRM Privileges" /></a>
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
    </div><!-- End .module -->
</div> <!-- End .grid_12 -->
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
 $(function(){
 var table = $('#myTable').DataTable({
   pageLength: 30,
   "order": [[ 0, "desc" ]],
   orderCellsTop: true
  });
     // Setup - add a text input to each footer cell
    $('#myTable thead tr#filterrow th').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 	$('#myTable thead tr#filterrow th#date').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-1" style="width:60%"  placeholder="DOB" />' );
    } );
    // DataTable
    $('#datepicker-1').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
     
    // Apply the filter
    $("#myTable thead input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );
    $("#noClass1").removeClass();
    $("#noClass2").removeClass();
    $("#noClass3").removeClass();
 });
  $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
        $("#noClass2").removeClass();
        $("#noClass3").removeClass();
    });
});
 </script>