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

									  <select name="token6" onchange="getchild(this.value,'rbm_id','6')">

										<option value="">-- Select ZBM --</option>

										<?php foreach($this->zbmDetails as $zbmDetail){

											$select = '';

											if($this->Filterdata['token6']==Class_Encryption::encode($zbmDetail['user_id'])){

							   					$select = 'selected="selected"';

											}

										?>

										<option value="<?=Class_Encryption::encode($zbmDetail['user_id'])?>" <?=$select?>><?=$zbmDetail['employee_code'].' - '.$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>

										<?php } ?>

									  </select> &nbsp;&nbsp;&nbsp;&nbsp;

									  <?php } ?>

									  

									  	<!--Regional Business Manager (RBM) Pull Down List for Filter Option $rbmDetail['employee_code'].' - '.-->

										<?php if($_SESSION['AdminDesignation']<6) { ?>RBM : 

									  <select id="rbm_id" name="token5" onchange="getchild(this.value,'abm_id','7')">

										<option value="">-- Select RBM --</option>

										<?php foreach($this->rbmDetails as $rbmDetail){

											$select = '';

											if($this->Filterdata['token5']==Class_Encryption::encode($rbmDetail['user_id'])){

							   					$select = 'selected="selected"';

											}

										?>

										<option value="<?=Class_Encryption::encode($rbmDetail['user_id'])?>" <?=$select?>><?=$rbmDetail['employee_code'].' - '.$rbmDetail['first_name'].' '.$rbmDetail['last_name']?></option>

										<?php } ?>

									  </select> &nbsp;&nbsp;&nbsp;&nbsp;

									  <?php } ?>

									  	

										<!--Area Business Manager (ABM) Pull Down List for Filter Option $abmDetail['employee_code'].' - '.-->

										<?php if($_SESSION['AdminDesignation']<7) { ?>ABM : 

									  <select id="abm_id" name="token4" onchange="getchild(this.value,'be_id','8')">

										<option value="">-- Select ABM --</option>

										<?php foreach($this->abmDetails as $abmDetail){

											$select = '';

											if($this->Filterdata['token4']==Class_Encryption::encode($abmDetail['user_id'])){

							   					$select = 'selected="selected"';

											}

										?>

										<option value="<?=Class_Encryption::encode($abmDetail['user_id'])?>" <?=$select?>><?=$abmDetail['employee_code'].' - '.$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>

										<?php } ?>

									  </select> &nbsp;&nbsp;&nbsp;&nbsp;

									  <?php } ?>

									  

									  	<!--Business Executive (BE) Pull Down List for Filter Option $beDetail['employee_code'].' - '.-->

										<?php if($_SESSION['AdminDesignation']<8) { ?>BE : 

									  <select id="be_id" name="token3">

										<option value="">-- Select BE --</option>

										<?php foreach($this->beDetails as $beDetail){

											$select = '';

											if($this->Filterdata['token3']==Class_Encryption::encode($beDetail['user_id'])){

							   					$select = 'selected="selected"';

											}

										?>

										<option value="<?=Class_Encryption::encode($beDetail['user_id'])?>" <?=$select?>><?=$beDetail['employee_code'].' - '.$beDetail['first_name'].' '.$beDetail['last_name']?></option>

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

									<!--<td align="center">

									  <a href="<?=$this->url(array('controller'=>'Reports','action'=>'graphicalview'),'default',true)?>" class="new_link" target="_blank">Graphical Report</a>

									  <a href="<?=$this->url(array('controller'=>'Graphreport'),'default',true)?>" class="new_link" target="_blank">Graphical Report</a>

									  </td>-->

								  </tr>

								</table>

							  </td>

							</tr>

							

							   <tr>

									<th style="width:3%; text-align:center">#</td>

									<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Doctor Name','DT.doctor_name')?></th>

									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('CRM Expense Cost','AT.expense_cost')?></th>

									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Expected Business','AT.total_value')?></th>

									<th style="width:8%; text-align:center">ROI Amount</th>

									<th style="width:8%; text-align:center">Action</th>

                               </tr>

                            </thead>

							

                            <tbody>

                             <?php //echo "<pre>";print_r($this->rois);die;

							 if(count($this->rois['Records'])>0){

								$roiMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));

								foreach($this->rois['Records'] as $key=>$roi) {

								  $class = ($key%2==0)?'even':'odd';

								?>

								<tr class="<?php echo  $class;?>">

								  <td align="center"><input type="checkbox" name="token[]" value="<?=Class_Encryption::encode($roi['doctor_id'])?>" /></td>

								  <td align="center"><?=$roi['doctor_name']?></td>

								  <td align="center"><?=number_format($roi['expenseCost'],2)?></td>

								  <td align="center"><?=number_format($roi['crmAmount'],2)?></td>

								  <td align="center"><?=number_format($roi['roiAmount'],2)?></td>

								  <td align="center">

								  	<a href="<?=$this->url(array('controller'=>'Reports','action'=>'detailreport','token'=>Class_Encryption::encode($roi['doctor_id'])),'default',true)?>"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail Report" /></a>

                                  </td>

								</tr>

								<?php } } else{ ?>

								<tr>

								  <td align="center" colspan="6">No ROI found !!...</td>

								</tr>

								<?php }?>

                                

                                <!-- Paging Style : 1 -->

								<tr>

								<th colspan="13" style="text-align:left"><?=CommonFunction::PageCounter($this->rois['Total'], $this->rois['Offset'], $this->rois['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>

								</tr>

                            </tbody>

                        </table>

                        </form>

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