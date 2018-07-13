	 <div class="grid_12">  

	            <!-- Example table -->

                <div class="module">  

                	<h2><span>Missed Call</span></h2>

                    

                    <div class="module-table-body"> 

						<!-- START : Search Form -->

						<form name="listFrom" id="listForm" action="" method="get" >

							<table width="96%" border="0" cellspacing="1" cellpadding="2">

							  <tr>

								<th align="center" colspan="5">Search Form</th>

							  </tr>

							  

							  <tr class="even">

								<!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->

								<?php if($_SESSION['AdminDesignation']<5) { ?>

								<td align="left" class="bold_text">ZBM : 

								  <select name="token6" id="zbm_id" onchange="getchild(this.value,'rbm_id','6');gethq(this.value,'headquater_id','5','');">

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

								  <select name="token5" id="rbm_id" onchange="getchild(this.value,'abm_id','7');gethq(this.value,'headquater_id','6','');">

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

								  <select name="token4" id="abm_id" onchange="getchild(this.value,'be_id','8');gethq(this.value,'headquater_id','7','');">

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

								  <select name="token3" id="be_id" onchange="gethq(this.value,'headquater_id','8','')">

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

								<?php if($_SESSION['AdminDesignation']<8) {?>

								<td class="bold_text" align="left">Headquarter :

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

								</td>

								<?php } ?>

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
									&nbsp;

         <input type="submit" name="exportmissedcall" class="submit-green" value="Export Missed Call Summary" title="Export Data in Excel" />
								</td>

							  </tr>

							</table>

						</form>

						<!-- END : Search Form -->

							

							<table id="myTable" class="tablesorter">

							<thead>							

							<tr>

							<th align="center" width="5%"><?=CommonFunction::OrderBy('S.No.','')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Doctor Name','DD.doctor_name')?></th>
							<th align="center" width="5%"><?=CommonFunction::OrderBy('Speciality','DD.speciality')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('Patch','PC.patch_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('HQ','EM.headquater_name')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('BE','EM.first_name')?></th>


							<th align="center" width="8%"><?=CommonFunction::OrderBy('ABM','ABM.first_name')?></th>

							<th align="center" width="10%"><?=CommonFunction::OrderBy('RBM','RBM.first_name')?></th>


							<th align="center" width="10%"><?=CommonFunction::OrderBy('Month','EE.call_date')?></th>


							</tr>

						

							<?php 

							if(count($this->vistdetail['Records'])>0){

								foreach($this->vistdetail['Records'] as $key=>$report) {//print_r($report);die;

								  $class = ($key%2==0)?'even':'odd';

								  $key = ($key+1);
								  if($key>=$this->vistdetail['Offset'] && $key<=$this->vistdetail['Offset']+100){
								  
								  

							?>

						 <tr class="<?=$class?>">

						 <td><?=$key.'.'?></td>

						 <td><?=$report['doctor_name']?></td>

						 <td><?=$report['speciality']?></td>
						 <td><?=$report['patch_name']?></td>
						 <td><?=$report['headquater_name']?></td>

						 <td><?=$report['doct_be']?></td>
						 <td><?=$report['doct_abm']?></td>
						 <td><?=$report['doct_rbm']?></td>
						 
						 <td><?=date('F-Y',strtotime(stringInsert($report['Month'],4,'-')));?></td>						

						 </tr>

						 <?php } } }  else{ ?>

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

	<?php 
	
	
	function stringInsert($str,$pos,$insertstr)
{
    if (!is_array($pos))
        $pos=array($pos);

    $offset=-1;
        foreach($pos as $p)
        {
            $offset++;
    $str = substr($str, 0, $p+$offset) . $insertstr . substr($str, $p+$offset);
        }
    return $str;
}
	?>		

	<script type="text/javascript">
	var zbm = '<?php echo $this->Filterdata['token6'];?>';
	 var rbm = '<?php echo $this->Filterdata['token5'];?>';
	 var abm = '<?php echo $this->Filterdata['token4'];?>';
	 var be = '<?php echo $this->Filterdata['token3'];?>';
     var hq_id = '<?php echo $this->Filterdata['token2'];?>';
     if(zbm!=''){
	 	getchild(zbm,'rbm_id','6',rbm);
		gethq(zbm,'headquater_id','5',hq_id);
	 }
	 if(rbm!=''){
	 	getchild(rbm,'abm_id','7',abm);
		gethq(rbm,'headquater_id','6',hq_id);
	 }
	 if(abm!=''){
	 	getchild(abm,'be_id','8',be);
		gethq(abm,'headquater_id','7',hq_id);
	 }
	 if(be!=''){
	   gethq(be,'headquater_id','8',hq_id);
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

