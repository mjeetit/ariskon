	 <div class="grid_12">  

	            <!-- Example table -->

                <div class="module">  

                	<h2><span>Doctor Wise Call Report</span></h2>

                    

                    <div class="module-table-body"> 

						<!-- START : Search Form -->

						<form name="listFrom" id="listForm" action="" method="get" >

							<table width="96%" border="0" cellspacing="1" cellpadding="2">

							  <tr>

								<th align="center" colspan="5">Search Form</th>

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

                                

                                <!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']<5) { ?>

								<td align="left" class="bold_text">ZBM : 

								  <select name="token6" id="token6" onchange="getchild(this.value,'token5','6')">

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

								<?php if($_SESSION['AdminDesignation']<6) { ?>

								<td align="left" class="bold_text">RBM : 

								  <select name="token5" id="token5" onchange="getchild(this.value,'token4','7')">

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

								<?php if($_SESSION['AdminDesignation']<7) { ?>

								<td align="left" class="bold_text">ABM : 

								  <select name="token4" id="token4" onchange="getchild(this.value,'token3','8')">

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

								<?php if($_SESSION['AdminDesignation']<8) { ?>

								<td align="left" class="bold_text">BE : 

								  <select name="token3" id="token3">

									<option value="">-- Select BE --</option>

									<?php foreach($this->beDetails as $beDetail){

										$select = '';

										if($this->Filterdata['token3']==Class_Encryption::encode($beDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($beDetail['user_id'])?>" <?=$select?>><?=$beDetail['first_name'].' '.$beDetail['last_name']?></option>

									<?php } ?>

								  </select>

								</td>

								<?php } ?>

                                

                                <!--Headquater Pull Down List for Filter Option-->

								<?php /*if($_SESSION['AdminDesignation']<8) {?>

								<td class="bold_text" align="left">Headquarter :

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

								</td>

								<?php }*/ ?>

							  </tr>

							  

							  <tr class="odd">
								<td width="20%" class="bold_text" align="left">

									From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->Filterdata['from_date']?>" />

								</td>

								<td width="20%" class="bold_text" align="left">

									To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->Filterdata['to_date']?>" />

								</td>
								<td colspan="3" align="center">

									<input type="submit" name="filter" class="submit-green" value="Search" /> &nbsp;

									<input type="submit" name="exportVisit" class="submit-green" value="Export in Excel" title="Export Data in Excel" />

								</td>

							  </tr>

							</table>

						</form>
<style>
td div input[type='text'] {
    width: 50% !important;
    border: 1px solid #ddd;
}
table {
    overflow: hidden !important;
}
</style>
						<!-- END : Search Form -->
							<table>
						<tr>
						   <td colspan="2">
							<?php  echo $this->pages; ?>
						   </td>
						</tr>
							</table>                       

                        <div style="clear: both"></div>

                     </div> <!-- End .module-table-body -->

                </div> 

				<!-- End .module -->

			</div> <!-- End .grid_12 --> 

			

	<script type="text/javascript">
	 var zbm = '<?php echo $this->Filterdata['token6'];?>';
	 var rbm = '<?php echo $this->Filterdata['token5'];?>';
	 var abm = '<?php echo $this->Filterdata['token4'];?>';
	 var be = '<?php echo $this->Filterdata['token3'];?>';
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

