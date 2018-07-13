 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>ROI Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
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
							   
							   <tr>
									<th style="width:3%; text-align:center">#</td>
									<th style="width:10%; text-align:center">Doctor Name</th>
									<th style="width:8%; text-align:center">CRM Expense Cost</th>
									<th style="width:8%; text-align:center">CRM Amount</th>
									<th style="width:8%; text-align:center">ROI Amount</th>
									<th style="width:8%; text-align:center">ROI Month</th>
									<th style="width:12%; text-align:center">Action</th>
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
								  
								  <?php
									$isEditROI = 0;
									if($_SESSION['AdminDesignation']==7 && $roi['abm_id']==$_SESSION['AdminLoginID'] && $roi['roiApproval']>0) {
										$isEditROI = 1;
									}
									else if($_SESSION['AdminDesignation']==6 && $roi['abm_id']==0 && $roi['rbm_id']==$_SESSION['AdminLoginID'] && $roi['roiApproval']>0) {
										$isEditROI = 1;
									}
									else if($_SESSION['AdminDesignation'] == 5 && $roi['abm_id']==0 && $roi['rbm_id']==0 && $roi['zbm_id'] == $_SESSION['AdminLoginID'] && $roi['roiApproval']>0) {
										$isEditROI = 1;
									}
									else {
										$isEditROI = 0;
									}
								  ?>
								  
								  <td align="center">
									<?php if($roi['roi_month'] != $roiMonth) {?>
									<a href="<?=$this->url(array('controller'=>'Roi','action'=>'add','token'=>Class_Encryption::encode($roi['doctor_id'])),'default',true)?>"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/i_add.png" title="Add ROI Data" /></a>
									<?php } else { if($isEditROI==1) {?>
									<a href="<?=$this->url(array('controller'=>'Roi','action'=>'edit','token'=>Class_Encryption::encode($roi['doctor_id'])),'default',true)?>"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit" /></a>
									<?php } } ?>
								  </td>
								</tr>
								<?php } } else{ ?>
								<tr>
								  <td align="center" colspan="6">No CRM found to do ROI !!...</td>
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
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
                        <?php /*?></div>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
 
        </div>
        <div class="clear"></div>
    </div><?php */?>
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
</script>	