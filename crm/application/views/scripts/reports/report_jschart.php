 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>ROI Report Section</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							 <!--Search and Filter Form-->
							 <tr>
							  <td colspan="7">
								<table width="96%" border="0" cellspacing="1" cellpadding="2">
								  <tr height="26">
									<th align="center" colspan="5">Search Form</th>
								  </tr>
								  
								  <tr class="even">
								    <?php $countries = array(); $forwarders  = array();?>
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
									
									<!--Headquarter Pull Down List for Filter Option-->
									<td align="left" class="bold_text">
									  <?php if($_SESSION['AdminDesignation']<8) {?>Headquarter :
									  <select name="token2">
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
									</td>
									
									<!--From Date for Filter Option-->
									<td width="20%" class="bold_text" align="left">
										From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->Filterdata['from_date']?>" />
									</td>
									<td width="20%" class="bold_text" align="left">
										To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->Filterdata['to_date']?>" />
									</td>
									<td width="20%" class="bold_text" align="left"></td>
							      </tr>
								  
								  <tr class="odd">
								    <!--Business Executive (BE) Pull Down List for Filter Option-->
									<td align="left" class="bold_text">
									  <?php if($_SESSION['AdminDesignation']<8) { ?>BE : 
									  <select name="token3">
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
									</td>
									
									<!--Area Business Manager (ABM) Pull Down List for Filter Option-->
									<td align="left" class="bold_text">
									  <?php if($_SESSION['AdminDesignation']<7) { ?>ABM : 
									  <select name="token4">
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
									</td>
									
									<!--Regional Business Manager (RBM) Pull Down List for Filter Option-->
									<td align="left" class="bold_text">
									  <?php if($_SESSION['AdminDesignation']<6) { ?>RBM : 
									  <select name="token5">
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
									</td>
									
									<!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->
									<td align="left" class="bold_text">
									<?php if($_SESSION['AdminDesignation']<5) { ?>ZBM : 
									  <select name="token6">
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
							  </td>
							</tr>
							
							<!--JSChart-->
							<tr><td colspan="6"><div id="graph">Loading...</div></td></tr>
							<tr><td colspan="6"><div id="graph1">Loading...</div></td></tr>
							   
							   <tr>
									<th style="width:3%; text-align:center">#</td>
									<th style="width:10%; text-align:center">Doctor Name</th>
									<th style="width:8%; text-align:center">CRM Expense Cost</th>
									<th style="width:8%; text-align:center">CRM Amount</th>
									<th style="width:8%; text-align:center">ROI Amount</th>
									<th style="width:8%; text-align:center">ROI Month</th>
                               </tr>
                            </thead>
							
                            <tbody>
                             <?php //echo "<pre>";print_r($this->rois);die;
							 if(count($this->rois)>0){
								$roiMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));
								foreach($this->rois as $i=>$roi) {
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								  <td align="center"><input type="checkbox" name="token[]" value="<?=Class_Encryption::encode($roi['doctor_id'])?>" /></td>
								  <td align="center"><?=$roi['doctor_name']?></td>
								  <td align="center"><?=number_format($roi['expenseCost'],2)?></td>
								  <td align="center"><?=number_format($roi['crmAmount'],2)?></td>
								  <td align="center"><?=number_format($roi['roiAmount'],2)?></td>
								  <td align="center"><?=date("M Y",mktime(0,0,0,date('m')-1,date('d'),date('Y')))?></td>								  
								</tr>
								<?php } } else{ ?>
								<tr>
								  <td align="center" colspan="6">No ROI found !!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        </form>
						
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
							</div>
                        <div style="clear: both"></div>
                     </div>
                </div>
			</div>	
	
	<script type="text/javascript" src="<?=Bootstrap::$baseUrl?>javascript/javascript/jschart/jscharts.js"></script><!-- Graph and Chart JS-->
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
	
	var myChart = new JSChart('graph', 'pie');
	myChart.setDataArray([['A', 40],['B', 16],['C', 20],['D', 10],['E', 10],['F', 4]]);
	myChart.colorize(['#99CDFB','#3366FB','#0000FA','#F8CC00','#F89900','#F76600']);
	myChart.setSize(600, 300);
	myChart.setTitle('Phd Reference Chart');
	myChart.setTitleFontFamily('Times New Roman');
	myChart.setTitleFontSize(14);
	myChart.setTitleColor('#0F0F0F');
	myChart.setPieRadius(95);
	myChart.setPieValuesColor('#FFFFFF');
	myChart.setPieValuesFontSize(9);
	myChart.setPiePosition(180, 165);
	myChart.setShowXValues(false);
	myChart.setLegend('#99CDFB', 'Papers where authors found');
	myChart.setLegend('#3366FB', 'Papers which cite from other articles');
	myChart.setLegend('#0000FA', 'Papers which cite from news');
	myChart.setLegend('#F8CC00', 'Papers which lack crucial');
	myChart.setLegend('#F89900', 'Papers with different conclusion');
	myChart.setLegend('#F76600', 'Papers with useful information');
	myChart.setLegendShow(true);
	myChart.setLegendFontFamily('Times New Roman');
	myChart.setLegendFontSize(10);
	myChart.setLegendPosition(350, 120);
	myChart.setPieAngle(30);
	myChart.set3D(true);
	myChart.draw();
	
	var myData = new Array(['Asia', 437, 520], ['Europe', 322, 390], ['North America', 233, 286], ['Latin America', 110, 162], ['Africa', 34, 49], ['Middle East', 20, 31], ['Aus/Oceania', 19, 22]);
	var myChart1 = new JSChart('graph1', 'bar');
	myChart1.setDataArray(myData);
	myChart1.setTitle('Internet usage by World Region (millions of users)');
	myChart1.setTitleColor('#8E8E8E');
	myChart1.setAxisNameX('');
	myChart1.setAxisNameY('');
	myChart1.setAxisNameFontSize(16);
	myChart1.setAxisNameColor('#999');
	myChart1.setAxisValuesAngle(30);
	myChart1.setAxisValuesColor('#777');
	myChart1.setAxisColor('#B5B5B5');
	myChart1.setAxisWidth(1);
	myChart1.setBarValuesColor('#2F6D99');
	myChart1.setAxisPaddingTop(60);
	myChart1.setAxisPaddingBottom(60);
	myChart1.setAxisPaddingLeft(45);
	myChart1.setTitleFontSize(11);
	myChart1.setBarColor('#2D6B96', 1);
	myChart1.setBarColor('#9CCEF0', 2);
	myChart1.setBarBorderWidth(0);
	myChart1.setBarSpacingRatio(50);
	myChart1.setBarOpacity(0.9);
	myChart1.setFlagRadius(6);
	myChart1.setTooltip(['North America', 'Click me', 1], callback);
	myChart1.setTooltipPosition('nw');
	myChart1.setTooltipOffset(3);
	myChart1.setLegendShow(true);
	myChart1.setLegendPosition('right top');
	myChart1.setLegendForBar(1, '2005');
	myChart1.setLegendForBar(2, '2010');
	myChart1.setSize(616, 321);
	myChart1.setGridColor('#C6C6C6');
	myChart1.draw();
	
	function callback() {
		alert('User click');
	}	
</script>