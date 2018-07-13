<style type="text/css">
	select#parcel_action{width:90px !important}
	select.icon-menu option {
		background-repeat:no-repeat;
		background-position:bottom left;
		padding-left:0px; padding-top:5px; 
	}
	
	<!--Checkbox Inner color CSS-->
	label { margin-right:20px; font-family:Arial, Helvetica, sans-serif; }
	input[type=checkbox].css-checkbox {display:none;}
	input[type=checkbox].css-checkbox + label.css-label {
		padding-left:19px;
		height:14px; 
		display:inline-block;
		line-height:14px;
		background-repeat:no-repeat;
		background-position: 3px 0;
		font-size:14px;
		vertical-align:middle;
		cursor:pointer;
	}
	input[type=checkbox].css-checkbox:checked + label.css-label { background-position: 3px -14px !important; }
	label.css-label {
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	
	<!--Red Checkbox for Error Parcels-->
	input[type=checkbox].css-checkbox + label.css-label2 {
		padding-left:19px;
		height:14px; 
		display:inline-block;
		line-height:14px;
		background-repeat:no-repeat;
		background-position: 3px 0;
		font-size:14px;
		vertical-align:middle;
		cursor:pointer;
	}
	input[type=checkbox].css-checkbox:checked + label.css-label2 { background-position: 0px -14px !important; }
	label.css-label2 {
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		height:14px;
		width:14px;
		float:left;
	}
	
	<!--Yellow Checkbox for Informational Parcels-->
	input[type=checkbox].css-checkbox + label.css-label3 {
		padding-left:19px;
		height:14px; 
		display:inline-block;
		line-height:14px;
		background-repeat:no-repeat;
		background-position: 3px 0;
		font-size:14px;
		vertical-align:middle;
		cursor:pointer;
	}
	input[type=checkbox].css-checkbox:checked + label.css-label3 { background-position: 0px -14px !important; }
	label.css-label3 {
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		height:14px;
		width:14px;
		float:left;
	}
	
	<!--Tooltip CSS-->
	.demo a{
		display:inline-block;
		position:relative;
	}
	.em1{
		color:#009933;
	}	
	.tooltip-container {
		position:relative;	/* Forces tooltip to be relative to the element, not the page */
		cursor:help;		/* Makes you cursor have the help symbol */
	}	
	.tooltip {
		display:block;
		position:absolute;
		width:150px;
		padding:5px 15px;
		left:50%;
		bottom:25px;
		margin-left:-95px;
		/* Tooltip Style */
		color:#fff;
		border:2px solid rgba(34,34,34,0.9);
		background:rgba(51,51,51,0.9);
		text-align:center;
		border-radius:3px;
		/* Tooltip Style */
		opacity:0;
		box-shadow:0px 0px 3px rgba(0, 0, 0, 0.3);
		-webkit-transition:all 0.2s ease-in-out;
		-moz-transition:all 0.2s ease-in-out;
		-0-transition:all 0.2s ease-in-out;
		-ms-transition:all 0.2s ease-in-out;
		transition:all 0.2s ease-in-out;
		-webkit-transform:scale(0);
		-moz-transform:scale(0);
		-o-transform:scale(0);
		-ms-transform:scale(0);
		transform:scale(0);
		/* reset tooltip, to not use container styling */
		font-size:14px;
		font-weight:normal;
		font-style:normal;
	}
	
	.tooltip:before, .tooltip:after{
		content:'';
		position:absolute;
		bottom:-13px;
		left:50%;
		margin-left:-9px;
		width:0;
		height:0;
		border-left:10px solid transparent;
		border-right:10px solid transparent;
		border-top:10px solid rgba(0,0,0,0.1);
	}
	.tooltip:after{
		bottom:-12px;
		margin-left:-10px;
		border-top:10px solid rgba(34,34,34,0.9);
	}
	
	.tooltip-container:hover .tooltip, a:hover .tooltip {
		/* Makes the Tooltip slightly transparent, Lets the barely see though it */
		opacity:0.9;
		/* Changes the scale from 0 to 1 - This is what animtes our tooltip! */
		-webkit-transform:scale(1);
		-moz-transform:scale(1);
		-o-transform:scale(1);
		-ms-transform:scale(1);
		transform:scale(1);
		font-family: Verdana;
		font-size:11px;
	}
	
	/* Pure CSS3 Animated Tooltip - Custom Classes
	---------------------------------------------------- */
	.tooltip-style1 {
		color:#000;
		border:2px solid #fff;
		background:rgba(246,246,246,0.9);
		font-style:italic;
	}
	.tooltip-style1:after{
		border-top:10px solid #fff;
	}
	</style>
	 <div class="grid_12">    
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Monthly Activity Summary</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="get" enctype="multipart/form-data">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="13"><table>
							<?php if($_SESSION['AdminDesignation']<5) { ?>
							<td>ZBM <select id="zbm_id" name="zbm_id" onchange="getchild(this.value,'rbm_id','6');gethq(this.value,'headquater_id','5','');">
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
							<td>RBM <select id="rbm_id" name="rbm_id" onchange="getchild(this.value,'abm_id','7');gethq(this.value,'headquater_id','6','');">
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
							<td>ABM <select id="abm_id" name="abm_id" onchange="getchild(this.value,'be_id','8');gethq(this.value,'headquater_id','7','');">
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
							<td>BE <select name="be_id" id="be_id" onchange="gethq(this.value,'headquater_id','8','');">
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
							<td>Headquater <select name="headquater_id" id="headquater_id">
							<option value="">-Select-</option>
							<?php foreach($this->headquarters as $hedquaters){ 
							 $selected = '';
							 if($this->filter['hedquater_id']==$hedquaters['headquater_id']){
							    $selected = 'selected="selected"';
							 }
							?>
							
							 <option value="<?php echo $hedquaters['headquater_id'];?>" <?php echo $selected;?>><?php echo $hedquaters['headquater_name']?></option>
							<?php }?>
							</select></td>
							<td width="20%" class="bold_text" align="left">

									From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->filter['from_date']?>" />

								</td>

								<td width="20%" class="bold_text" align="left">

									To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->filter['to_date']?>" />

								</td></tr>
							<tr class="odd">
								<td colspan="8" align="center"><input type="submit" name="submit" value="Search"  class="submit-green"/>  &nbsp;
								<input type="submit" name="Export" value="Export" class="submit-green"/></td>
							</tr>
						</table></td></tr>	
						<tr>
						<th>Name</th>
						<th>Emp. Code</th>
                        <th>Desig</th>
						<th>Month</th>
						<th>Date</th>
						<th>Day</th>
						<th>HQ Plaaned</th>
						<th>Plaaned With</th>
						<th>HQ of worked</th>
						<th>Worked with</th>
						<th>Doctors Call</th>
						<th>Chemist Call</th>
						</tr>
						<?php if(!empty($this->vistdetail['Records'])){ 
						foreach($this->vistdetail['Records'] as $i=>$doctorvisit){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $doctorvisit['first_name'].' '.$doctorvisit['last_name']?></td>
						 <td><?php echo $doctorvisit['employee_code']?></td>
                         <td><?php echo $doctorvisit['designation_name'];?></td>
						 <td><?php echo $doctorvisit['call_month'];?></td>					 
						 <td><?php echo $doctorvisit['call_date'];?></td>
						 <td><?php echo date('D',strtotime($doctorvisit['call_date']));?></td>
						 <td><?php echo $doctorvisit['planned_hq'];?></td>	
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['tpbe_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['tpabm_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['tprbm_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['tpzbm_visit'])?></td>			 
						 <td><?php echo $doctorvisit['headquater_name'];?></td>
						 <td><?php echo $this->ObjModel->getEmpname($doctorvisit['be_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['abm_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['rbm_visit'])."<br>".$this->ObjModel->getEmpname($doctorvisit['zbm_visit'])?></td>
						 <td><p class="tooltip-container" style="color:#009933;"><?php echo $doctorvisit['TDC'];?><span class="tooltip"><?=str_replace(',','<br>',$doctorvisit['dtrs'])?></span></p></td>
						 <td><?php echo $doctorvisit['CVC'];?></td>
						 
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
	var zbm = '<?php echo $this->filter['zbm_id'];?>';
	 var rbm = '<?php echo $this->filter['rbm_id'];?>';
	 var abm = '<?php echo $this->filter['abm_id'];?>';
	 var be = '<?php echo $this->filter['be_id'];?>';
     var hq_id = '<?php echo $this->filter['headquater_idheadquater_id'];?>';
     if(zbm!=''){
	 	getchild(zbm,'rbm_id','6',rbm);
		gethq(zbm,'headquater_id','5',hq_id);
	 }
	 if(rbm!=''){
	 	getchild(rbm,'abm_id','7',abm);
		gethq(rbm,'headquater_id','6',hq_id);
	 }
	 if(abm!=''){
	 	getchild(abm,'be_id','8',be);
		gethq(abm,'headquater_id','7',hq_id);
	 }
	 if(be!=''){
	   gethq(be,'headquater_id','8',hq_id);
	 }

	$(function() {		

		$("#from_date" ).datepicker({

			showOn: "button",

			buttonImage: "<?=Bootstrap::$baseUrl;?>public/admin_images/calendar.gif",

			buttonImageOnly: true,

			dateFormat: "yy-mm-dd"

		});

		$("#to_date" ).datepicker({

			showOn: "button",

			buttonImage: "<?=Bootstrap::$baseUrl;?>public/admin_images/calendar.gif",

			buttonImageOnly: true,

			dateFormat: "yy-mm-dd"

		});

	});

</script>			
