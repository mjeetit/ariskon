 <div class="grid_12">

	            <!-- Example table -->

                <div class="module">

                	<h2><span>Doctor Lists</span></h2>

                    

                    <div class="module-table-body">

                    	<table id="myTable" class="tablesorter">

                        	<thead>

							<!--Search and Filter Form-->

							 <tr>

							  <td colspan="12">

							  	<table width="96%" border="0" cellspacing="1" cellpadding="2">

								  <tr height="26">

									<th width="25%">

                                      <a href="<?=$this->url(array('controller'=>'Doctor','action'=>'add'),'default',true)?>" class="button"><span>Add New Doctor<img src="<?=IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add New User" /></span></a>

                                      <a href="<?=$this->url(array('controller'=>'Doctor','action'=>'patchcode'),'default',true)?>" class="button" target="_blank"><span>Patch Codes</span></a>

                                    </th>

									<th width="35%" align="center">Import Doctor File</th>

									<th width="40%" id="imp1"><a onclick="$.importForm('1')"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/asc.gif" title="Show Import Form" /></th>

									<th width="40%" id="imp0" style="display:none;"><a onclick="$.importForm('0')"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/desc.gif" title="Hide Import Form" />                                    

                                    </th>

								  </tr>

								  

								  <form name="doctorImportHeader" id="doctorImportHeader" action="" method="post" enctype="multipart/form-data">

                                  <tr class="odd" id="impForm" style="display:none;">

								    <td align="left" class="bold_text" colspan="3">

										<!--<a href="<?=$this->url(array('controller'=>'Doctor','action'=>'patchcode'),'default',true)?>" class="button" title="Download All Headquarter"><span>Download All Headquarter</span></a>

                                        <a href="<?=Bootstrap::$baseUrl;?>public/doctor_header.xlsx" title="Download Doctor Header File">Download Header</a>-->

                                        <input type="submit" name="expAllHq" value="Download All Headquarter Header" class="submit-green" />

                                    </td>

								</tr>

                                </form>

                                

                                <form name="doctorImportForm" id="doctorImportForm" action="" method="post" enctype="multipart/form-data">

                                <tr class="odd" id="impForm1" style="display:none;">

								   <!--Headquarter Pull Down List for Filter Option-->

									<?php if($_SESSION['AdminDesignation']<8) {?>

									<td align="left" class="bold_text">

									  Select Headquarter :

									  <select name="hqt" id="hqt" required >

										<option value="">-- Select Headquarter --</option>

										<?php foreach($this->headquarters as $headquarter){

											$select = '';

											if($this->Filterdata['hqt']==Class_Encryption::encode($headquarter['headquater_id'])){

							   					$select = 'selected="selected"';

											}

										?>

										<option value="<?=Class_Encryption::encode($headquarter['headquater_id'])?>" <?=$select?>><?=$headquarter['bunit_name'].'-'.$headquarter['headquater_name']?></option>

										<?php } ?>

									  </select>

									</td>

									<?php } ?>

									<td align="left" class="bold_text">Select Doctor File : <input type="file" name="doctorsheet" id="doctorsheet" /></td>

									<td align="left" class="bold_text">

										<input type="submit" name="uplDr" value="Upload" class="submit-green" />

										<!--<input type="submit" name="bunittocountry" value="Uploade With Header"  class="submit-green" />-->

                                    </td>

								</tr>

                                </form>

								</table>

							  </td>

							</tr>

							

							<!-- START : Search Form -->

							<tr>

							  <td colspan="12">

							  	<form name="listFrom" id="listForm" action="" method="get" >

								<table width="96%" border="0" cellspacing="1" cellpadding="2">

								  <tr height="26">

									<th align="center" colspan="5">Search Form</th>

								  </tr>

								  

								  <tr class="odd">

								    <!--Headquarter Pull Down List for Filter Option-->

									<td width="25%" align="left" class="bold_text">

									  <?php if($_SESSION['AdminDesignation']<8) {?>Headquarter :

									  <select name="token2" onchange="changePatchCode(this.value,'')">

										<option value="">-- Select Headquarter --</option>

										<?php foreach($this->headquarters as $headquarter){

											$select = '';

											if($this->Filterdata['token2']==Class_Encryption::encode($headquarter['headquater_id'])){
												$id=$headquarter['headquater_id'];
							   					$select = 'selected="selected"';
												$id1=$this->Filterdata['token2'];
												$id2=$this->Filterdata['token7'];
													echo "<script>changePatchCode('$id1','$id2')</script>";

											}

										?>

										<option value="<?=Class_Encryption::encode($headquarter['headquater_id'])?>" <?=$select?>><?=$headquarter['bunit_name'].'-'.$headquarter['headquater_name']?></option>

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

									<td width="20%"  class="bold_text">

									  <?php if($_SESSION['AdminDesignation']<8) {?>Patch :

									  <select name="token7" id="patch_id">

										<option value="">-- Select Patch --</option>

										<?php } ?>

									  </select>

									</td>


									<td width="15%" class="bold_text" align="center"><input type="submit" name="exportDoctor" class="submit-green" value="Export in Excel" /></td>

							      </tr>

								  

								  <tr class="even">

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

								

								<!--Doctor Type Pull Down List for Filter Option-->

								<td align="left" class="bold_text">Doctor Type :

								  <select name="dtype">

									<option value="1" <?=($this->Filterdata['dtype']=='1') ? 'selected="selected"':''?>>Listed Doctor</option>

									<option value="0" <?=($this->Filterdata['dtype']=='0') ? 'selected="selected"':''?>>Non Listed Doctor</option>

								  </select>

								</td>

								

								<!--Activity Type Pull Down List for Filter Option-->

								<td class="bold_text" align="left">Activity :

								  <select name="atoken">

									<option value="">-- Select Activity --</option>

									<?php foreach($this->activities as $key=>$activity){

										$atok  = Class_Encryption::encode($activity['activity_id']);

										$aname = $activity['activity_name'];

										$select = ($this->Filterdata['atoken']==$atok) ? 'selected="selected"' : '';

									?>

									<option value="<?=$atok?>" <?=$select?>><?=$aname?></option>

									<?php } ?>

								  </select>

								</td>

								<!--Doctor Type Pull Down List for Filter Option-->

								<td align="left" class="bold_text">Status :

								  <select name="dstat">

									<option value="1" <?=($this->Filterdata['dstat']=='1') ? 'selected="selected"':''?>>Active Doctor</option>

									<option value="0" <?=($this->Filterdata['dstat']=='0') ? 'selected="selected"':''?>>In-active Doctor</option>

								  </select>

								</td>

								<td class="bold_text"></td>

							  </tr>

								  

								  <tr class="odd">

								    <!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->

									<td align="left" class="bold_text">

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

									  </select>

									  <?php } ?>

									</td>
									<!--Regional Business Manager (RBM) Pull Down List for Filter Option-->

									<td align="left" class="bold_text">

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

									  </select>

									  <?php } ?>

									</td>
									<!--Area Business Manager (ABM) Pull Down List for Filter Option-->

									<td align="left" class="bold_text">

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

									  </select>

									  <?php } ?>

									</td>
								    <!--Business Executive (BE) Pull Down List for Filter Option-->

									<td align="left" class="bold_text">

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

									  </select>

									  <?php } ?>

									</td>

									<td align="center" class="bold_text"><input type="submit" name="filter" class="submit-green" value="Search" /></td>		

								  </tr>								  

								</table>

								</form>

							  </td>

							</tr>

                            <!-- END : Search Form -->

								

                                <form name="listForm" id="listForm" action="" method="post">

                                <tr class="even">

								    <td align="left" colspan="5">

									  <img src="<?=Bootstrap::$baseUrl;?>public/admin_images/arrow_dwn.gif" alt="" align="absmiddle" border="0" />

									  Check All / Uncheck All

                                      <!--<a href="javascript:void(1);" onclick="javascript: markAllSelectedRows('listForm'); return false;" class="new_link">Check All</a> / 

									  <a href="javascript:void(1);" onclick="javascript: unMarkSelectedRows('listForm'); return false;" class="new_link">Uncheck All</a>-->&nbsp;&nbsp;

                                      <select name="listaction" id="listaction" class="look" onChange="javascript:if(this.value == 'Delete' && confirm('Selected doctor will be deleted\nAre you sure?')) this.form.submit(); else return false;" >

                                      <option value="" selected="selected">With Selected</option>

                                      <option value="Delete">Delete</option>

                                    </select>

									</td>

								</tr>

                                

                                <tr>

									<th style="width:3%; text-align:center"><input type="checkbox" name="checkHead" id="checkHead" /></td>

									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Doctor Name','DT.doctor_name')?></th>

									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Speciality','DT.speciality')?></th>

									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Qualification','DT.qualification')?></th>

									<th style="width:7%; text-align:center"><?=CommonFunction::OrderBy('Class','DT.class')?></th>

									<th style="width:15%; text-align:center"><?=CommonFunction::OrderBy('Patch','PT.patch_name')?></th>

									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('City','CT.city_name')?></th>

									<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('HQ Name','HQ.headquater_name')?></th>

									<th style="width:5%; text-align:center"><?=CommonFunction::OrderBy('Type','DT.isApproved')?></th>

									<th style="width:5%; text-align:center"><?=CommonFunction::OrderBy('Status','DT.isActive')?></th>

									<th style="width:12%">Action</th>

                                </tr>

                            </thead>

							

                            <tbody>

                             <?php

							 if(count($this->doctorLists['Records'])>0){

								foreach($this->doctorLists['Records'] as $key=>$doctor) {

								  $class = ($key%2==0)?'even':'odd';

								?>

								<tr class="<?=$class;?>">

								<td align="center"><input type="checkbox" name="dtoken[]" class="allchecked" value="<?=Class_Encryption::encode($doctor['doctor_id'])?>" /></td>

						<td align="center"><?=$doctor['doctor_name']?></td>

						<td align="center"><?=$doctor['speciality']?></td>

						<td align="center"><?=$doctor['qualification']?></td>

						<td align="center"><?=$doctor['class']?></td>

						<td align="left"><?=$doctor['patchName']?></td>

						<td align="left"><?=$doctor['cityName']?></td>

						<td align="left"><?=$doctor['hq']?></td>

						<td align="center" id="app_portion<?=$doctor['doctor_id']?>">

						<?php if($doctor['isApproved']=='0'){?>

						   <img src="<?=IMAGE_LINK?>/icon_inactive.gif" align="absmiddle" alt="Non-Listed Doctor" border="0" 

				onclick="changeStatusByportion('<?='crm_doctors'?>','<?=$doctor['doctor_id']?>','isApproved','1','doctor_id','app_portion');" title="Non-Listed Doctor" class="changeStatus" />

						<?php }elseif($doctor['isApproved']=='1'){?>

						<img src="<?=IMAGE_LINK?>/icon_active.gif" align="absmiddle" alt="Listed Doctor" border="0" 

				onclick="changeStatusByportion('<?='crm_doctors'?>','<?=$doctor['doctor_id']?>','isApproved','0','doctor_id','app_portion');" title="Listed Doctor" class="changeStatus" />

						<?php }?>

						</td>

						<td align="center" id="user_portion<?=$doctor['doctor_id']?>">

						<?php if($doctor['isActive']=='1'){?>

						   <img src="<?=IMAGE_LINK?>/icon_active.gif" align="absmiddle" alt="Inactive" border="0" 

				onclick="changeStatusByportion('<?='crm_doctors'?>','<?=$doctor['doctor_id']?>','isActive','0','doctor_id','user_portion');" title="Active" class="changeStatus" />

						<?php }else{?>

						<img src="<?=IMAGE_LINK?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 

				onclick="changeStatusByportion('<?='crm_doctors';?>','<?=$doctor['doctor_id']?>','isActive','1','doctor_id','user_portion');" title="Inactive" class="changeStatus" />

						<?php }?>

						</td>

						<td align="center">

						<a href="<?=$this->url(array('controller'=>'doctor','action'=>'edit','token'=>Class_Encryption::encode($doctor['doctor_id'])),'default',true)?>"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit Doctor Detail" /></a>

						<!--&nbsp;| <a onclick="fancyboxopenfor('<?=$this->url(array('controller'=>'doctor','action'=>'view','doctor_id'=>$doctor['doctor_id']),'default',true)?>')"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a>&nbsp;|

					 	<a onclick="fancyboxopenfordelete('<?=$this->url(array('controller'=>'doctor','action'=>'delete','doctor_id'=>$doctor['doctor_id']),'default',true)?>')"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" /></a>-->

						</td>

								</tr>

								<?php }} else{ ?>

								<tr>

								<td align="center" colspan="11">No Record Found!...</td>

								</tr>

								<?php }?>

								

								<!-- Paging Style : 1 -->

								<tr>

								<th colspan="13" style="text-align:left"><?=CommonFunction::PageCounter($this->doctorLists['Total'], $this->doctorLists['Offset'], $this->doctorLists['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>

								</tr>

                            </tbody>

                            </form>

                        </table>

                        <div style="clear: both"></div>

                     </div> <!-- End .module-table-body -->

                </div> 

				<!-- End .module -->

			</div> <!-- End .grid_12 -->

			

	<script type="text/javascript">

	$(function() {

		$.importForm = function(formVal) {

			if(formVal==1) {

				$('#imp0').show();

				$('#impForm').show();

				$('#impForm1').show();

				$('#imp1').hide();

			}

			else {

				$('#imp0').hide();

				$('#impForm').hide();

				$('#impForm1').hide();

				$('#imp1').show();

			}

		}

		

		$( "#doctorImportForm" ).submit(function( event ) {

			var loggedUser = '<?=$_SESSION['AdminDesignation']?>';

			/*var hqValue = $('#hqt').val();

			if(hqValue=='' && loggedUser<8) {

				alert("Please select headquarter!!");

				event.preventDefault();

			}*/

			

			var file = $('input[type="file"]').val();

			var exts = ['xls','xlsx'];

			// first check if file field has any value

			if ( file ) {

				// split file name at dot

				var get_ext = file.split('.');

				// reverse name to check extension

				get_ext = get_ext.reverse();

				// check file type is valid as given in 'exts' array

				if ( $.inArray ( get_ext[0].toLowerCase(), exts ) <= -1 ){

					alert('Allowed extension .xls and .xlsx only!!');

					event.preventDefault();

				}

			}

			else {

				alert("Please select file path!!");

				event.preventDefault();				

			}

		});

		

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

	

	function fancyboxopenfor(url){

	 $.fancybox({

			"width": "70%",

			"height": "100%",

			"autoScale": true,

			"transitionIn": "fade",

			"transitionOut": "fade",

			"type": "iframe",

			"href": url

		}); 

	}



	function fancyboxopenforpriv(url){

	 $.fancybox({

			"width": "40%",

			"height": "100%",

			"autoScale": true,

			"transitionIn": "fade",

			"transitionOut": "fade",

			"type": "iframe",

			"href": url

		}); 

	}

	

	function fancyboxopenfordelete(url){

	 $.fancybox({

			"width": "40%",

			"height": "40%",

			"autoScale": true,

			"transitionIn": "fade",

			"transitionOut": "fade",

			"type": "iframe",

			"href": url

		}); 

	}

	

	$("#checkHead").click(function() {

    		$(".allchecked").attr('checked', this.checked);

			$(".css-checkbox").attr('checked', this.checked);

		});

</script>	