 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Detail ROI Report</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							 <!--Search and Filter Form-->
							 <tr>
							  <td colspan="8">
								<table width="96%" border="0" cellspacing="1" cellpadding="2">
								  <tr height="26">
									<th align="center" colspan="5">Search Form</th>
								  </tr>
								  
								  <tr class="even">
									<!--Doctor Pull Down List for Filter Option-->
									<td width="20%" align="left" class="bold_text">Doctor :
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
									
									<!--Headquarter Pull Down List for Filter Option $headquarter['location_code'].' - '.-->
									<td width="24%" align="left" class="bold_text">
									  <?php if($_SESSION['AdminDesignation']<8) {?>Headquarter :
									  <select name="token2">
										<option value="">-- Select Headquarter --</option>
										<?php foreach($this->headquarters as $headquarter){
											$select = '';
											if($this->Filterdata['token2']==Class_Encryption::encode($headquarter['headquater_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($headquarter['headquater_id'])?>" <?=$select?>><?=$headquarter['headquater_name']?></option>
										<?php } ?>
									  </select>
									  <?php } ?>
									</td>
                                    
                                    <!--CRM Expense Type Pull Down List for Filter Option-->
									<td width="24%" align="left" class="bold_text">
									  <?php if($_SESSION['AdminDesignation']<8) {?>CRM Request :
									  <select name="activitytoken">
										<option value="">-- Select Request --</option>
										<?php foreach($this->expenses as $expense){
											$select = '';
											if($this->Filterdata['activitytoken']==Class_Encryption::encode($expense['expense_type'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($expense['expense_type'])?>" <?=$select?>><?=$expense['type_desc']?></option>
										<?php } ?>
									  </select>
									  <?php } ?>
									</td>
									
									<!--From Date for Filter Option-->
									<td width="18%" class="bold_text" align="left">
										From : <input type="text" name="from_date" id="from_date" size="10" value="<?=$this->Filterdata['from_date']?>" />
									</td>
									<td width="18%" class="bold_text" align="left">
										To : <input type="text" name="to_date" id="to_date" size="10" value="<?=$this->Filterdata['to_date']?>" />
									</td>									
							      </tr>
								  
								  <tr class="odd">
								  	<td align="left" colspan="5" class="bold_text">
										<!--Zonal Business Manager (ZBM) Pull Down List for Filter Option $zbmDetail['employee_code'].' - '.-->
										<?php if($_SESSION['AdminDesignation']<5) { ?>ZBM : 
									  <select name="token6">
										<option value="">-- Select ZBM --</option>
										<?php foreach($this->zbmDetails as $zbmDetail){
											$select = '';
											if($this->Filterdata['token6']==Class_Encryption::encode($zbmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($zbmDetail['user_id'])?>" <?=$select?>><?=$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>
										<?php } ?>
									  </select> &nbsp;&nbsp;&nbsp;&nbsp;
									  <?php } ?>
									  
									  	<!--Regional Business Manager (RBM) Pull Down List for Filter Option $rbmDetail['employee_code'].' - '.-->
										<?php if($_SESSION['AdminDesignation']<6) { ?>RBM : 
									  <select name="token5">
										<option value="">-- Select RBM --</option>
										<?php foreach($this->rbmDetails as $rbmDetail){
											$select = '';
											if($this->Filterdata['token5']==Class_Encryption::encode($rbmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($rbmDetail['user_id'])?>" <?=$select?>><?=$rbmDetail['first_name'].' '.$rbmDetail['last_name']?></option>
										<?php } ?>
									  </select> &nbsp;&nbsp;&nbsp;&nbsp;
									  <?php } ?>
									  	
										<!--Area Business Manager (ABM) Pull Down List for Filter Option $abmDetail['employee_code'].' - '.-->
										<?php if($_SESSION['AdminDesignation']<7) { ?>ABM : 
									  <select name="token4">
										<option value="">-- Select ABM --</option>
										<?php foreach($this->abmDetails as $abmDetail){
											$select = '';
											if($this->Filterdata['token4']==Class_Encryption::encode($abmDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($abmDetail['user_id'])?>" <?=$select?>><?=$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>
										<?php } ?>
									  </select> &nbsp;&nbsp;&nbsp;&nbsp;
									  <?php } ?>
									  
									  	<!--Business Executive (BE) Pull Down List for Filter Option $beDetail['employee_code'].' - '.-->
										<?php if($_SESSION['AdminDesignation']<8) { ?>BE : 
									  <select name="token3">
										<option value="">-- Select BE --</option>
										<?php foreach($this->beDetails as $beDetail){
											$select = '';
											if($this->Filterdata['token3']==Class_Encryption::encode($beDetail['user_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($beDetail['user_id'])?>" <?=$select?>><?=$beDetail['first_name'].' '.$beDetail['last_name']?></option>
										<?php } ?>
									  </select> &nbsp;&nbsp;&nbsp;&nbsp;
									  <?php } ?>
									  
									  <input type="submit" name="filter" class="submit-green" value="Search" /> &nbsp;&nbsp;&nbsp;&nbsp; 
									  <input type="submit" name="Export" class="submit-green" value="Export in Excel" />
									</td>
								  </tr>
								  
								  <tr class="even">
								    <td align="left" colspan="5">
									  <img src="<?=Bootstrap::$baseUrl;?>public/admin_images/arrow_dwn.gif" alt="" align="absmiddle" border="0" />
									  <a href="javascript:void(1);" onclick="javascript: markAllSelectedRows('listForm'); return false;" class="new_link">Check All</a> / 
									  <a href="javascript:void(1);" onclick="javascript: unMarkSelectedRows('listForm'); return false;" class="new_link">Uncheck All</a>
									</td>
								  </tr>
								</table>
							  </td>
							</tr>
							
							   <tr>
									<?php $totalRowData = count($this->detailreport);
									if($totalRowData>0) { foreach($this->detailreport[0] as $headerKey=>$rowHeader) { ?>
                                    <th style="width:10%; text-align:center"><?=$headerKey?></th>
                                    <?php } } ?>
                               </tr>
                            </thead>
							
                            <tbody>
                             <?php
							 	// Report Sheet Row Data
								if($totalRowData>0){
									$zoneArray   = array();
									$regionArray = array();
									$hqArray     = array();
									$i=0;
									foreach($this->detailreport as $index=>$rowData)
									{
										$class = ($i%2==0)?'even':'odd';
										echo '<tr class="'.$class.'">';
										$j=0;
										foreach($rowData as $key=>$rowValue)
										{
											$columnData = ($j>3) ? number_format($rowValue,2) : utf8_decode($rowValue);
											$columnData = ($key=='Zone')   ? (!in_array($rowValue,array_filter($zoneArray))   ? $columnData : '') : $columnData;
											$columnData = ($key=='Region') ? (!in_array($rowValue,array_filter($regionArray)) ? $columnData : '') : $columnData;
											$columnData = ($key=='HQ')     ? (!in_array($rowValue,array_filter($hqArray))     ? $columnData : '') : $columnData;
											$columnData = ($totalRowData==($index+1)) ? ($j>3 ? $columnData : '') : $columnData;
											$columnData = ($totalRowData==($index+1)) ? ($j==0 ? 'Grand Total' : $columnData) : $columnData;
											$tdClass = ($i==($totalRowData-1)) ? 'class="bold_text"' : '';
											
											echo '<td align="center"'.$tdClass.'>'.$columnData.'</td>';
											$j++;
										}
										echo '</tr>';
										
										$zoneArray[]   = (!in_array($rowData['Zone'],$zoneArray))     ? $rowData['Zone']   : '';
										$regionArray[] = (!in_array($rowData['Region'],$regionArray)) ? $rowData['Region'] : '';
										$hqArray[]     = (!in_array($rowData['HQ'],$hqArray))         ? $rowData['HQ']     : '';
										$i++;
									}
									} else{ ?>
								<tr>
								  <td align="center" colspan="6">No ROI found !!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        </form>
						
                        <!--<div class="pager" id="chartgraph" style="display:none;">gfdgdfgd</div>-->
						
						<div class="pager" id="pager">
                            <form action="">
                                <div>
                                <a href="" class="button"><span><img src="<?php echo IMAGE_LINK;?>/arrow-180-small.gif" tppabs="http://www.xooom.pl/work/magicadmin/images/arrow-180-small.gif" height="9" width="12" alt="Previous" /> Prev</span></a>
                                <input type="text" class="pagedisplay input-short align-center"/>
								 <a href="" class="button"><span>Next <img src="<?php echo IMAGE_LINK;?>/arrow-000-small.gif" height="9" width="12" alt="Next" /></span></a> 
                                <select class="pagesize input-short align-center">
                                    <option value="10" selected="selected">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                </select>
                                </div>
                            </form>
							<!--<input type="checkbox" name="chartngraph" id="chartngraph" /> Graph and Chart
							&nbsp;&nbsp;&nbsp; <a href="<?=$this->url(array('controller'=>'Roi','action'=>'graph'),'default',true)?>" target="_blank">Graph and Chart</a>-->
							</div>
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
		
		$('#chartngraph').click(function() {
			var $this = $(this);
			// $this will contain a reference to the checkbox   
			if ($this.is(':checked')) {
				//alert('Hello');return false;
				$.ajax({
					type : "POST",
					url  : "<?=Bootstrap::$baseUrl?>Roi/graph",
					
					beforeSend: function(){
						//$("#typeTD_"+tdID).html('<img src="<?=ADMIN_IMAGE_LINK?>/loader.gif" align="absmiddle" alt="Loader" border="0" title="Please Wait" class="changeStatus" />');
					},
					success: function(response) {alert(response);return false;
						$("#chartgraph").html(response);
					}
				});
			} else {
				//alert('Hi');return false;
			}
		});
	});	
	</script>