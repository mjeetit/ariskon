c <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>CRM Report</span></h2>
                    
                    <div class="module-table-body">
                    	<form name="listFrom" id="listForm" action="" method="get" >
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
							<!-- START : Search Form -->
							<tr>
							  <td colspan="14">
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
									<!--Newly inserted Code-->
									<td align="left" class="bold_text">Status:
										<select name="token7">
											<option value="">-- Select Status--</option>
											<option value="<?=Class_Encryption::encode(3)?>" <?=$select?> <?php if($this->Filterdata['token7']==Class_Encryption::encode(3)) {echo 'selected="selected"';}?>>Pending</option>
											<option value="<?=Class_Encryption::encode(1)?>"<?php if($this->Filterdata['token7']==Class_Encryption::encode(1)) {echo 'selected="selected"';}?>">Approved</option>
											<option value="<?=Class_Encryption::encode(2)?>" <?php if($this->Filterdata['token7']==Class_Encryption::encode(2)) {echo 'selected="selected"';}?>>Rejected</option>
										</select>
									</td>
									<!--Newly inserted Code-->
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
									<td align="center" class="bold_text">
									  <input type="submit" name="filter" class="submit-green" value="Search" style="margin-bottom:5px" />
									  <input type="submit" name="exportVisit" class="submit-green" value="Export in Excel" title="Export Data in Excel" />
									</td>
								  </tr>
								  
								  <!--<tr class="even">
								    <td align="left" colspan="5">
									  <img src="<?=Bootstrap::$baseUrl;?>public/admin_images/arrow_dwn.gif" alt="" align="absmiddle" border="0" />
									  <a href="javascript:void(1);" onclick="javascript: markAllSelectedRows('listForm'); return false;" class="new_link">Check All</a> / 
									  <a href="javascript:void(1);" onclick="javascript: unMarkSelectedRows('listForm'); return false;" class="new_link">Uncheck All</a>
									</td>
								  </tr>-->
								</table>
							  </td>
							</tr>
                            <!-- END : Search Form -->
							    
							<tr>
								<th style="width:3%; text-align:center">SL No.</td>
								<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Date','AT.created_date')?></th>
								<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('CRM No','AT.appointment_code')?></th>
								<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Dr. Name','DT.doctor_name')?></th>	
								<!--New Inserted Code -->
								<th style=	"width:5%; text-align:center"><?=CommonFunction::OrderBy('Headquarter','HQ.headquater_name')?></th>
								<th style=	"width:10%; text-align:center"><?=CommonFunction::OrderBy('
								Requested BY','EP.first_name')?></th>
								<!--End of newly inserted code-->
								<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Quality','DT.qualification')?></th>
								<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Patch','PC.patch_name')?></th>
								<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Class of Dr.','DT.class')?></th>
								<!--<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Activity Type','DA.activity_name')?></th>-->
								<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Expense Type','ET.type_name')?></th>
								<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Expense Cost','AT.expense_cost')?></th>
								<?php if($_SESSION['AdminDesignation']<8 OR $_SESSION['AdminDesignation']==34) { ?><th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('ABM Status','AT.abm_approval')?></th><?php } ?>
								<?php if($_SESSION['AdminDesignation']<7 OR $_SESSION['AdminDesignation']==34) { ?><th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('RBM Status','AT.rbm_approval')?></th><?php } ?>
								<?php if($_SESSION['AdminDesignation']<6 OR $_SESSION['AdminDesignation']==34) { ?><th style="width:7%; text-align:center"><?=CommonFunction::OrderBy('ZBM Status','AT.zbm_approval')?></th><?php } ?>
								<?php if($_SESSION['AdminLevelID']==1 OR $_SESSION['AdminDesignation']==34) { ?><th style="width:7%; text-align:center"><?=CommonFunction::OrderBy('HO Status','AT.business_audit_status')?></th><?php } ?>
								<?php if($_SESSION['AdminLevelID']==1 OR $_SESSION['AdminDesignation']==34) { ?><th style="width:5%; text-align:center"><?=CommonFunction::OrderBy('GM Status','AT.gm_audit_status')?></th><?php } ?>
							</tr>
                            </thead>
                            <tbody>
                             <?php
							 $approvalArray = array(0=>'Pending',1=>'Approved',2=>'Rejected');
							 $fontArray = array(0=>'#000000;',1=>'#009900;',2=>'#FF0000;');
							 if(count($this->appointments['Records'])>0){
								foreach($this->appointments['Records'] as $key=>$appoint) {
								  $class = ($key%2==0)?'even':'odd';
								  $key = ($key+1);
								?>
								<tr class="<?php echo  $class;?>">
								<td><?=(isset($this->Filterdata['offset'])) ? ($this->Filterdata['offset']+$key).'.' : $key.'.'?></td>
								<td align="center"><?=date('Y-m-d',strtotime($appoint['created_date']))?></td>
								<td align="center"><?=$appoint['appointment_code']?></td>
								<td align="center"><?=$appoint['doctor_name']?></td>					
								<!--Newly inserted code-->
								<td align="center"><?=$appoint['headquater_name']?></td>
								<td align="center"><?=$appoint['first_name'].' '.$appoint['last_name']?></td>
								<!--End of newly inserted code-->
								<td align="center"><?=$appoint['qualification']?></td>
								<td align="center"><?=$appoint['patch_name']?></td>
								<td align="center"><?=$appoint['class']?></td>
								<!--<td align="center"><?=$appoint['activity_name']?></td>-->
								<td align="center"><?=$appoint['type_name']?></td>
								<td align="center"><?=$appoint['expense_cost']?></td>
								<?php if($_SESSION['AdminDesignation']<8 OR $_SESSION['AdminDesignation']==34){?>
									<td align="center" style="color:<?=$fontArray[$appoint['abm_approval']]?>"><?=($appoint['abm_id']>0) ? $approvalArray[$appoint['abm_approval']] : '--NR--'?></td>
								<?php }?>
								<?php if($_SESSION['AdminDesignation']<7 OR $_SESSION['AdminDesignation']==34){?>
									<td align="center" style="color:<?=$fontArray[$appoint['rbm_approval']]?>"><?=($appoint['rbm_id']>0) ? $approvalArray[$appoint['rbm_approval']] : '--NR--'?></td>
								<?php }?>
								<?php if($_SESSION['AdminDesignation']<6 OR $_SESSION['AdminDesignation']==34){?>
									<td align="center" style="color:<?=$fontArray[$appoint['zbm_approval']]?>"><?=($appoint['zbm_id']>0) ? $approvalArray[$appoint['zbm_approval']] : '--NR--'?></td>
								<?php }?>
								<?php if($_SESSION['AdminLevelID']==1 OR $_SESSION['AdminDesignation']==34){?>
									<td align="center" style="color:<?=$fontArray[$appoint['business_audit_status']]?>"><?=$approvalArray[$appoint['business_audit_status']]?></td>
								<?php }?>
								<?php if($_SESSION['AdminLevelID']==1 OR $_SESSION['AdminDesignation']==34){?>
									<td align="center" style="color:<?=$fontArray[$appoint['gm_audit_status']]?>"><?=$approvalArray[$appoint['gm_audit_status']]?></td>
								<?php }?>								
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="6">No Record Found!...</td>
								</tr>
								<?php }?>
								
								<!-- Paging Style : 1 -->
								<tr>
								<th colspan="13" style="text-align:left"><?=CommonFunction::PageCounter($this->appointments['Total'], $this->appointments['Offset'], $this->appointments['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>
								</tr>
                            </tbody>
                        </table>
                        </form>
						
                        <!-- Paging Style : 2 -->
						<?php /*?><div class="pager" id="pager">
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
							</div><?php */?>
							
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
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