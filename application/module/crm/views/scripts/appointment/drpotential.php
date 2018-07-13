<div id="productPotentialDiv1" style="width:1165px; overflow-x:auto;overflow-y:none;margin-left:-4% !important">
	<table cellpadding="2" cellspacing="2" border="0" style="width:<?=$this->tableWidth; ?>px">
	  <tr class="even">
		<td align="center" colspan="19"><b>Potential of Dr for JCL Products</b></td>
	  </tr>
	  
	  <tr class="odd">
		<td align="center" valign="middle" rowspan="3" width="5%" class="bold_text">Products</td>
		<td colspan="6" align="center" class="bold_text">Last 3 Months</td>
		<td colspan="12" align="center" class="bold_text">Expected 6 Months</td>
	  </tr>
	  
	  <tr class="even">
		<?php $monthYearArray = array(); for($p=1,$i=2;$p<4;$p++,$i--) { ?>
		<td align="center" colspan="2" class="bold_text">
			<?php $monthYearPrev = date("M Y",mktime(0,0,0,date('m')-$i,date('d'),date('Y'))); 
			$PreviousMonthYear	= date("Y-m-01",strtotime(str_replace(' ','',$monthYearPrev)));
			$monthYearArray[] 	= $PreviousMonthYear;
			echo $monthYearPrev;
			?>
			<input type="hidden" name="month[]" id="month_<?=$p?>" value="<?=$PreviousMonthYear?>"/></b>
		</td>
	  <?php }  for($i=1,$p=4;$i<7;$i++,$p++) { $trClass = ($i%2==0) ? 'even' : 'odd'; ?>
		<td align="center" colspan="2"><b>
			<?php $monthYearNext = date("M Y",mktime(0,0,0,date('m')+$i,date('d'),date('Y'))); 
			$ExpectedMonthYear = date("Y-m-01",strtotime(str_replace(' ','',$monthYearNext)));
			array_push($monthYearArray,$ExpectedMonthYear);
			echo $monthYearNext;
			?>
			<input type="hidden" name="month[]" id="month_<?=$p?>" value="<?=$ExpectedMonthYear?>"/>
		</td>
		<?php } ?>
	  </tr>
	  
	  <tr class="odd">
		<?php foreach($monthYearArray as $monthYear) { ?>
		<td align="center" width="5%">Unit</td>
		<td align="center" width="5%">Value</td>
		<?php } ?>
	  </tr>
	  
	  <?php 
		foreach($this->products as $i=>$product)
		{
			$trClass = ($i%2==0) ? 'even' : 'odd';
			if(in_array($product['product_id'],$this->showproduct)) {
	  ?>

	  <tr class="<?=$trClass?>">
		<td align="left" valign="top"><b><?=$product['product_name']?></b></td>
		<input type="hidden" name="token[]" value="<?=Class_Encryption::encode($product['product_id'])?>" />
		<?php foreach($monthYearArray as $monthYear) { ?>
		<td align="center">
			<input type="text" name="unit_<?=$monthYear?>[]" id="unit_<?=$monthYear?>_<?=$i?>" class="input-medium" onblur="$.getValue('<?=$i?>','<?=$monthYear?>','<?=(isset($this->productPrice[$product['product_id']])) ? $this->productPrice[$product['product_id']] : 0?>')" required pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
			<!--<input type="hidden" name="token_<?=$monthYear?>[]" value="<?=Class_Encryption::encode($product['product_id'])?>" />-->
		</td>
		<td align="center"><input type="text" name="value_<?=$monthYear?>[]" id="value_<?=$monthYear?>_<?=$i?>" readonly="readonly" class="input-medium" /></td>
		<?php } ?>
	  </tr>
	  <?php } } ?>
	  
	  <tr class="odd">
		<td align="right"><b>Total</b></td>
		<?php foreach($monthYearArray as $monthYear) { ?>
		<!--<td align="center"><b><?=date("M Y",strtotime($monthYear))?></b></td>-->
		<td align="right" colspan="2"><input type="text" name="tot_val_<?=$monthYear?>" id="tot_val_<?=$monthYear?>" readonly="readonly" class="input-medium"/></td>
		<?php } ?>
	  </tr>
	  
	  <tr class="even">
		<td align="right"><b>Last 3 Months</b></td>
		<td align="right" colspan="2"><input type="text" name="tot_val_last" id="tot_val_last" readonly="readonly" class="input-medium"/></td>
		<td align="right" colspan="2"><b>Expected 6 Months</b></td>
		<td align="left" colspan="14"><input type="text" name="tot_val_expect" id="tot_val_expect" readonly="readonly" class="input-medium" size="20" /></td>
	  </tr>
	  
	  <tr class="odd">
		<td align="right"><b>Total CRM</b></td>
		<td align="left" colspan="18"><input type="text" name="tot_val_crm" id="tot_val_crm" readonly="readonly" class="input-medium" size="25" /></td>
	  </tr>
	
	<!--<tr class="even">
	  <td align="left">Chemist-1</td>
	  <td align="left" colspan="4"><input type="text" id="chcode1" class="input-medium" readonly="readonly"/></td>
	  <td align="left" colspan="2">Contact Person</td>
	  <td align="left" colspan="4"><input type="text" id="chname1" class="input-medium" readonly="readonly"/></td>
	  <td align="left" colspan="2">Tel No.</td>
	  <td align="left" colspan="6"><input type="text" id="chphone1" class="input-medium" readonly="readonly"/></td>
	</tr>
	
	<tr class="odd">
	  <td align="left">Chemist-2</td>
	  <td align="left" colspan="4"><input type="text" id="chcode2" class="input-medium" readonly="readonly"/></td>
	  <td align="left" colspan="2">Contact Person</td>
	  <td align="left" colspan="4"><input type="text" id="chname2" class="input-medium" readonly="readonly"/></td>
	  <td align="left" colspan="2">Tel No.</td>
	  <td align="left" colspan="6"><input type="text" id="chphone2" class="input-medium" readonly="readonly"/></td>
	</tr>-->
  </table>
  </div>