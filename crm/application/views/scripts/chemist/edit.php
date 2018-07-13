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
					  <td colspan="4" align="left" style="border:none">
					    <a href="<?php echo $this->url(array('controller'=>'Chemist','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr class="even">
					  <td align="left" width="25%">Legacy Code : <?=$this->chemist[0]['legacy_code']?></td>
					  <td align="left" width="25%">
                      	Chemist Name <span class="strick">*</span> :
                        <input type="text" name="chemist_name" id="chemist_name" value="<?=$this->chemist[0]['chemist_name']?>" required aria-required="true" class="input-medium"/>
                      </td>
					  <td align="left" width="25%">
                      	Contact Person : <input type="text" name="contact_person" id="contact_person" value="<?=$this->chemist[0]['contact_person']?>" class="input-medium"/>
                      </td>
					  <td align="left" width="25%">
                      	Class : <input type="text" id="class" name="class" value="<?=$this->chemist[0]['class']?>" class="input-medium">
                      </td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left">
                      	Email : <input type="email" name="email" id="email" value="<?=$this->chemist[0]['email']?>" class="input-medium"/>
                      </td>
					  <td align="left">
                      	Phone : <input type="text" id="phone" name="phone" value="<?=$this->chemist[0]['phone']?>" class="input-medium">
                      </td>
					  <td align="left" colspan="2">
                      	Mobile : <input type="text" id="mobile" name="mobile" value="<?=$this->chemist[0]['mobile']?>" class="input-medium">
                      </td>
					</tr>
					
					<tr class="even">
					  <td align="left">
                      	Headquarter <span class="strick">*</span> :
                        <select name="hqtoken" id="hqtoken" onchange="getNextRecord(this.value,9,'citytoken','');" required aria-required="true">
						  <option value="" selected="selected">Please select</option>
						  <?php foreach($this->headquarters as $hq) { ?>
						  <option value="<?=$hq['headquater_id']?>" <?php if($hq['headquater_id']==$this->chemist[0]['headquater_id']) {?>selected="selected"<?php } ?>><?=$hq['headquater_name']?></option>
						  <?php } ?>
						</select>
					  </td>
					  <td align="left">
                      	City Code <span class="strick">*</span> :
                        <select name="citytoken" id="citytoken" onchange="getNextRecord(this.value,10,'patchtoken','');" required aria-required="true">
                      		<option value="">--Select City--</option>
                        </select>
                      </td>
					  <td align="left" colspan="2">
                      	Patch Code <span class="strick">*</span> :
                        <select name="patchtoken" id="patchtoken" required aria-required="true">
						  <option value="" selected="selected">Please select</option>
						</select>
                      </td>
					</tr>
					
					<tr class="odd">
					  <td align="left">
                      	Address 1 <span class="strick">*</span> :
                        <input type="text" name="address1" id="address1" value="<?=$this->chemist[0]['address1']?>" required aria-required="true" class="input-medium" />
                      </td>
					  <td align="left">
                      	Address 2 : <input type="text" name="address2" id="address2" value="<?=$this->chemist[0]['address2']?>" class="input-medium" />
                      </td>
					  <td align="left" colspan="2">
                      	PIN Code : <input type="text" name="postcode" id="postcode" value="<?=$this->chemist[0]['postcode']?>" class="input-medium" />
                      </td>
					</tr>
					
					<tr class="even">
					  <?php for($i=0;$i<4;$i++) { ?>
                      <td align="left">
                      	Stockist <?=($i+1)?> <?php if($i<2) {?><span class="strick">*</span><?php } ?> :
                        <input type="text" name="stockists[]" id="stockist_<?=$i?>" value="<?=$this->stokist[$i]?>" <?php if($i<2) {?>required<?php } ?> class="input-medium" />
                      </td>
                      <?php } ?>
					</tr>
					
					<tr class="odd">
					  <td style="border:none" colspan="4"><input type="submit" name="chemistUpd" id="chemistUpd" value="Update"></td>
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
	$(function() {
		$.getNextRecord('<?=$this->chemist[0]['headquater_id']?>',9,'citytoken','<?=$this->chemist[0]['city_id']?>');
		$.getNextRecord('<?=$this->chemist[0]['headquater_id']?>',10,'patchtoken','<?=$this->chemist[0]['patch_id']?>');
	});
	
	$.getNextRecord = function(id,level,target,selected){
		$.ajax({
			  url: '<?=Bootstrap::$baseUrl?>Ajax/changestatusrecord',
			  data: 'id='+id+'&level='+level+'&selected='+selected,
			  success: function(data) {
				 $("#"+target).html(data);
			  }
		});
	}
</script>