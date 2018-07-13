	 <div class="grid_12">  

	            <!-- Example table -->

                <div class="module">  

                	<h2><span>Doctor Visit Detail</span></h2>

                    

                    <div class="module-table-body"> 

						<!-- START : Search Form -->

						<?php ?><form name="listFrom" id="listForm" action="" method="get" >

							<table width="96%" border="0" cellspacing="1" cellpadding="2">

							  <tr class="odd">

								<td colspan="5">

									<b>Reportee Name : </b><?=$this->reporteedetail['Emp']?> &nbsp;&nbsp;

									<b>Designation : </b><?=$this->reporteedetail['designation_name']?> &nbsp;&nbsp;

									<b>HQ : </b><?=$this->reporteedetail['headquater_name']?>

								</td>

							</tr>

							

							  <tr height="26">

								<th align="center" colspan="5">Search Form</th>

							  </tr>

							  

							  <tr class="even">

								<!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']!=5) { ?>

								<td align="left" class="bold_text">ZBM : 

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

								  </select>								  

								</td>

								<?php } ?>

								

								<!--Regional Business Manager (RBM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']!=6) { ?>

								<td align="left" class="bold_text">RBM : 

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

								  </select>

								</td>

								<?php } ?>

								

								<!--Area Business Manager (ABM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']!=7) { ?>

								<td align="left" class="bold_text">ABM : 

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

								  </select>

								</td>

								<?php } ?>

								

								<!--Business Executive (BE) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']!=8) { ?>

								<td align="left" class="bold_text">BE : 

								  <select name="token3">

									<option value="">-- Select BE --</option>

									<?php foreach($this->beDetails as $key=>$userName){

										$select = '';

										if($this->Filterdata['token3']==Class_Encryption::encode($key)){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($key)?>" <?=$select?>><?=$userName?></option>

									<?php } ?>

								  </select>

								</td>

								<?php } ?>

                                

                                <!--Patch Pull Down List for Filter Option-->

								<td align="left" class="bold_text">Patch :

								  <select name="token2">

									<option value="">-- Select Patch --</option>

									<?php foreach($this->patches as $key=>$patch){

										$ptok  = Class_Encryption::encode($key); //Class_Encryption::encode($patch['patch_id']);

										$pname = $patch; //$patch['patch_name'];

										$select = ($this->Filterdata['token2']==$ptok) ? 'selected="selected"' : '';

									?>

									<option value="<?=$ptok?>" <?=$select?>><?=$pname?></option>

									<?php } ?>

								  </select>

								</td>

							  </tr>

							  

							  <tr class="odd">

								<!--Doctor Pull Down List for Filter Option-->

								<td width="20%" align="left" class="bold_text">Doctor : 

								  <select name="token1">

									<option value="">-- Select Doctor --</option>

									<?php foreach($this->doctors as $key=>$doctor){

										$dtok  = Class_Encryption::encode($key); //Class_Encryption::encode($doctor['doctor_id']);

										$dname = $doctor; //$doctor['doctor_name'];

										$select = ($this->Filterdata['token1']==$dtok) ? 'selected="selected"' : '';

									?>

									<option value="<?=$dtok?>" <?=$select?>><?=$dname?></option>

									<?php } ?>

								  </select>

								</td>

                                

                                <!--Doctor Type Pull Down List for Filter Option-->

								<td width="20%" align="left" class="bold_text">Doctor Type :

								  <select name="dtype">

									<option value="1" <?=($this->Filterdata['dtype']=='1') ? 'selected="selected"':''?>>Listed Doctor</option>

									<option value="0" <?=($this->Filterdata['dtype']=='0') ? 'selected="selected"':''?>>Non Listed Doctor</option>

								  </select>

								</td>

								

								<!--Activity Pull Down List for Filter Option-->

								<td width="20%" align="left" class="bold_text">Activity :

								  <select name="atoken">

									<option value="">-- Select Activity --</option>

									<?php foreach($this->activities as $key=>$activity){

										$atok  = Class_Encryption::encode($key); //Class_Encryption::encode($activity['patch_id']);

										$aname = $activity; //$activity['patch_name'];

										$select = ($this->Filterdata['atoken']==$atok) ? 'selected="selected"' : '';

									?>

									<option value="<?=$atok?>" <?=$select?>><?=$aname?></option>

									<?php } ?>

								  </select>

								</td>

								<td width="20%" align="left" class="bold_text">Products :                                	

                                  <select name="ptoken">

									<option value="">-- Select Product --</option>

									<?php foreach($this->products as $key=>$product){

										$prtok  = Class_Encryption::encode($key); //Class_Encryption::encode($product['product_id']);

										$prname = $product; //$product['product_name'];

										$select = ($this->Filterdata['ptoken']==$prtok) ? 'selected="selected"' : '';

									?>

									<option value="<?=$prtok?>" <?=$select?>><?=$prname?></option>

									<?php } ?>

								  </select>

                                </td>

                                <td width="20%" align="center"><input type="submit" name="filter" class="submit-green" value="Search" /> &nbsp;<input type="submit" name="export" class="submit-green" value="Export" /></td>

							  </tr>

							</table>

							</form><?php ?>

						<!-- END : Search Form -->

							

							<table id="myTable" class="tablesorter">

							<thead>							

							<tr>

							<!--<th>#</th>-->
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit Date','EE.call_date')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Doctor Name','DD.doctor_name')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Patch','PC.patch_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('City','CT.city_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('HQ','HT.headquater_name')?></th>

							<?php if($_SESSION['AdminDesignation']!=5){?>

								<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit with ZBM','ZBM.first_name')?></th>

							<?php } if($_SESSION['AdminDesignation']!=6){?>

								<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit with RBM','RBM.first_name')?></th>

							<?php } if($_SESSION['AdminDesignation']!=7){?>

								<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit with ABM','ABM.first_name')?></th>

							<?php } if($_SESSION['AdminDesignation']!=8){?>

								<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit with BE','BE.first_name')?></th>

							<?php } ?>

							<th width="23%">Product : Unit</th>

							

							<th align="center" width="7%"><?=CommonFunction::OrderBy('Visit Time','EE.call_time')?></th>
							<th align="center" width="7%"><?=CommonFunction::OrderBy('Date Added','EE.date_added')?></th>
							</tr>

						

							<?php 

							if(count($this->detailreport['Records'])>0){

								foreach($this->detailreport['Records'] as $key=>$report) {

								  $class = ($key%2==0)?'even':'odd';

							?>

						 <tr class="<?=$class?>">

						  <!--<td><input type="checkbox" name="emptoken[]" value="<?=$report['employee_code']?>" /></td>-->
						<td><?=$report['call_date']?></td>
						 <td><?=$report['doctor_name']?></td>

						 <td><?=$report['patch_name']?></td>
						  <td><?=$report['city_name']?></td>
						 <td><?=$report['headquater_name']?></td>
						 <?php if($_SESSION['AdminDesignation']!=5){?>

						 	<td align="center"><?=(!empty($report['zbmvisit'])) ? $report['zbmvisit'] : '--'?></td>

						 <?php } ?>

						 <?php if($_SESSION['AdminDesignation']!=6){?>

						 	<td align="center"><?=(!empty($report['rbmvisit'])) ? $report['rbmvisit'] : '--'?></td>

						 <?php } ?>

						 <?php if($_SESSION['AdminDesignation']!=7){?>

						 	<td align="center"><?=(!empty($report['abmvisit'])) ? $report['abmvisit'] : '--'?></td>

						 <?php } ?>

						 <?php if($_SESSION['AdminDesignation']!=8){?>

						 	<td align="center"><?=(!empty($report['bevisit'])) ? $report['bevisit'] : '--'?></td>

						 <?php } ?>

						 <td><?=$this->ObjModel->productPromoted($report['visit_id']);?></td>

						 

						 <td><?=$report['call_time']?></td>
						 <td><?=$report['date_added']?></td>
						 </tr>

						 <?php } }  else{ ?>

						<tr>

						<td align="center" colspan="8">No Record Found!...</td>

						</tr>

						<?php }?>

						 

						 <!-- Paging Style : 1 -->

						<tr>

						<th colspan="13" style="text-align:left"><?=CommonFunction::PageCounter($this->detailreport['Total'], $this->detailreport['Offset'], $this->detailreport['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>

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

	});

	</script>

