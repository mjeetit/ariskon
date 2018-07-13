	<?php // print_r($this->filter); ?>
	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>TP Summary</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="get" enctype="multipart/form-data">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="11"><table>
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
							<?php }?>
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
								<td colspan="7" align="center"><input type="submit" name="submit" value="Search"  class="submit-green"/>  &nbsp;
								<input type="submit" name="Export" value="Export" class="submit-green"/></td>
							</tr>
						</table></td></tr>	
						<tr>
						<th>Employee Name</th>
                        <th>Designation</th>
						<th>Headquater</th>
						<th>Month</th>
						<th>No. of Days Work Planned</th>
						<th>HQ</th>
						<th>EX</th>
						<th>Out</th>
						</tr>
						<?php if(!empty($this->vistdetail['Records'])){ 
						foreach($this->vistdetail['Records'] as $i=>$doctorvisit){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $doctorvisit['first_name'].' '.$doctorvisit['last_name']?></td>
						 <td><?php echo $doctorvisit['designation_name']?></td>
                         <td><?php echo $doctorvisit['headquater_name'];?></td>
						 <td><?php echo date('F, Y',strtotime($doctorvisit['visit_month']));?></td>
						 <td><?php echo count(array_unique(explode(',',$doctorvisit['CNT'])));?></td>
						 <td><?php echo $doctorvisit['hq_count'];?></td>
						 <td><?php echo $doctorvisit['ex_count'];?></td>
						 <td><?php echo $doctorvisit['out_count'];?></td>
						 
						 </tr>
						 <?php } }?>
                            </thead>
							<tr><th colspan="11" style="text-align:left"><?=CommonFunction::PageCounter($this->vistdetail['Total'], $this->vistdetail['Offset'], $this->vistdetail['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th></tr>
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
