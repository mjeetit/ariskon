<div class="grid_12">
  <div class="module">
    <h2><span>Edit ROI Data</span></h2>
	<div class="module-body">
	  <form name="data_setting" action="" method="post"> 
	    <table width="70%" style="border:none"> 
		  <thead>
		    <tr>
			 <table width="100%" cellpadding="2" cellspacing="2" border="0">
                          <?php foreach($this->roidetails as $i=>$rois){?>
						  <tr class="odd">
						   <td>
							<input type="hidden" name="roi_id" value="<?=$rois['roi_id']?>"/>
							<?php echo $rois['product_name']?>
							<td>
							<td align="left" valign="top">
							<input type="hidden" name="product_<?=$i?>" id="product_<?=$i?>" value="<?php echo $rois['product_id'];?>" />
						    <input type="text" name="unit[<?php echo $rois['product_id']?>]" id="unit_<?=$i?>" class="input-medium" onblur="$.getValue('<?=$i?>')" required pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  value="<?php echo $rois['unit']?>"/>
						  </td>
						  <td align="left" valign="top"><input type="text" name="value[<?php echo $rois['product_id']?>]" id="value_<?=$i?>" readonly="readonly" class="input-medium"  value="<?php echo $rois['value']?>"/></td>
						  <input type="hidden" name="tot_val" id="tot_val" value="<?php echo $rois['roi_total_value']?>" />
						  
							</tr>
							<?php }?>
						<?php $i++; foreach(array(1,2,3) as $add_prod){?>	
							<tr class="odd">
						   <td>
						   <select name="product[]" id="product_<?=$i?>">
							<?php foreach($this->products as $product){?>
							<option value="<?php echo $product['product_id']?>"><?php echo $product['product_name']?></option>
							<?php }?>
							
							<td>
							<td align="left" valign="top">
							<input type="hidden" name="product_<?=$i?>" id="product_<?=$i?>" value="<?php echo $rois['product_id'];?>" />
						    <input type="text" name="extra_unit[]" id="unit_<?=$i?>" class="input-medium" onblur="$.getValue('<?=$i?>')"  pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  value=""/>
						  </td>
						  <td align="left" valign="top"><input type="text" name="extra_value[]" id="value_<?=$i?>" readonly="readonly" class="input-medium"  value=""/></td>
						  <input type="hidden" name="tot_val" id="tot_val" value="<?php echo $rois['roi_total_value']?>" />
						  
							</tr>
							
						<?php $i++; } ?>	
						<tr><td colspan="2"><input name="submit" type="submit" value="Submit" class="submit-green" /></td></tr>	
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
   $.getValue = function(iden) {
        var token     = $('#product_'+iden).val();
		var unitid    = $('#unit_'+iden).val();
		var unitValue = (parseFloat(unitid)>0) ? unitid : 0;
		if(token > 0) {
			$.ajax({
				url: '<?=Bootstrap::$baseUrl?>/Ajax/getvalue',
				data: 'token='+token,
				success: function(response) { alert(response);
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