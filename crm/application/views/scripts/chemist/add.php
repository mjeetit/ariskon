<div class="grid_12">
  <div class="module">
    <h2><span>Add New </span></h2>
	<div class="module-body">
	  <form name="data_setting" action="" method="post"> 
	    <table width="70%" style="border:none">
		  <thead>
		    <tr>
			  <td align="center" style="border:none">
			    <table style=" width:100%">
				  <thead>
				    <tr class="odd">
					  <td colspan="8" align="left" style="border:none">
					    <a href="<?php echo $this->url(array('controller'=>'Chemist','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr class="even">
					  <td align="left" width="10%">Legacy Code <span class="strick">*</span> :</td>
					  <td align="left" width="15%"><input type="text" name="legacy_code" id="legacy_code" required aria-required="true" class="input-medium" autofocus/></td>
					  <td align="left" width="12%">Chemist Name <span class="strick">*</span> :</td>
					  <td align="left" width="13%"><input type="text" name="chemist_name" id="chemist_name" required aria-required="true" class="input-medium"/></td>
					  <td align="left" width="13%">Contact Person <span class="strick">*</span> :</td>
					  <td align="left" width="12%"><input type="text" name="contact_person" id="contact_person" required aria-required="true" class="input-medium"/></td>
					  <td align="left" width="10%">Class <span class="strick">*</span> :</td>
					  <td align="left" width="15%"><input type="text" id="class" name="class" required aria-required="true" class="input-medium"></td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left">Patch <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="patch" id="patch" required aria-required="true" class="input-medium" /></td>
					  <td align="left">Email <span class="strick">*</span> :</td>
					  <td align="left"><input type="email" name="email" id="email" required aria-required="true" class="input-medium"/></td>
					  <td align="left">Phone <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="phone" name="phone" required aria-required="true" class="input-medium"></td>
					  <td align="left">Mobile <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="mobile" name="mobile" required aria-required="true" class="input-medium"></td>
					</tr>
					
					<tr class="odd">
						<!-- street is replaced by patch by jm on 25072018
						<td align="left">Street Code <span class="strick">*</span> -->
					  	<td align="left">Patch Code <span class="strick">*</span> :</td>
					  	<td align="left">
					    	<select name="street_id" id="streetCode" required aria-required="true">
								<option value="" selected="selected">Please select</option>
								<!-- code modified because we get values from patch instead of street by jm on 25072018 -->
								<?php //foreach($this->streets as $street) { ?>
								  	<!--
								  	<option value="<?=$street['street_id']?>"><?=$street['location_code'].' - '.$street['street_name']?>
								  	</option> -->

								<?php //} ?>

								<?php  foreach($this->patchcodes as $patchcode){ ?>
								  	<option value="<?=$patchcode['patch_id']?>"><?=$patchcode['patchcode'].' - '.$patchcode['patch_name']?>
								  	</option>
								<?php } ?>
							</select>
					  	</td>
					  	
					  	<td align="left">City Code <span class="strick">*</span> :</td>
					  	<td align="left"><input type="text" id="city_name" class="input-medium" readonly="readonly"/></td>
					    <td align="left">Headquarter Code <span class="strick">*</span> :</td>
						<td align="left"><input type="text" id="headquarter_name" class="input-medium" readonly="readonly"/></td>
						<td align="left">BU <span class="strick">*</span> :</td>
						<td align="left"><input type="text" id="bu_name" class="input-medium" readonly="readonly"/></td>
					</tr>
					
					<tr class="event">
					  <td align="left">Address 1 <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="address1" id="address1" required aria-required="true" class="input-medium" /></td>
					  <td align="left">Address 2 <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="address2" id="address2" required aria-required="true" class="input-medium" /></td>
					  <td align="left">PIN Code <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="postcode" id="postcode" required aria-required="true" class="input-medium" /></td>
					  <td align="left"></td>
					  <td align="left"></td>
					</tr>
					
					<tr class="event">
					  <td align="left">Stockist 1 <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="stockists[]" id="stockist_1" required aria-required="true" class="input-medium" /></td>
					  <td align="left">Stockist 2 <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="stockists[]" id="stockist_2" required aria-required="true" class="input-medium" /></td>
					  <td align="left">Stockist 3 <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="stockists[]" id="stockist_3" class="input-medium" /></td>
					  <td align="left">Stockist 4 <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="stockists[]" id="stockist_4" class="input-medium" /></td>
					</tr>
					
					<tr class="event">
					  <td style="border:none" colspan="8"><input type="submit" name="chemistAdd" id="chemistAdd" value="Add Chemist"></td>
					</tr>
				  </thead>
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
	$('#streetCode').change(function() {//alert(this.value);return false;
		if(this.value > 0) {

			var url = '<?=Bootstrap::$baseUrl?>/Ajax/getlocation';

			$.ajax({
				url: url,
				data: 'token='+this.value,
				success: function(response) {
					if($.trim(response) != 0) {

						var details = response.split('^'); 
						
						$("#city_name").val(details[0]);
						$("#headquarter_name").val(details[1]);
						$("#bu_name").val(details[6])
					}
					else {
						$("#city_name").val('');
						$("#headquarter_name").val('');
						$("#bu_name").val('');
					}
				}
			});
		}
		else {
			$("#city_name").val('');
			$("#headquarter_name").val('');
			$("#bu_name").val('');
		}
	});
});
</script>