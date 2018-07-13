	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Active Tour Plan</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="get" enctype="multipart/form-data">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="10">
							<table>
							<tr>
							<td>Employee <select name="user_id">
							<option value="">-Select-</option>
							<?php foreach($this->allusers as $key=>$users){
							$select = '';

										if($this->filter['user_id']==$key){

											$select = 'selected="selected"';

										}?>
							 <option value="<?php echo $key;?>" <?php echo $select; ?>><?php echo $users;?></option>
							<?php } ?>
							</select></td>
							
							<td>Headquater <select name="hedquater_id">
							<option value="">-Select-</option>
							<?php foreach($this->headquater as $hedquaters){ 
							      $select = '';

										if($this->filter['hedquater_id']==$hedquaters['headquater_id']){

											$select = 'selected="selected"';

										}?>
							
							 <option value="<?php echo $hedquaters['headquater_id'];?>" <?php echo $select; ?> ><?php echo $hedquaters['headquater_name']?></option>
							<?php }?>
							</select></td>
							<td><input type="submit" name="submit" value="Search" /></td>
							<td><input type="submit" name="Export" value="Export" /></td>
							</tr>
						</table>
						</td>
						</tr>
							
						<tr>
						<th>S.No.</th>
						<th>Employee Name</th>
						<th>Employee Code</th>
                        <th>Designation</th>
						<th>Headquater</th>
						<th>Call Day</th>
						<th>Month</th>
						<th>Approval Auth.</th>
						<th>Approve Status</th>
						<th>Action</th>
						</tr>
						<?php if(!empty($this->vistdetail)){ 
						foreach($this->vistdetail as $i=>$doctorvisit){
						 $class = ($i%2==0)?'even':'odd'; ?>
    					 <tr class="<?php echo  $class;?>">
						 <td><?php echo ($i+1)?></td> 
						 <td><?php echo $doctorvisit['first_name'].' '.$doctorvisit['last_name']?></td>
						 <td><?php echo $doctorvisit['employee_code']?></td>
                         <td><?php echo $doctorvisit['designation_name'];?></td>
						 <td><?php echo $doctorvisit['headquater_name'];?></td>
						 <td><?php echo count(array_unique(explode(',',$doctorvisit['CNT'])));?></td>
						 <td><?php echo date('M-Y',strtotime($doctorvisit['visit_month']))?></td>
						 
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['parent_id']);?></td>
						 <td <?php echo ($doctorvisit['accepte_by']>0)?'style="background-color: green;"':'style="background-color: yellow;"';?>><?php echo ($doctorvisit['accepte_by']>0)?'Approved':'Pending';?></td>
						 
						 <td align="center">
						 <a href="<?php echo $this->url(array('controller'=>'Tourplan','action'=>'currenttourdetil','user_id'=>$doctorvisit['user_id'],'Mode'=>'Current'));?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="view" /></a>&nbsp;|&nbsp;
						 <a onclick="rejecttour('<?php echo $this->url(array('controller'=>'Tourplan','action'=>'rejecttp','user_id'=>$doctorvisit['user_id'],'month'=>date('Y-m',strtotime($doctorvisit['visit_month']))));?>')" href="javascript:void(0)"><img title="Reject" src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" /></a>
						 </td>
						 </tr>
						 <?php } }?>
                            </thead>
                        </table>
                       
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 --> 
			
<script type="text/javascript">
function rejecttour(url){
  if(confirm('Are you sure you want to Reject?')){
     $.ajax({
	    url : url,
		success : function(msg){
		  location.reload();
		}
	 });
  }
}
</script>			
