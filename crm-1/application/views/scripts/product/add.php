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
					    <a href="<?php echo $this->url(array('controller'=>'Product','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr class="even">
					  <td align="left" width="16%">Product Name <span class="strick">*</span> :</td>
					  <td align="left" width="16%"><input type="text" name="product_name" id="product_name" required aria-required="true" class="input-medium" autofocus/></td>
					  <td align="left" width="16%">Measurement <span class="strick">*</span> :</td>
					  <td align="left" width="16%">
                      	<select name="pack_type" id="pack_type" required aria-required="true">
						  <option value="" selected="selected">Please select</option>
						  <?php foreach($this->types as $type) { ?>
						  <option value="<?=$type['pack_type']?>"><?=$type['type_name']?></option>
						  <?php } ?>
						</select>
                      </td>
					  <td align="left" width="16%">MRP Incl Vat <span class="strick">*</span> :</td>
					  <td align="left" width="20%"><input type="text" name="mrp_incl_vat" id="mrp_incl_vat" required aria-required="true" class="input-medium"/></td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left">Stockiest Price (Ex. VAT) <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="stockist_excl_vat" id="stockist_excl_vat" required aria-required="true" class="input-medium" /></td>
					  <td align="left">Retailer Price (Ex. VAT) <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="retailer_excl_vat" id="retailer_excl_vat" required aria-required="true" class="input-medium"/></td>
					  <td align="left">VAT Charged <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="vat_charged" name="vat_charged" required aria-required="true" class="input-medium"></td>
					</tr>
					
					<tr class="event">
					  <td style="border:none" colspan="6"><input type="submit" name="productAdd" id="chemistAdd" value="Add Product"></td>
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