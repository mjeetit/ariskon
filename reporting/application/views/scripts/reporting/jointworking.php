	 <div class="grid_12">  

	            <!-- Example table -->

                <div class="module">  

                	<h2><span>Joint Working Report</span></h2>

                    

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

								  <select name="token6" id="zbm_id" onchange="getchild(this.value,'rbm_id','6')">

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

								  <select name="token5" id="rbm_id" onchange="getchild(this.value,'abm_id','7')">

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

								  <select name="token4" id="abm_id">

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

								<!--<td align="left" class="bold_text">BE : 

								  <select name="token3">

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

								</td>-->

								<?php } ?>
								<td width="20%" class="bold_text" align="left">

									From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->filter['from_date']?>" />

								</td>

								<td width="20%" class="bold_text" align="left">

									To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->filter['to_date']?>" />

								</td>
								
                                
							  </tr>

							  

							  <tr class="odd">
							  <td width="20%" class="bold_text" align="left">Region :
								<select name="region">
								<option value="">-Select Region-</option>
								<?php foreach($this->regions as $region){?>
								<option value="<?php echo $region['region_id'];?>"><?php echo $region['region_name']?></option>
								<?php } ?>
								</select></td>

								<td colspan="4" align="center">

									<input type="submit" name="filter" class="submit-green" value="Search" /> &nbsp;

									<input type="submit" name="exportVisit" class="submit-green" value="Export in Excel" title="Export Data in Excel" />
									&nbsp;
<input onsubmit="jointwork(this.id);" type="submit" id="jointworksummury"name="jointworksummury" class="submit-green" value="Joint Work Summury" title="Export Data in Excel" />
								</td>

							  </tr>

							</table>

						</form>

						<!-- END : Search Form -->

							

							<table id="myTable" class="tablesorter">

							<thead>							

							<tr>
							<th>&nbsp;</th>
							<th colspan="3">ABM</th>
							<th colspan="3">RBM</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
							</tr>
							<tr>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Date','EE.call_date')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Name of ABM','UD.first_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Work With BE','UD1.first_name')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Work With Manager','EE.work_with_ho')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Name of RBM','UD.first_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Work With BE','UD1.first_name')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Work With Manager','EE.work_with_ho')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Name of Doctor','CD.doctor_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Patch','PT.patch_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Town','CT.city_name')?></th>




							</tr>

						

							<?php 

							if(count($this->vistdetail['Records'])>0){

								foreach($this->vistdetail['Records'] as $key=>$report) {

								  $class = ($key%2==0)?'even':'odd';


							?>

						 <tr class="<?=$class?>">

						 <td><?=$report['call_date']?></td>

						 <td><?=$report['abm_name']?></td>
						<td><?=($report['designation_id']==7)?$report['be_name']:'';?></td> 
						 <td><?=($report['designation_id']==7 && $report['work_with_ho']>0)?'Yes':'';?></td>

						 <td><?=$report['rbm_name']?></td> 

						 <td><?=($report['designation_id']==6)?$report['be_name']:'';?></td> 
						 
						 <td><?=($report['designation_id']==6 && $report['work_with_ho']>0)?'Yes':'';?></td>

						 <td><?=$report['doctor_name']?></td>

						 <td><?=$report['patch_name']?></td>

						 <td><?=$report['city_name']?></td>

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
     if(zbm!=''){
	 	getchild(zbm,'rbm_id','6',rbm);
	 }
	 if(rbm!=''){
	 	getchild(rbm,'abm_id','7',abm);
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

