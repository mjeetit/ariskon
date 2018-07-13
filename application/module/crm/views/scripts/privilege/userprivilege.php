 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Designation Wise Default Privilege Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<!-- START : Search Form -->
							<tr>
							  <td colspan="5">
								<table width="96%" border="0" cellspacing="1" cellpadding="2">
								  <tr height="26">
									<th align="center" colspan="6">Search Form</th>
								  </tr>
								  
								  <tr class="even">
								    <!--Business Executive (BE) Pull Down List for Filter Option-->
									<td align="left" class="bold_text">
									  <?php if($_SESSION['AdminDesignation']<8) { ?>BE : 
									  <select name="token3" id="token3" onchange="setDisable('token3',this.value)">
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
									  <select name="token4" id="token4" onchange="setDisable('token4',this.value)">
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
									  <select name="token5" id="token5" onchange="setDisable('token5',this.value)">
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
									  <select name="token6" id="token6" onchange="setDisable('token6',this.value)">
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
									
									<!--All Designation Pull Down List for Filter Option-->
									<td align="left" class="bold_text">
									<?php if($_SESSION['AdminDesignation']<5) { ?>Designation : 
									  <select name="token7" id="token7" onchange="setDisable('token7',this.value)">
										<option value="">-- Select Designation --</option>
										<?php foreach($this->desigs as $desig){
											$select = '';
											if($this->Filterdata['token7']==Class_Encryption::encode($desig['designation_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($desig['designation_id'])?>" <?=$select?>><?=$desig['designation_code'].' - '.$desig['designation_name']?></option>
										<?php } ?>
									  </select>
									  <?php } ?>
									</td>
									<td align="center" class="bold_text">
									  <input type="submit" name="filter" class="submit-green" value="Search" />
									</td>
								  </tr>
								</table>
							  </td>
							</tr>
                            <!-- END : Search Form -->
													
							<tr>
								<th style="width:2%; text-align:left">SlNo.</td>
								<th style="width:8%; text-align:left"><?=CommonFunction::OrderBy('Emp. Code','EPD.employee_code')?></th>
								<th style="width:10%; text-align:left"><?=CommonFunction::OrderBy('Emp. Name','EPD.first_name')?></th>
								<th style="width:20%; text-align:left"><?=CommonFunction::OrderBy('Designation Name','DT.designation_name')?></th>
								<th style="width:60%; text-align:left">Default Privilege</th>
							</tr>
							
                            <tbody>
                             <?php if(count($this->viewData['Records'])>0) { foreach($this->viewData['Records'] as $key=>$priv) {?>
							 <tr>
							 	<td><?=($key+1)?>.</td>
								<td><?=$priv['employee_code']?></td>
								<td><a href="javascript:void(0);" onclick="fancyboxopenfor('<?=$this->url(array('controller'=>'Privilege','action'=>'edituserprivilege','token'=>Class_Encryption::encode($priv['user_id']),'token1'=>Class_Encryption::encode($priv['designation_id'])),'default',true)?>')" title="See All Modules" /><?=$priv['emp']?></a></td>
								<td><?=$priv['desig']?></td>
								<td><?=$priv['module']?> <a href="javascript:void(0);" onclick="fancyboxopenfor('<?=$this->url(array('controller'=>'Privilege','action'=>'edituserprivilege','token'=>Class_Encryption::encode($priv['user_id']),'token1'=>Class_Encryption::encode($priv['designation_id'])),'default',true)?>')" title="See All Modules" />[See All Modules]</a></td>
							 </tr>
							 <?php } } else { ?>
							 <tr><td colspan="3" align="center">No record found!!</td></tr>
							 <?php }?>
							 
							 <!-- Paging Style : 1 -->
							 <tr>
								<th colspan="5" style="text-align:left"><?=CommonFunction::PageCounter($this->viewData['Total'], $this->viewData['Offset'], $this->viewData['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>
							 </tr>
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div>
			
<script type="text/javascript">
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

function setDisable(box,tokenVal) {
	var arr = ["token3","token4","token5","token6","token7"];
	jQuery.each(arr, function() {
		if(tokenVal != '') {
			if(this == box) {
				$('#'+this).removeAttr('disabled');
			}
			else {
				$('#'+this).val('');
				$('#'+this).attr('disabled', 'disabled');
			}
		}
		else {
			$('#'+this).removeAttr('disabled');
		}
	});
}
</script>	