<div class="grid_12">
  <div class="module">
    <h2><span>Add ROI Data</span></h2>
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

						<tr class="even">
						  <td width="33%" align="left"><b>Product</b></td>
						  <td width="33%" align="left"><b>Unit</b></td>
						  <td width="33%" align="left"><b>Value</b></td>
						</tr>                       

						<?php 
						foreach($this->allProducts as $i=>$product)
						{
							if(in_array($product['product_id'],$this->crmProducts))
							{
							   $trClass = ($i%2==0) ? 'odd' : 'even';
						?>

						<tr class="<?=$trClass?>">
                          <td align="left" valign="top"><?=$product['product_name']?></td>                          
                          <td align="left" valign="top">
						  	<input type="hidden" name="token2[]" value="<?php echo $product['product_id'];//Class_Encryption::encode($product['product_id'])?>" />
						    <input type="text" name="unit[]" id="unit_<?=$i?>" class="input-medium" onblur="$.getValue('<?=$i?>','<?=(isset($this->productPrice[$product['product_id']])) ? $this->productPrice[$product['product_id']] : 0?>')" required pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
						  </td>
						  <td align="left" valign="top"><input type="text" name="value[]" id="value_<?=$i?>" readonly="readonly" class="input-medium" /></td>
                        </tr>
						<?php } } ?>
			
						<tr class="odd">
                            <td align="left"><!--<a href="javascript:void(1);" onclick="$.checkapprovalmethod('1')">Add 2 More Products</a>--></td>
							<td align="right"><b>Total Value</b></td>
							<td align="left" valign="top"><input type="text" name="tot_val" id="tot_val" readonly="readonly" class="input-medium"/></td>
                        </tr>
						
						<tr class="event">
					  		<td style="border:none" colspan="3"><input type="submit" name="roiADD" value="Add ROI"></td>
						</tr>
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
	$.getValue = function(iden,prPrice) {//alert(prPrice);return false;
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