<div class="grid_12">
  <div class="module">
    <h2><span>Edit ROI Data</span></h2>
	<div class="module-body">
	  <form name="data_setting" action="" method="post"> 
	    <table width="70%" style="border:none">
		  <thead>
		    <tr>
			  <td align="center" style="border:none">
			    <table style=" width:100%">
				  <thead>
				    <tr>
					  <td align="left" style="border:none">
					    <a href="<?php echo $this->url(array('controller'=>'Roi','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr>
                      <td width="100%" valign="top" align="left">
                        <table width="100%" cellpadding="2" cellspacing="2" border="0">
                          <tr class="odd">
							<input type="hidden" name="month" value="<?=date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')))?>"/>
							<td align="center" colspan="4"><b>ROI for Month <?=date("M Y",mktime(0,0,0,date('m')-1,date('d'),date('Y')))?></b></td>
                          </tr>						
						<h2><span> ROI for Month <?=date("M Y",mktime(0,0,0,date('m')-1,date('d'),date('Y')))?></span></h2>
						<tr class="even">
						  <td width="25%" align="left"><b>Product</b></td>
						  <td width="15%" align="left"><b>Unit</b></td>
						  <td width="60%" align="left"><b>Value</b></td>
						</tr>                       

						<?php 
						$roiTotal = 0;
						foreach($this->allProducts as $i=>$product)
						{
							if(in_array($product['product_id'],$this->roiDetail['products']))
							{
							   $trClass = ($i%2==0) ? 'odd' : 'even';
						?>

						<tr class="<?=$trClass?>">
                          <td align="left" valign="top"><?=$product['product_name']?></td>
                          <td align="left" valign="top"><?=$this->roiDetail['productPrice'][$product['product_id']]['unit']?></td>
						  <td align="left" valign="top"><?=$this->roiDetail['productPrice'][$product['product_id']]['value']?></td>
                        </tr>
						<?php $roiTotal += $this->roiDetail['productPrice'][$product['product_id']]['value']; } } ?>
						
						<?php
						$isApprovalROI = 0;
						if($_SESSION['AdminDesignation']==7 && $this->roiDetail['lastCRMDetail']['abm_id']==$_SESSION['AdminLoginID'] && $this->roiDetail['unapprovalROICount']['CNT']!=0) {
							$isApprovalROI = 1;
						}
						else if($_SESSION['AdminDesignation']==6 && $this->roiDetail['lastCRMDetail']['abm_id']==0 && $this->roiDetail['lastCRMDetail']['rbm_id']==$_SESSION['AdminLoginID'] && $this->roiDetail['unapprovalROICount']['CNT']!=0) {
							$isApprovalROI = 1;
						}
						else if($_SESSION['AdminDesignation'] == 5 && $this->roiDetail['lastCRMDetail']['abm_id']==0 && $this->roiDetail['lastCRMDetail']['rbm_id']==0 && $this->roiDetail['lastCRMDetail']['zbm_id'] == $_SESSION['AdminLoginID'] && $this->roiDetail['unapprovalROICount']['CNT']!=0) {
							$isApprovalROI = 1;
						}
						else {
							$isApprovalROI = 0;
						}
						if($isApprovalROI==1) {
					?>
					<tr class="odd">
                        <td colspan="2"></td>
						<td align="left" class="bold_text">
						  <input type="hidden" name="roitoken" value="<?=Class_Encryption::encode($this->roiDetail['roiToken'])?>" />							
						  <input type="radio" name="editpotential" value="1" id="approvedbe" onclick="$.checkapprovalmethod('1')" /> Approved BE Data &nbsp;&nbsp;
						  <input type="radio" name="editpotential" value="2" id="ownpotentail" onclick="$.checkapprovalmethod('2')" /> Provide Your Data
						</td>
					</tr>
					
					<!-- Approved Same Data which has provided by BE -->
					<tr>
                      <td valign="top" align="left" colspan="3">
					   <div id="samebedata" style="display:none;width:1165px; overflow-x:auto;overflow-y:none;!important">
                        <h2><span> ROI for Month <?=date("M Y",mktime(0,0,0,date('m')-1,date('d'),date('Y')))?> : Approved Same Data which has provided by BE</span></h2>
						<table cellpadding="2" cellspacing="2" border="0">
                          <tr class="even">
						  <td width="25%" align="left"><b>Product</b></td>
						  <td width="15%" align="left"><b>Unit</b></td>
						  <td width="60%" align="left"><b>Value</b></td>
						</tr>                       

						<?php 
						foreach($this->allProducts as $i=>$product)
						{
							if(in_array($product['product_id'],$this->roiDetail['products']))
							{
							   $trClass = ($i%2==0) ? 'odd' : 'even';
						?>

						<tr class="<?=$trClass?>">
                          <td align="left" valign="top"><?=$product['product_name']?></td>
                          <td align="left" valign="top">
						  	<input type="hidden" name="same_token2[]" value="<?php echo $product['product_id'];//Class_Encryption::encode($product['product_id'])?>" />
						    <input type="hidden" name="same_unit[]" id="unit_<?=$i?>" class="input-medium" onblur="$.getValue('<?=$i?>','<?=(isset($this->productPrice[$product['product_id']])) ? $this->productPrice[$product['product_id']] : 0?>')" value="<?=$this->roiDetail['productPrice'][$product['product_id']]['unit']?>" />
							<?=$this->roiDetail['productPrice'][$product['product_id']]['unit']?>
						  </td>
						  <td align="left" valign="top"><input type="hidden" name="same_value[]" id="value_<?=$i?>" readonly="readonly" class="input-medium" value="<?=$this->roiDetail['productPrice'][$product['product_id']]['value']?>" /><?=$this->roiDetail['productPrice'][$product['product_id']]['value']?></td>
                        </tr>
						<?php } } ?>
						
						<!-- First Extra JCL Product Selection List -->
						<tr>
							<td align="left" class="bold_text">Select Product 1 : &nbsp;&nbsp;
								<?php $countValue = count($this->allProducts)+1; ?>
								<select name="same_token2[]" id="product_<?=$countValue?>" onchange="$.getValues('<?=$countValue?>')">
								  <option value="" selected="selected">Please select</option>
								  <?php foreach($this->allProducts as $products){ if(!in_array($products['product_id'],$this->crmProducts)){ ?>
								  <option value="<?=$products['product_id']?>"><?=$products['product_name']?></option>
								  <?php } } ?>
								</select>
							</td>
							<td align="left"><input type="text" name="same_unit[]" id="unit_<?=$countValue?>" class="input-medium" onblur="$.getValues('<?=$countValue?>')" pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" /></td>
							<td align="left"><input type="text" name="same_value[]" id="value_<?=$countValue?>" readonly="readonly" class="input-medium" /></td>
						</tr>
						
						<!-- Second Extra JCL Product Selection List -->
						<tr>
							<td align="left" class="bold_text">Select Product 2 : &nbsp;&nbsp;
								<?php $countValue = count($this->allProducts)+2; ?>
								<select name="same_token2[]" id="product_<?=$countValue?>" onchange="$.getValues('<?=$countValue?>')">
								  <option value="" selected="selected">Please select</option>
								  <?php foreach($this->allProducts as $products){ if(!in_array($products['product_id'],$this->crmProducts)){ ?>
								  <option value="<?=$products['product_id']?>"><?=$products['product_name']?></option>
								  <?php } } ?>
								</select>
							</td>
							<td align="left"><input type="text" name="same_unit[]" id="unit_<?=$countValue?>" class="input-medium" onblur="$.getValues('<?=$countValue?>')" pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" /></td>
							<td align="left"><input type="text" name="same_value[]" id="value_<?=$countValue?>" readonly="readonly" class="input-medium" /></td>
						</tr>
						
						<tr class="odd">
                            <td align="left"></td>
							<td align="right"><b>Total Value</b></td>
							<td align="left" valign="top"><input type="text" name="same_tot_val" id="tot_val" readonly="readonly" class="input-medium" value="<?=$roiTotal?>"/></td>
                        </tr>
						  
						  <tr class="odd">
						  	<td align="left" colspan="3"><input type="submit" name="approvalsame" id="approvalsame" value="Approved BE Data" /></td>
						  </tr>					
                        </table>
					   </div>
                      </td>
                    </tr>
					
					<!-- Provide Own Data : Potential of Dr for JCL Products -->
					<tr>
                      <td width="23%" valign="top" align="left" colspan="3">
					   <div id="owndata" style="display:none;width:1165px; overflow-x:auto;overflow-y:none;!important">
                        <h2><span> ROI for Month <?=date("M Y",mktime(0,0,0,date('m')-1,date('d'),date('Y')))?> : Provide Own Data Against BE Data</span></h2>
						<table cellpadding="2" cellspacing="2" border="0">
                          <tr class="even">
						  <td width="25%" align="left"><b>Product</b></td>
						  <td width="15%" align="left"><b>Unit</b></td>
						  <td width="60%" align="left"><b>Value</b></td>
						</tr>                       

						<?php 
						foreach($this->allProducts as $i=>$product)
						{
							if(in_array($product['product_id'],$this->roiDetail['products']))
							{
							   $trClass = ($i%2==0) ? 'odd' : 'even';
						?>

						<tr class="<?=$trClass?>">
                          <td align="left" valign="top"><?=$product['product_name']?></td>
                          <td align="left" valign="top">
						  	<input type="hidden" name="own_token2[]" value="<?php echo $product['product_id'];//Class_Encryption::encode($product['product_id'])?>" />
						    <input type="text" name="own_unit[]" id="unit_<?=$i?>" class="input-medium" onblur="$.getValue('<?=$i?>','<?=(isset($this->productPrice[$product['product_id']])) ? $this->productPrice[$product['product_id']] : 0?>')" value="<?=$this->roiDetail['productPrice'][$product['product_id']]['unit']?>" required pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
						  </td>
						  <td align="left" valign="top"><input type="text" name="own_value[]" id="value_<?=$i?>" readonly="readonly" class="input-medium" value="<?=$this->roiDetail['productPrice'][$product['product_id']]['value']?>" /></td>
                        </tr>
						<?php } } ?>
						
						<!-- First Extra JCL Product Selection List -->
						<tr>
							<td align="left" class="bold_text">Select Product 1 : &nbsp;&nbsp;
								<?php $countValue = count($this->allProducts)+1; ?>
								<select name="token2[]" id="product_<?=$countValue?>" onchange="$.getValues('<?=$countValue?>')">
								  <option value="" selected="selected">Please select</option>
								  <?php foreach($this->allProducts as $products){ if(!in_array($products['product_id'],$this->crmProducts)){ ?>
								  <option value="<?=$products['product_id']?>"><?=$products['product_name']?></option>
								  <?php } } ?>
								</select>
							</td>
							<td align="left"><input type="text" name="own_unit[]" id="unit_<?=$countValue?>" class="input-medium" onblur="$.getValues('<?=$countValue?>')" pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" /></td>
							<td align="left"><input type="text" name="own_value[]" id="value_<?=$countValue?>" readonly="readonly" class="input-medium" /></td>
						</tr>
						
						<!-- Second Extra JCL Product Selection List -->
						<tr>
							<td align="left" class="bold_text">Select Product 2 : &nbsp;&nbsp;
								<?php $countValue = count($this->allProducts)+2; ?>
								<select name="token2[]" id="product_<?=$countValue?>" onchange="$.getValues('<?=$countValue?>')">
								  <option value="" selected="selected">Please select</option>
								  <?php foreach($this->allProducts as $products){ if(!in_array($products['product_id'],$this->crmProducts)){ ?>
								  <option value="<?=$products['product_id']?>"><?=$products['product_name']?></option>
								  <?php } } ?>
								</select>
							</td>
							<td align="left"><input type="text" name="own_unit[]" id="unit_<?=$countValue?>" class="input-medium" onblur="$.getValues('<?=$countValue?>')" pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" /></td>
							<td align="left"><input type="text" name="own_value[]" id="value_<?=$countValue?>" readonly="readonly" class="input-medium" /></td>
						</tr>
						
						<tr class="odd">
                            <td align="left"></td>
							<td align="right"><b>Total Value</b></td>
							<td align="left" valign="top"><input type="text" name="own_tot_val" id="tot_val" readonly="readonly" class="input-medium" value="<?=$roiTotal?>"/></td>
                        </tr>
						  
						  <tr class="odd">
						  	<td align="left" colspan="3"><input type="submit" name="approvalown" id="approvalown" value="Update and Approved" /></td>
						  </tr>
                        </table>
					   </div>
                      </td>
                    </tr>
					<?php } ?>
				</table>
			  </td>
			</tr>
		  </thead>
		</table>
	  </form>
	</div> <!-- End .module-body //$.fillIformation = function(value) {-->
  </div>  <!-- End .module -->
  <div style="clear:both;"></div>
</div>
					   
<script type="text/javascript">
$(document).ready(function() {	
	$.checkapprovalmethod = function(methodtype) {
		if(methodtype == '1') {
			$('#samebedata').show();
			$('#owndata').hide();
		}
		else if(methodtype == '2') {
			$('#samebedata').hide();
			$('#owndata').show();
		}
		else {
			$('#samebedata').hide();
			$('#owndata').hide();
		}
	}
	
	$.getValue = function(iden,prPrice) {
		var token     = $('#product_'+iden).val();
		var unitid    = $('#unit_'+iden).val();
		var unitValue = (parseFloat(unitid)>0) ? unitid : 0;
		var calculateValue = (prPrice*unitValue);
		$('#value_'+iden).val(calculateValue.toFixed(2));
		$.calculateSumValue();
	}
	
	$.getValues = function(iden) {
		var token     = $('#product_'+iden).val();
		var unitid    = $('#unit_'+iden).val();
		var unitValue = (parseFloat(unitid)>0) ? unitid : 0;
		if(token > 0) {
			$.ajax({
				url: '<?=Bootstrap::$baseUrl?>/Ajax/getvalue',
				data: 'token='+token,
				success: function(response) {
					if($.trim(response) != 0) {						
						var calculateValue = (response*unitValue);
						$('#value_'+iden).val(calculateValue.toFixed(2));
						$.calculateSumValue();
					}
					else {
						$('#value_'+iden).val(response);
						$.calculateSumValue();
					}
				}
			});
		}
		else {
			$('#value_'+iden).val('0');
			$.calculateSumValue();
		}
	}
	
	$.calculateSumValue = function() {
		var sum = 0;
         //iterate through each product textboxes and add the values
         $("input[name*='value']").each(function () {
             //add only if the value is number
             if (!isNaN(this.value) && this.value.length != 0) {
                 sum += parseFloat(this.value);
             }
         });
         $('#tot_val').val(sum.toFixed(2));
	}
});
</script>