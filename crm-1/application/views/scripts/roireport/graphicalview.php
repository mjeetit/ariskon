 <link href="<?=Bootstrap::$baseUrl?>javascript/javascript/charting/css/basic.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/enhance.js"></script><!-- Graph and Chart JS-->	
	<script type="text/javascript">
		// Run capabilities test
		enhance({
			loadScripts: [
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/excanvas.js',
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/jquery.min.js',
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/visualize.jQuery.js',
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/js/example.js'
			],
			loadStyles: [
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/css/visualize.css',
				'<?=Bootstrap::$baseUrl?>javascript/javascript/charting/css/visualize-light.css'
			]	
		});   
    </script>
 
 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>ROI Report Section</span></h2>
                    
                    <div class="module-table-body">
                    	<!--Search and Filter Form-->
						<form name="searchnfilterform" id="searchnfilterform" method="post" action="">
						<div class="searchform">
							<div class="bold_text">
								<!--Doctor Pull Down List for Filter Option-->
								Doctor : 
								<select name="token1" style="width:150px;">
									<option value="">-- Select Doctor --</option>
									<?php foreach($this->doctors as $doctor){
										$select = '';
										if($this->Filterdata['token1']==Class_Encryption::encode($doctor['doctor_id'])){
											$select = 'selected="selected"';
										}
									?>
									<option value="<?=Class_Encryption::encode($doctor['doctor_id'])?>" <?=$select?>><?=$doctor['doctor_name']?></option>
									<?php } ?>
								</select>
								
								<!--Headquarter Pull Down List for Filter Option-->
								&nbsp;&nbsp;&nbsp;&nbsp;
								  <?php if($_SESSION['AdminDesignation']<8) {?>Headquarter :
								  <select name="token2" style="width:150px;">
									<option value="">-- Select Headquarter --</option>
									<?php foreach($this->headquarters as $headquarter){
										$select = '';
										if($this->Filterdata['token2']==Class_Encryption::encode($headquarter['headquater_id'])){
											$select = 'selected="selected"';
										}
									?>
									<option value="<?=Class_Encryption::encode($headquarter['headquater_id'])?>" <?=$select?>><?=$headquarter['location_code'].' - '.$headquarter['headquater_name']?></option>
									<?php } ?>
								  </select>
								  <?php } ?>
								  
									<!--From Date for Filter Option-->
									&nbsp;&nbsp;&nbsp;&nbsp;
									From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->Filterdata['from_date']?>" />
									&nbsp;&nbsp;&nbsp;&nbsp;
									To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->Filterdata['to_date']?>" />
							</div>
							
							<br /><div class="bold_text">
								<!--Business Executive (BE) Pull Down List for Filter Option-->
								  <?php if($_SESSION['AdminDesignation']<8) { ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BE : 
								  <select name="token3" style="width:150px;">
									<option value="">-- Select BE --</option>
									<?php foreach($this->beDetails as $beDetail){
										$select = '';
										if($this->Filterdata['token3']==Class_Encryption::encode($beDetail['user_id'])){
											$select = 'selected="selected"';
										}
									?>
									<option value="<?=Class_Encryption::encode($beDetail['user_id'])?>" <?=$select?>><?=$beDetail['employee_code'].' - '.$beDetail['first_name'].' '.$beDetail['last_name']?></option>
									<?php } ?>
								  </select>
								  <?php } ?>
								
								<!--Area Business Manager (ABM) Pull Down List for Filter Option-->
								&nbsp;&nbsp;&nbsp;&nbsp;
								<?php if($_SESSION['AdminDesignation']<7) { ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ABM : 
								<select name="token4" style="width:150px;">
								<option value="">-- Select ABM --</option>
								<?php foreach($this->abmDetails as $abmDetail){
									$select = '';
									if($this->Filterdata['token4']==Class_Encryption::encode($abmDetail['user_id'])){
										$select = 'selected="selected"';
									}
								?>
								<option value="<?=Class_Encryption::encode($abmDetail['user_id'])?>" <?=$select?>><?=$abmDetail['employee_code'].' - '.$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>
								<?php } ?>
								</select>
								<?php } ?>
								  
								<!--Regional Business Manager (RBM) Pull Down List for Filter Option-->
								&nbsp;&nbsp;&nbsp;&nbsp;
								<?php if($_SESSION['AdminDesignation']<6) { ?>RBM : 
								<select name="token5" style="width:150px;">
								<option value="">-- Select RBM --</option>
								<?php foreach($this->rbmDetails as $rbmDetail){
									$select = '';
									if($this->Filterdata['token5']==Class_Encryption::encode($rbmDetail['user_id'])){
										$select = 'selected="selected"';
									}
								?>
								<option value="<?=Class_Encryption::encode($rbmDetail['user_id'])?>" <?=$select?>><?=$rbmDetail['employee_code'].' - '.$rbmDetail['first_name'].' '.$rbmDetail['last_name']?></option>
								<?php } ?>
								</select>
								<?php } ?>
								
								<!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->
								&nbsp;&nbsp;&nbsp;&nbsp;
								<?php if($_SESSION['AdminDesignation']<5) { ?>ZBM : 
								<select name="token6" style="width:150px;">
								<option value="">-- Select ZBM --</option>
								<?php foreach($this->zbmDetails as $zbmDetail){
									$select = '';
									if($this->Filterdata['token6']==Class_Encryption::encode($zbmDetail['user_id'])){
										$select = 'selected="selected"';
									}
								?>
								<option value="<?=Class_Encryption::encode($zbmDetail['user_id'])?>" <?=$select?>><?=$zbmDetail['employee_code'].' - '.$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>
								<?php } ?>
								</select>
								<?php } ?>
									
								&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="filter" class="inputbutton" value="Search" />
							</div><br />
						</div>
                        </form><br /><br />
						
                        <table id="myTable" class="tablesorter" style="display:none;">
                        	<thead>
							   	<tr>
									<td></td>
									<th scope="col">CRM Amount</th>
									<th scope="col">CRM Expense</th>
									<th scope="col">ROI Amount</th>
								</tr>
                            </thead>
							
                            <tbody>
							<tr>
								<th scope="row">RN Choudhary</th>
								<td>1000</td>
								<td>160</td>
								<td>40</td>
							</tr>
							<tr>
								<th scope="row">Mohit Goyal</th>
								<td>3</td>
								<td>40</td>
								<td>30</td>
							</tr>
							<tr>
								<th scope="row">SN Sinha</th>
								<td>10</td>
								<td>180</td>
								<td>10</td>
							</tr>
							<tr>
								<th scope="row">12 RN Choudhary</th>
								<td>190</td>
								<td>160</td>
								<td>40</td>
							</tr>
							<tr>
								<th scope="row">12 Mohit Goyal</th>
								<td>3</td>
								<td>40</td>
								<td>30</td>
							</tr>
							<tr>
								<th scope="row">12 SN Sinha</th>
								<td>10</td>
								<td>180</td>
								<td>10</td>
							</tr>
							<tr>
								<th scope="row">23 RN Choudhary</th>
								<td>190</td>
								<td>160</td>
								<td>40</td>
							</tr>
							<tr>
								<th scope="row">23 Mohit Goyal</th>
								<td>3</td>
								<td>40</td>
								<td>30</td>
							</tr>
							<tr>
								<th scope="row">23 SN Sinha</th>
								<td>10</td>
								<td>180</td>
								<td>10</td>
							</tr>
							 <?php //echo "<pre>";print_r($this->rois);die;
							 /*if(count($this->rois)>0){
								$roiMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));
								//foreach($this->rois as $i=>$roi) {
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								  <th scope="row"><?=$roi['doctor_name']?></th>
								  <td align="center"><?=number_format($roi['expenseCost'],2)?></td>
								  <td align="center"><?=number_format($roi['crmAmount'],2)?>676</td>
								  <td align="center"><?=number_format($roi['roiAmount'],2)?></td>
								  <!--<td align="center"><?=date("M Y",mktime(0,0,0,date('m')-1,date('d'),date('Y')))?></td>-->								  
								</tr>
								<?php } } else{ ?>
								<tr>
								  <td align="center" colspan="6">No ROI found !!...</td>
								</tr>
								<?php } */ ?>
                            </tbody>
                        </table><br /><br />
                        <div style="clear: both"></div>
                     </div>
                </div>
			</div>	
	
	<script type="text/javascript">	
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