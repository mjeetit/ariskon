
	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>TP VS Actual</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="get" enctype="multipart/form-data">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="12"><table>
							<?php if($_SESSION['AdminDesignation']<5) { ?>
							<td>ZBM <select id="zbm_id" name="zbm_id" onchange="getchild(this.value,'rbm_id','6')">
							<option value="">-Select-</option>
							<?php foreach($this->zbmDetails as $users){ 
							  $selected = '';
							 if($this->filter['zbm_id']==Class_Encryption::encode($users['user_id'])){
							    $selected = 'selected="selected"';
							 }
							?>
							 <option value="<?php echo Class_Encryption::encode($users['user_id']);?>" <?php echo $selected;?>><?php echo $users['first_name']." ".$users['last_name']?></option>
							<?php } ?>
							</select></td>
							<?php } ?>
							<?php if($_SESSION['AdminDesignation']<6) { ?>
							<td>RBM <select id="rbm_id" name="rbm_id" onchange="getchild(this.value,'abm_id','7')">
							<option value="">-Select-</option>
							<?php foreach($this->rbmDetails as $users){ 
							  $selected = '';
							 if($this->filter['rbm_id']==Class_Encryption::encode($users['user_id'])){
							    $selected = 'selected="selected"';
							 }
							?>
							 <option value="<?php echo Class_Encryption::encode($users['user_id']);?>" <?php echo $selected;?>><?php echo $users['first_name']." ".$users['last_name']?></option>
							<?php } ?>
							</select></td>
							<?php } ?>
							<?php if($_SESSION['AdminDesignation']<7) { ?>
							<td>ABM <select id="abm_id" name="abm_id" onchange="getchild(this.value,'be_id','8')">
							<option value="">-Select-</option>
							<?php foreach($this->abmDetails as $users){ 
							 $selected = '';
							 if($this->filter['abm_id']==Class_Encryption::encode($users['user_id'])){
							    $selected = 'selected="selected"';
							 }
							?>
							 <option value="<?php echo Class_Encryption::encode($users['user_id']);?>" <?php echo $selected;?>><?php echo $users['first_name']." ".$users['last_name']?></option>
							<?php } ?>
							</select></td>
							<?php } ?>
							<?php if($_SESSION['AdminDesignation']<8) { ?>
							<td>BE <select name="be_id" id="be_id">
							<option value="">-Select-</option>
							<?php foreach($this->beDetails as $users){ 
						     $selected = '';
							 if($this->filter['be_id']==Class_Encryption::encode($users['user_id'])){
							    $selected = 'selected="selected"';
							 }
							?>
							 <option value="<?php echo Class_Encryption::encode($users['user_id']);?>"  <?php echo $selected;?>><?php echo $users['first_name']." ".$users['last_name']?></option>
							<?php } ?>
							</select></td>
							<?php } ?>
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
							<td>From <select name="from_date">
							<option value="">-Select-</option>
							<?php foreach($this->getmonths as $month){ 
							 $selected = '';
							 if($this->filter['from_date']==$month){
							    $selected = 'selected="selected"';
							 }
							?>
							
							 <option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo date("M-Y", strtotime($month) );?></option>
							<?php }?>
							</select></td>
							<td>To <select name="to_date">
							<option value="">-Select-</option>
							<?php foreach($this->getmonths as $month){ 
							 $selected = '';
							 if($this->filter['to_date']==$month){
							    $selected = 'selected="selected"';
							 }
							?>
							<option value="<?php echo $month;?>" <?php echo $selected;?>><?php echo date("M-Y", strtotime($month) );?></option>
							<?php }?>
							</select></td></tr>
							<tr class="odd">
								<td colspan="8" align="center"><input type="submit" name="submit" value="Search"  class="submit-green"/>  &nbsp;
								<input type="submit" name="Export" value="Export" class="submit-green"/></td>
							</tr>
						</table></td></tr>	
						<tr><th colspan="4"></th><th colspan="4">Planned as per TP</th><th colspan="4">Actual working</th></tr>
						<tr>
						<th>Employee Name</th>
						<th>Employee Code</th>
                        <th>Designation</th>
						<th>Date</th>
						<th>Planned HQ</th>
						<th>Place/Town</th>
						<th>Patch</th>
						<th>Work Planned With</th>
						<th>Worked HQ</th>
						<th>Place/Town</th>
						<th>Patch</th>
						<th>Worked  With</th>
						</tr>
						<?php if(!empty($this->vistdetail['Records'])){ 
						foreach($this->vistdetail['Records'] as $i=>$doctorvisit){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $doctorvisit['first_name'].' '.$doctorvisit['last_name']?></td>
						 <td><?php echo $doctorvisit['employee_code']?></td>
                         <td><?php echo $doctorvisit['designation_name'];?></td>
						 <td><?php echo $doctorvisit['tour_date'];?></td>
						 <td><?php echo $doctorvisit['headquater_name'];?></td>
						 <td><?php echo $doctorvisit['actual_hq'];?></td>				 
						 <td><?php echo $doctorvisit['city_name'];?></td>
						 <td><?php echo $doctorvisit['patch_name'];?></td>
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['be_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['abm_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['rbm_visit'])?></td>
						 <td><?php echo $doctorvisit['actual_city'];?></td>
						 <td><?php echo $doctorvisit['actual_patch'];?></td>
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['abe_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['aabm_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['arbm_visit'])?></td>
						 </tr>
						 <?php } }?>
                            </thead>
							<tr><th colspan="12" style="text-align:left"><?=CommonFunction::PageCounter($this->vistdetail['Total'], $this->vistdetail['Offset'], $this->vistdetail['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th></tr>
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
   function getchild(selected_value,selectbox_id,design_id){
	$.ajax({
		  url: '<?=$this->url(array('controller'=>'Ajax','action'=>'getchildselecteduser'),'default',true)?>',
		  data: 'user_id='+selected_value+'&design_id='+design_id,
		  success: function(data) {
		     //alert(data);//return false;
			$("#"+selectbox_id).html(data);
		  }
	 });
}
</script>			
