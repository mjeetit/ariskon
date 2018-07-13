<style>
div.forwardButton input {
    background:url(<?=IMAGE_LINK?>/skip_forward.png) no-repeat;
    cursor:pointer;
    width: 40px;
    height: 40px;
    border: none;
}
div.backButton input {
    background:url(<?=IMAGE_LINK?>/skip_backward.png) no-repeat;
    cursor:pointer;
    width: 40px;
    height: 40px;
    border: none;
}
</style>

<div class="grid_12">
  <div class="module">
    <h2><span>Edit </span></h2>
	<div class="module-body">
	  <form name="editForm" id="editForm" action="" method="post"> 
	    <table width="70%" style="border:none">
		  <thead>
		    <tr>
			  <td align="center" style="border:none">
			    <table style=" width:100%">
				  <thead>
				    <tr class="odd">
					  <td colspan="4" align="left" style="border:none">
					    <a href="<?php echo $this->url(array('controller'=>'Stockist','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr class="even">
					  <td align="left" width="10%" class="bold_text">Stockist Name <span class="strick">*</span> :</td>
					  <td align="left" width="20%"><textarea name="name" rows="5" cols="25" required><?=$this->detail['stockist_name']?></textarea></td>
					  <td align="left" width="10%" class="bold_text">Address <span class="strick">*</span> :</td>
					  <td align="left" width="60%"><textarea name="address" rows="5" cols="25" required><?=$this->detail['address']?></textarea></td>
					</tr>
					
					<tr class="odd">
					  <td colspan="4" class="bold_text">Select Headquarter</td>
					</tr>
					
					<tr class="even" style="width:100%">
					<td colspan="2" valign="top"><?php $addedHQ = explode(',',$this->detail['hq']); ?>
					 <select id="boxa" multiple="multiple" size="15" style="width:100%;">
					  <?php foreach($this->headquarters as $key=>$headquarter) { if(!in_array($headquarter['headquater_id'],$addedHQ)) { ?>
					  <option value="<?=$headquarter['headquater_id']?>"><?=$headquarter['headquater_name']?></option>
					  <?php } } ?>
					 </select>
					</td>
					<td style="width:10%; vertical-align:middle;" align="center"> 
					 <div class="forwardButton"><input type="button" onclick="javascript:$.addvalue();"></div><br /><br />
					 <div class="backButton"><input type="button" onclick="javascript:$.removevalue();"></div><br />
					</td>
					<td valign="top" style="width:40%; height:100%"> 
					 <select name="hq[]" id="boxb" multiple="multiple" size="15" style="width:100%;" required>
					 <?php foreach($this->headquarters as $key=>$headquarter) { if(in_array($headquarter['headquater_id'],$addedHQ)) { ?>
					  <option value="<?=$headquarter['headquater_id']?>" selected="selected"><?=$headquarter['headquater_name']?></option>
					  <?php } } ?>
					 </select>
					</td>
				   </tr>
					
					<tr class="event">
					  <td style="border:none" colspan="4">
					    <input type="submit" name="addaction" id="addaction" value="Update" />
						<span id="prdError" style="color:#FF0000; font-weight:bold;"></span>
					  </td>
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

<script type="text/javascript" language="javascript">
	$.addvalue = function() {
		!$('#boxa option:selected').remove().appendTo('#boxb');
	}
	
	$.removevalue = function() {
		!$('#boxb option:selected').remove().appendTo('#boxa');
		$('#boxb option').prop('selected', true);
	}
	
	$.listproduct = function() {
		var prdValues = $('#boxb').val();
		var prdNames  = $('#boxb').text(); //alert(prdValues.length);return false;
		if(prdValues.length >=1) {
			$('#prdError').html("Please wait...");
			$('#addForm').submit();
		}
		else {
			$('#drpotential').html('');
			$('#boxa').css('border-color','#FF0000');
			$('#boxb').css('border-color','#FF0000');
			$('#prdError').html('Please select atleast one headquarter !!');			
		}
	}
</script>