	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Next Tour Plan</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="get" enctype="multipart/form-data">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
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
							 $selected = '';
							 if($this->filter['hedquater_id']==$hedquaters['headquater_id']){
							    $selected = 'selected="selected"';
							 }
							?>
							
							 <option value="<?php echo $hedquaters['headquater_id'];?>" <?php echo $selected;?>><?php echo $hedquaters['headquater_name']?></option>
							<?php }?>
							</select></td>
							<td><input type="submit" name="submit" value="Search"  class="submit-green"/></td>
							<td><input type="submit" name="Export" value="Export" class="submit-green"/></td>
							</tr>
						<tr>
						<th>Employee Name</th>
						<th>Employee Code</th>
                        <th>Designation</th>
						<th>Headquater</th>
						<th>Call Day</th>
						<th>Month</th>
						<th>Action</th>
						</tr>
						<?php if(!empty($this->vistdetail)){ 
						foreach($this->vistdetail as $i=>$doctorvisit){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $doctorvisit['first_name'].' '.$doctorvisit['last_name']?></td>
						 <td><?php echo $doctorvisit['employee_code']?></td>
                         <td><?php echo $doctorvisit['designation_name'];?></td>
						 <td><?php echo $doctorvisit['headquater_name'];?></td>
						 <td><?php echo count(array_unique(explode(',',$doctorvisit['CNT'])));?></td>
						 <td><?php echo date('M-Y',strtotime($doctorvisit['visit_month']))?></td>
						 <td align="center">
						 <!--<a href="<?php echo $this->url(array('controller'=>'Reporting','action'=>'repordetail','user_id'=>$doctorvisit['user_id'],'Mode'=>'Doctor'));?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>-->
						 <a href="<?php echo $this->url(array('controller'=>'Tourplan','action'=>'nexttourdetail','user_id'=>$doctorvisit['user_id']));?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>&nbsp;|&nbsp;
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
