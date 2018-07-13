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

                	<h2><span>Activity Summary Report</span></h2>

                    

                    <div class="module-table-body"> 

						<!-- START : Search Form -->

						<form name="listFrom" id="listForm" action="" method="get" >

							<table width="96%" border="0" cellspacing="1" cellpadding="2">

							  <tr>

								<th align="center" colspan="6">Search Form</th>

							  </tr>

							  

							  <tr class="even">

								<!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']<5) { ?>

								<td align="left" class="bold_text">ZBM : 

								  <select id="zbm_id" name="token6" onchange="getchild(this.value,'rbm_id','6')">

									<option value="">-- Select ZBM --</option>

									<?php foreach($this->zbmDetails as $zbmDetail){

										$select = '';

										if($this->filter['token6']==Class_Encryption::encode($zbmDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($zbmDetail['user_id'])?>" <?=$select?>><?=$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>

									<?php } ?>

								  </select>								  

								</td>

								<?php } ?>

								

								<!--Regional Business Manager (RBM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']<6) { ?>

								<td align="left" class="bold_text">RBM : 

								 <select id="rbm_id" name="token5" onchange="getchild(this.value,'abm_id','7')">

									<option value="">-- Select RBM --</option>

									<?php foreach($this->rbmDetails as $rbmDetail){

										$select = '';

										if($this->filter['token5']==Class_Encryption::encode($rbmDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($rbmDetail['user_id'])?>" <?=$select?>><?=$rbmDetail['first_name'].' '.$rbmDetail['last_name']?></option>

									<?php } ?>

								  </select>

								</td>

								<?php } ?>

								

								<!--Area Business Manager (ABM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']<7) { ?>

								<td align="left" class="bold_text">ABM : 

								  <select id="abm_id" name="token4" onchange="getchild(this.value,'be_id','8')">

									<option value="">-- Select ABM --</option>

									<?php foreach($this->abmDetails as $abmDetail){

										$select = '';

										if($this->filter['token4']==Class_Encryption::encode($abmDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($abmDetail['user_id'])?>" <?=$select?>><?=$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>

									<?php } ?>

								  </select>

								</td>

								<?php } ?>

								

								<!--Business Executive (BE) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']<8) { ?>

								<td align="left" class="bold_text">BE : 

								  <select name="token3" id="be_id">

									<option value="">-- Select BE --</option>

									<?php foreach($this->beDetails as $beDetail){

										$select = '';

										if($this->filter['token3']==Class_Encryption::encode($beDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($beDetail['user_id'])?>" <?=$select?>><?=$beDetail['first_name'].' '.$beDetail['last_name']?></option>

									<?php } ?>

								  </select>

								</td>

								<?php } ?>
								<td width="20%" class="bold_text" align="left">

									From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->filter['from_date']?>" />

								</td>

								<td width="20%" class="bold_text" align="left">

									To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->filter['to_date']?>" />

								</td>

                                
							  </tr>

							  

							  <tr class="odd">
							  <td align="left" class="bold_text">Year : 
									<?php $yearform = (isset($this->filter['year']) && !empty($this->filter['year'])) ? trim($this->filter['year']) : date('Y');?>
									<select name="year" id="year">
										<option value="2014" <?php if($yearform=='2014'){ ?>selected="selected"<?php } ?>>2014</option>
										<option value="2015" <?php if($yearform=='2015'){ ?>selected="selected"<?php } ?>>2015</option>
										<option value="2016" <?php if($yearform=='2016'){ ?>selected="selected"<?php } ?>>2016</option>
										<option value="2017" <?php if($yearform=='2017'){ ?>selected="selected"<?php } ?>>2017</option>
										<option value="2018" <?php if($yearform=='2018'){ ?>selected="selected"<?php } ?>>2018</option> 
									</select>
								</td>
							  <td align="left" class="bold_text">Month : 
									<?php $monthform = (isset($this->filter['month']) && !empty($this->filter['month'])) ? trim($this->filter['month']) : date('m');?>
									<select name="month" id="month">
										<option value="01" <?php if($monthform=='01'){ ?>selected="selected"<?php } ?>>January</option>
										<option value="02" <?php if($monthform=='02'){ ?>selected="selected"<?php } ?>>February</option>
										<option value="03" <?php if($monthform=='03'){ ?>selected="selected"<?php } ?>>March</option>
										<option value="04" <?php if($monthform=='04'){ ?>selected="selected"<?php } ?>>April</option>
										<option value="05" <?php if($monthform=='05'){ ?>selected="selected"<?php } ?>>May</option>
										<option value="06" <?php if($monthform=='06'){ ?>selected="selected"<?php } ?>>June</option>
										<option value="07" <?php if($monthform=='07'){ ?>selected="selected"<?php } ?>>July</option>
										<option value="08" <?php if($monthform=='08'){ ?>selected="selected"<?php } ?>>August</option>
										<option value="09" <?php if($monthform=='09'){ ?>selected="selected"<?php } ?>>September</option>
										<option value="10" <?php if($monthform=='10'){ ?>selected="selected"<?php } ?>>October</option>
										<option value="11" <?php if($monthform=='11'){ ?>selected="selected"<?php } ?>>November</option>
										<option value="12" <?php if($monthform=='12'){ ?>selected="selected"<?php } ?>>December</option>
									</select>
								</td>

								<td colspan="4" align="center">

									<input type="submit" name="filter" class="submit-green" value="Search" /> &nbsp;

									<input type="submit" name="exportVisit" class="submit-green" value="Export in Excel" title="Export Data in Excel" />&nbsp;
									
									<input type="submit" name="exportSummary" class="submit-green" value="Export Summary" title="Export Summary In Excel" />

								</td>

							  </tr>

							</table>

						</form>

						<!-- END : Search Form -->

							

							<table id="myTable" class="tablesorter">

							<thead>							
							<tr>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Name','EE.call_date')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Designation','UD.first_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('HQ','UD1.first_name')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Month','EE.work_with_ho')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('No. of days worked','UD.first_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('No of non call activity day','UD1.first_name')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('No of Leave','EE.work_with_ho')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('last reported date','CD.doctor_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('No. of Doctor Visited more then thrice','PT.patch_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('No of DR Visited Thrice','CT.city_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('No. of dr Visited twice','CT.city_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('No. of Dr Visited once','CT.city_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('No. of Dr Not Visited','CT.city_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Call Average','CT.city_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('No of Non listed Call','CT.city_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('No Of Chemist call','CT.city_name')?></th>



							</tr>

						

							<?php 

							if(count($this->vistdetail['Records'])>0){

								foreach($this->vistdetail['Records'] as $key=>$report) {

								  $class = ($key%2==0)?'even':'odd';


							?>

						 <tr class="<?=$class?>">

						 <td><?=$report['Emp']?></td>

						 <td><?=$report['designation_name']?></td>
						<td><?=$report['headquater_name'];?></td> 
						 <td><?=date('M-Y',strtotime($report['visit_month']));?></td>

						 <td><?=$report['DAY_CNT']?></td> 

						 <td><?=$report['Total_NC'];?></td> 
						 
						 <td><?=$report['total_leave'];?></td>

						 <td><?=$report['last_reporting']?></td>
						 <?php $getcounts = array_count_values(explode(',',$report['ALL_DOCOTR']));
						 $getdoctor_name = array_count_values(explode(',',$report['ALL_DOCOTRNAME']));
						 
						   $morethantrice = 0;
						   $thrice = 0;
						   $twice = 0;
						   $once = 0;
						   $morethantrice_name = '';
						   $thrice_name = '';
						   $twice_name = '';
						   $once_name = '';
						   foreach($getdoctor_name as $doctor_name=>$getcount){
						      if($getcount>3){
							     $morethantrice = $morethantrice +1;
								 $morethantrice_name .= $doctor_name.'<br>';
							  }
							  if($getcount==3){
							     $thrice = $thrice +1;
								 $thrice_name .= $doctor_name.'<br>';
							  }
							  if($getcount==2){
							     $twice = $twice +1;
								 $twice_name .= $doctor_name.'<br>';
							  }
							  if($getcount==1){
							     $once = $once +1;
								 $once_name .= $doctor_name.'<br>';
							  }
						   }
						   //print_r($getcounts);die;
						 ?>
						 <td><p class="tooltip-container" style="color:#009933;"><?=$morethantrice?><span class="tooltip"><?=$morethantrice_name?></span></p></td>

						 <td><p class="tooltip-container" style="color:#009933;"><?=$thrice?><span class="tooltip"><?=$thrice_name?></span></p></td>
						 <td><p class="tooltip-container" style="color:#009933;"><?=$twice?>
						 <span class="tooltip"><?=$twice_name?></span></p></td>
						 <td><p class="tooltip-container" style="color:#009933;"><?=$once?>
						 <span class="tooltip"><?=$once_name?></span></p></td>
						 <td><?=$this->ObjModel->GetNonvisitedDR($report['ALL_DOCOTR'],$report['user_id'],$report['designation_id']);?></td>
						 <td><?=$report['Call_Avg']?></td>
						 
						 <td><?=$report['Total_NLD']?></td>
						 <td><?=$report['Total_CV']?></td>
						 </tr>

						 <?php } }  else{ ?>

						<tr>

						<td align="center" colspan="8">No Record Found!...</td>

						</tr>

						<?php }?>

						 

						 <!-- Paging Style : 1 -->

						<tr>

						<th colspan="13" style="text-align:left"><?=CommonFunction::PageCounter($this->vistdetail['Total'], $this->vistdetail['Offset'], $this->vistdetail['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>

						</tr>

                       </thead>

                      </table>                       

                        <div style="clear: both"></div>

                     </div> <!-- End .module-table-body -->

                </div> 

				<!-- End .module -->

			</div> <!-- End .grid_12 --> 

			

	<script type="text/javascript">
	var zbm = '<?php echo $this->filter['token6'];?>';
	 var rbm = '<?php echo $this->filter['token5'];?>';
	 var abm = '<?php echo $this->filter['token4'];?>';
	 var be = '<?php echo $this->filter['token3'];?>';
     if(zbm!=''){
	 	getchild(zbm,'rbm_id','6',rbm);
	 }
	 if(rbm!=''){
	 	getchild(rbm,'abm_id','7',abm);
	 }
	 if(abm!=''){
	 	getchild(abm,'be_id','8',be);
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

