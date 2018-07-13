	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Doctor Visit Detail</span></h2>
                    
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
								<?php if($_SESSION['AdminDesignation']<6) { ?>
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
								<?php if($_SESSION['AdminDesignation']<7) { ?>
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
								<?php if($_SESSION['AdminDesignation']<8) { ?>
								<td align="left" class="bold_text">BE : 
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
								<td colspan="5" align="center">
									<input type="submit" name="filter" class="submit-green" value="Search" /> &nbsp;
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
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Reportee Name','PC.patch_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Patch','PC.patch_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Doctor Name','DD.doctor_name')?></th>
							<th align="center" width="5%"><?=CommonFunction::OrderBy('Speciality','PC.patch_name')?></th>
							<th align="center" width="8%"><?=CommonFunction::OrderBy('Class of Dr.','PC.patch_name')?></th>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Activity Type','PC.patch_name')?></th>
							<?php if($_SESSION['AdminDesignation']!=5){?>
								<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit with ZBM','ZBM.first_name')?></th>
							<?php } if($_SESSION['AdminDesignation']!=6){?>
								<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit with RBM','RBM.first_name')?></th>
							<?php } if($_SESSION['AdminDesignation']!=7){?>
								<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit with ABM','ABM.first_name')?></th>
							<?php } if($_SESSION['AdminDesignation']!=8){?>
								<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit with BE','BE.first_name')?></th>
							<?php } ?>
							<th align="center" width="10%"><?=CommonFunction::OrderBy('Visit Date','EE.call_date')?></th>
							<th align="center" width="7%"><?=CommonFunction::OrderBy('Visit Time','EE.call_time')?></th>
							</tr>
						
							<?php 
							if(count($this->vistdetail['Records'])>0){
								foreach($this->vistdetail['Records'] as $key=>$report) {
								  $class = ($key%2==0)?'even':'odd';
								  $key = ($key+1);
							?>
						 <tr class="<?=$class?>">
						 <td><?=(isset($this->Filterdata['offset'])) ? ($this->Filterdata['offset']+$key).'.' : $key.'.'?></td>
						 <td><?=$report['reportee']?></td>
						 <td><?=$report['patch_name']?></td>
						 <td><?=$report['doctor_name']?></td>
						 <td><?=$report['speciality']?></td>
						 <td><?=$report['class']?></td>
						 <td><?=$report['activity_name']?></td>
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
						 <td><?=$report['call_date']?></td>
						 <td><?=$report['call_time']?></td>
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
