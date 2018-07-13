 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Doctor Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<table id="myTable" class="tablesorter">
                        	<thead>
							<!-- START : Search Form -->
							<tr>
							  <td>
							  	<form name="listFrom" id="listForm" action="" method="get" >
								<table width="96%" border="0" cellspacing="1" cellpadding="2">
								  <tr height="26">
									<th align="center" colspan="5">Search Form</th>
								  </tr>
								  
								  <tr class="even">
								    <!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->
									<?php if($_SESSION['AdminDesignation']<5) { ?>
									<td align="left" class="bold_text">ZBM : 
									  <select name="token6" id="zbmlist">
										<option value="0">-- Select ZBM --</option>
										<?php foreach($this->zbmDetails as $zbmDetail){
											$select = '';
											if($this->Filterdata['token6']==Class_Encryption::encode($zbmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($zbmDetail['user_id'])?>" <?=$select?>><?=$zbmDetail['employee_code'].' - '.$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>
										<?php } ?>
									  </select>
									</td>
									<?php } ?>
									
									<!--Regional Business Manager (RBM) Pull Down List for Filter Option-->
									<?php if($_SESSION['AdminDesignation']<6) { ?>
									<td align="left" class="bold_text">RBM : 
									  <select name="token5" id="rbmlist">
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
									</td>
									<?php } ?>
									
									<!--Area Business Manager (ABM) Pull Down List for Filter Option-->
									<?php if($_SESSION['AdminDesignation']<7) { ?>
									<td align="left" class="bold_text">ABM : 
									  <select name="token4" id="abmlist">
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
									</td>
									<?php } ?>
									
								    <!--Business Executive (BE) Pull Down List for Filter Option-->
									<?php if($_SESSION['AdminDesignation']<8) { ?>
									<td align="left" class="bold_text" colspan="2">BE : 
									  <select name="token3" id="belist">
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
									</td>
									<?php } ?>
							      </tr>
								  
								  <tr class="even">
								    <!--Zone Pull Down List for Filter Option-->
									<td width="23%" align="left" class="bold_text">Zone : 
									  <select name="zone" id="zone">
										<option value="">-- Select Zone --</option>
										<?php foreach($this->zbmDetails as $zbmDetail){
											$select = '';
											if($this->Filterdata['token6']==Class_Encryption::encode($zbmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($zbmDetail['user_id'])?>" <?=$select?>><?=$zbmDetail['employee_code'].' - '.$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>
										<?php } ?>
									  </select>
									</td>
									
									<!--Region Pull Down List for Filter Option-->
									<td width="20%" align="left" class="bold_text">Region : 
									  <select name="region" id="region">
										<option value="">-- Select Region --</option>
										<?php foreach($this->rbmDetails as $rbmDetail){
											$select = '';
											if($this->Filterdata['token5']==Class_Encryption::encode($rbmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($rbmDetail['user_id'])?>" <?=$select?>><?=$rbmDetail['employee_code'].' - '.$rbmDetail['first_name'].' '.$rbmDetail['last_name']?></option>
										<?php } ?>
									  </select>
									</td>
									
									<!--Area Pull Down List for Filter Option-->
									<td width="20%" align="left" class="bold_text">Area : 
									  <select name="area" id="area">
										<option value="">-- Select Area --</option>
										<?php foreach($this->abmDetails as $abmDetail){
											$select = '';
											if($this->Filterdata['token4']==Class_Encryption::encode($abmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($abmDetail['user_id'])?>" <?=$select?>><?=$abmDetail['employee_code'].' - '.$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>
										<?php } ?>
									  </select>
									</td>
									
								    <!--City Pull Down List for Filter Option-->
									<td width="18%" align="left" class="bold_text">City : 
									  <select name="city" id="city">
										<option value="">-- Select City --</option>
										<?php foreach($this->abmDetails as $abmDetail){
											$select = '';
											if($this->Filterdata['token4']==Class_Encryption::encode($abmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($abmDetail['user_id'])?>" <?=$select?>><?=$abmDetail['employee_code'].' - '.$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>
										<?php } ?>
									  </select>
									</td>
									
									<!--Street Pull Down List for Filter Option-->
									<td width="19%" align="left" class="bold_text">Street : 
									  <select name="street" id="street">
										<option value="">-- Select Street --</option>
										<?php foreach($this->abmDetails as $abmDetail){
											$select = '';
											if($this->Filterdata['token4']==Class_Encryption::encode($abmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($abmDetail['user_id'])?>" <?=$select?>><?=$abmDetail['employee_code'].' - '.$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>
										<?php } ?>
									  </select>
									</td>
							      </tr>
								  
								  <tr class="odd">
									<!--Headquarter Pull Down List for Filter Option-->
									<?php if($_SESSION['AdminDesignation']<8) {?>
									<td align="left" class="bold_text">Headquarter :
									  <select name="token2" id="hqlist">
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
									</td>
									<?php } ?>
									
									<!--Doctor Pull Down List for Filter Option-->
									<td align="left" class="bold_text">Doctor :
									  <select name="token1">
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
									</td>
									
									<!--From Date for Filter Option-->
									<td class="bold_text" align="left">
										From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->Filterdata['from_date']?>" />
									</td>
									<td class="bold_text" align="left">
										To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->Filterdata['to_date']?>" />
									</td>
									<td align="center" class="bold_text"><input type="submit" name="filter" class="inputbutton" value="Search" /></td>			
								  </tr>
								  
								  <tr class="even">
								    <td align="left" colspan="5">
									  <img src="<?=Bootstrap::$baseUrl;?>public/admin_images/arrow_dwn.gif" alt="" align="absmiddle" border="0" />
									  <a href="javascript:void(1);" onclick="javascript: markAllSelectedRows('listForm'); return false;" class="new_link">Check All</a> / 
									  <a href="javascript:void(1);" onclick="javascript: unMarkSelectedRows('listForm'); return false;" class="new_link">Uncheck All</a>
									</td>
								  </tr>
								</table>
								</form>
							  </td>
							</tr>
                            <!-- END : Search Form -->
							
                                <tr>
								<td>
								  <table width="95%" border="0" cellspacing="1" cellpadding="2" class="header_bordercolor">
									<tr>
										<th>Graphical Report</th>
									</tr>
									
									<tr>
										<td width="100%"><img src="<?php echo $this->url(array('controller'=>'Graphreport','action'=>'graph','token'=>$graphData),'default',true)?>" /></td>
									</tr>
								  </table>
							   </td>
							  </tr>
                            </thead>
                        </table>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
			
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
		
		$("#zbmlist").change(function() {
			if(this.value != '')
			{
				$.ajax({
					type: "POST",
					url: '<?=Bootstrap::$baseUrl?>Graphreport/getlist/',
					data: 'token='+this.value,
		
					success: function(datas) { //alert(datas);return false;
						$.each(datas,function(optvalue,opttext) {
							if(optvalue=='levelrbm') {
								$('#rbmlist').children().remove();
								$("#rbmlist").append('<option value=0>-- Select RBM --</option>');
								$.each(opttext, function(optvalue,opttext){
									var opt = $('<option />');
									opt.val(optvalue);
									opt.text(opttext);						
									$('#rbmlist').append(opt);
								});
							}
							
							if(optvalue=='levelabm') {
								$('#abmlist').children().remove();
								$("#abmlist").append('<option value=0>-- Select ABM --</option>');
								$.each(opttext, function(optvalue,opttext){
									var opt = $('<option />');
									opt.val(optvalue);
									opt.text(opttext);						
									$('#abmlist').append(opt);
								});
							}
							
							if(optvalue=='levelbe') {
								$('#belist').children().remove();
								$("#belist").append('<option value=0>-- Select BE --</option>');
								$.each(opttext, function(optvalue,opttext){
									var opt = $('<option />');
									opt.val(optvalue);
									opt.text(opttext);						
									$('#belist').append(opt);
								});
							}
						});
					}
				});
			}
		});
	});
</script>	