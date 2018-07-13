	 <div class="grid_12">  

	            <!-- Example table -->

                <div class="module">  

                	<h2><span>Doctor Visit Frequency</span></h2>

                    

                    <div class="module-table-body"> 

						<!-- START : Search Form -->

						<form name="listFrom" id="listForm" action="" method="get" >

							<table width="96%" border="0" cellspacing="1" cellpadding="2">

							  <tr height="26">

								<th align="center" colspan="5">Search Form</th>

							  </tr>

							  

							  <tr class="even">

							  	<td width="20%" align="left" class="bold_text">Year : 

									<?php $yearform = (isset($this->Filterdata['year']) && !empty($this->Filterdata['year'])) ? trim($this->Filterdata['year']) : date('Y');?>

									<select name="year" id="year">

										<option value="2014" <?php if($yearform=='2014'){ ?>selected="selected"<?php } ?>>2014</option>

										<option value="2015" <?php if($yearform=='2015'){ ?>selected="selected"<?php } ?>>2015</option>
										<option value="2016" <?php if($yearform=='2016'){ ?>selected="selected"<?php } ?>>2016</option>
										<option value="2017" <?php if($yearform=='2017'){ ?>selected="selected"<?php } ?>>2017</option>
										<option value="2018" <?php if($yearform=='2018'){ ?>selected="selected"<?php } ?>>2018</option>

									</select>

								</td>

								<td width="20%" align="left" class="bold_text">Month : 

									<?php $monthform = (isset($this->Filterdata['month']) && !empty($this->Filterdata['month'])) ? trim($this->Filterdata['month']) : date('m');?>

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

                                <!--From Date for Filter Option-->

								<td width="20%" class="bold_text" align="left">

									From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->Filterdata['from_date']?>" />

								</td>

								<td width="20%" class="bold_text" align="left">

									To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->Filterdata['to_date']?>" />

								</td>

								<td width="20%" align="left" class="bold_text"></td>

							  </tr>

                              

                              <tr class="odd">

								<!--Doctor Pull Down List for Filter Option-->

								<td align="left" class="bold_text">Doctor :

								  <select name="token1" id="doctor_id">

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

								

								<!--Headquater Pull Down List for Filter Option-->

								<td class="bold_text" align="left">

									<?php if($_SESSION['AdminDesignation']<8) {?>Headquarter :

								  <select name="token2" id="headquater_id">

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

								<td class="bold_text" align="center"></td>

							  </tr>

                              

                              <tr class="even">

								<!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']<5) { ?>

								<td align="left" class="bold_text">ZBM : 

								  <select id="zbm_id" name="token6" onchange="getchild(this.value,'rbm_id','6');gethq(this.value,'headquater_id','5','');getdotr(this.value,'doctor_id','5','')">

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

								 <select id="rbm_id" name="token5" onchange="getchild(this.value,'abm_id','7');gethq(this.value,'headquater_id','6','');getdotr(this.value,'doctor_id','6','')">

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

								  <select id="abm_id" name="token4" onchange="getchild(this.value,'be_id','8');gethq(this.value,'headquater_id','7','');getdotr(this.value,'doctor_id','7','')">

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

								  <select name="token3" id="be_id" onchange="gethq(this.value,'headquater_id','8','');getdotr(this.value,'doctor_id','8','')">

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

								

								<td class="bold_text" align="center">

								 <input type="submit" name="filter" class="submit-green" value="Search" />

								  <input type="submit" name="exportVisit" class="submit-green" value="Export in Excel" title="Export Data in Excel" />

								</td>	

							  </tr>


							</table>

							</form>

						<!-- END : Search Form -->

							

							<table id="myTable" class="tablesorter">

							<thead>

							<tr>

							<th>Sl No.</th>

							<?php foreach($this->tableHeader as $key=>$header) { ?>

								<th><?=CommonFunction::OrderBy($header,$key)?></th>

							<?php } ?>

							</tr>

						

							<?php 

							if(count($this->vistdetail['Records'])>0){

								foreach($this->vistdetail['Records'] as $key=>$visit) {

								  $class = ($key%2==0)?'even':'odd';

								  $key = ($key+1);

							?>

						 <tr class="<?=$class?>">

						 <td><?=(isset($this->Filterdata['offset'])) ? ($this->Filterdata['offset']+$key).'.' : $key.'.'?></td>

						 <td><?=$visit['doctor_name']?></td>

                         <td><?=$visit['speciality']?></td>

						 <td><?=$visit['class']?></td>

						 <td><?=$visit['visit_month']?></td>

						 <td><?=$visit['Days']?></td>
						 <td><?=$visit['name']?></td>
						 <td><?=$visit['employee_code']?></td>
						 <td><?=$visit['headquater_name']?></td>

						 </tr>

						 <?php } }  else{ ?>

						<tr>

						<td align="center" colspan="<?=(count($this->tableHeader)+1)?>">No Record Found!...</td>

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
	 var zbm = '<?php echo $this->Filterdata['token6'];?>';
	 var rbm = '<?php echo $this->Filterdata['token5'];?>';
	 var abm = '<?php echo $this->Filterdata['token4'];?>';
	 var be = '<?php echo $this->Filterdata['token3'];?>';
     var hq_id = '<?php echo $this->Filterdata['token2'];?>';
	 var dr_id = '<?php echo $this->Filterdata['token1'];?>';
     if(zbm!=''){
	 	getchild(zbm,'rbm_id','6',rbm);
		gethq(zbm,'headquater_id','5',hq_id);
		getdotr(zbm,'doctor_id','5',dr_id);
	 }
	 if(rbm!=''){
	 	getchild(rbm,'abm_id','7',abm);
		gethq(rbm,'headquater_id','6',hq_id);
		getdotr(rbm,'doctor_id','6',dr_id);
	 }
	 if(abm!=''){
	 	getchild(abm,'be_id','8',be);
		gethq(abm,'headquater_id','7',hq_id);
		getdotr(abm,'doctor_id','7',dr_id);
	 }
	 if(be!=''){
	   gethq(be,'headquater_id','8',hq_id);
	   getdotr(be,'doctor_id','8',dr_id);
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

