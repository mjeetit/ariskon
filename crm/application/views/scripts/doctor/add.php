<div class="grid_12">
  <div class="module">
    <h2><span>Add New Doctor</span></h2>
	<div class="module-body">
	  <form name="addform" id="addform" action="" method="post"> 
	    <table width="100%" style="border:none">
		  <thead>
		    <tr>
			  <td align="center" style="border:none">
			    <table style=" width:100%">
				  <thead>
				    <tr class="odd">
					  <td colspan="8" align="left" style="border:none">
					    <a href="<?php echo $this->url(array('controller'=>'Doctor','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr class="even">
					  <td align="left" width="15%" class="bold_text">Doctor Name <span class="strick">*</span> :</td>
					  <td align="left" width="20%"><input type="text" name="doctor_name" id="doctor_name" required aria-required="true" class="input-medium" /></td>
					  <td align="left" width="15%" class="bold_text">Email <span class="strick">*</span> :</td>
					  <td align="left" width="20%"><input type="email" id="email" name="email" class="input-medium" /></td>
					  <td align="left" width="15%" class="bold_text">Gender <span class="strick">*</span> :</td>
					  <td align="left" width="15%">
					    <input type="radio" name="gender" id="gender" value="M" checked="checked" /> Male <input type="radio" name="gender" id="gender" value="F" /> Female
					  </td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left" class="bold_text">Speciality <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="speciality" id="speciality" class="input-medium" /></td>
					  <td align="left" class="bold_text">Class <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="class" id="class" class="input-medium"/></td>
					  <td align="left" class="bold_text">Qualification <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="qualification" name="qualification" class="input-medium"></td>					  
					</tr>
                    
                    <tr class="even">					  
					  <td align="left" class="bold_text">Date of Birth <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="dob" name="dob" class="input-medium"></td>
					  <td align="left" class="bold_text">Phone <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="phone" name="phone" class="input-medium"></td>
					  <td align="left" class="bold_text">Mobile <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="mobile" name="mobile" class="input-medium"></td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left" class="bold_text">Visit Frequency <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="visit_frequency" name="visit_frequency" class="input-medium"></td>
					  <td align="left" class="bold_text">Best Day to Meet <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="meeting_day" name="meeting_day" class="input-medium"></td>
					  <td align="left" class="bold_text">Best Time to Meet <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="meeting_time" name="meeting_time" class="input-medium"></td>
					</tr>
					
					<tr class="even">
					  <td align="left" class="bold_text">Address 1 <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="address1" id="address1" class="input-medium" /></td>
					  <td align="left" class="bold_text">Address 2 :</td>
					  <td align="left"><input type="text" name="address2" id="address2" class="input-medium"/></td>
					  <td align="left" class="bold_text">PIN Code <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" id="postcode" name="postcode" class="input-medium"></td>
					</tr>
					
					<tr class="odd">
					  <td align="left" class="bold_text">Headquarter <span class="strick">*</span> :</td>
					  <td align="left">
					    <select name="hqtoken" id="hqtoken" style="width:120px;" onchange="$.getlocation(this.value);" required aria-required="true">
							<option value="">-- Select Headquarter --</option>
							<?php foreach($this->headquarters as $headquarter){?>
							<option value="<?=Class_Encryption::encode($headquarter['headquater_id'])?>" <?=$select?>><?=$headquarter['headquater_name']?></option>
							<?php } ?>
					    </select>
					  </td>
					  <td align="left" class="bold_text">Patchcode <span class="strick">*</span> :</td>
					  <td align="left">
					    <select name="patchtoken" id="patchtoken" style="width:120px;" onchange="$.patchlocation(this.value);" required aria-required="true">
						  <option value="">Select Patch</option>
						</select>
					  </td>
					  <td align="left" class="bold_text">City :</td>
					  <td align="left" id="city_name"></td>
					</tr>
					
					<tr class="even">
					  <td align="left" class="bold_text">Area :</td>
					  <td align="left" id="area_name"></td>
					  <td align="left" class="bold_text">Zone :</td>
					  <td align="left" id="zone_name"></td>
					  <td align="left" class="bold_text">Region :</td>
					  <td align="left" id="region_name"></td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left" colspan="2" class="bold_text">Chemist (Choosse 2 Chemist Name) <span class="strick">*</span> :</td>
					  <td align="left" colspan="4" id="chemistdetails"></td>
					</tr>
					
					<tr class="even">
					  <td style="border:none" colspan="6"><input type="submit" name="doctorAdd" id="doctorAdd" value="ADD NEW"></td>
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
	var mvp = 2;
	$('#chemistdetails').html('');
	
	$.getlocation = function(token) {
		$("#patchtoken > option").remove(); //alert(token);return false;
		
		if(token != '') {
			$.ajax({
				url: '<?=Bootstrap::$baseUrl?>/Ajax/getpatch',
				data: 'token='+token,
				success: function(response) {//alert(response);return false;
					if($.trim(response) != 0) {
						$.each(response[0],function(token,name) {
							var opt = $('<option />');
							opt.val(token);
							opt.text(name);							
							$('#patchtoken').append(opt);
						});
						
						var pid = "<?=Class_Encryption::encode($this->info['patch_id'])?>";
						$('#patchtoken option[value="' + pid + '"]').prop('selected', true);
					}
					else {
						var opt = $('<option />');
						opt.val('');
						opt.text('Select Patch');				
						$('#patchtoken').append(opt);
					}
				}
			});
		}
		else {
			var opt = $('<option />');
			opt.val('');
			opt.text('Select Patch');				
			$('#patchtoken').append(opt);
		}
		
		$("#city_name").html('');
		$("#area_name").html('');
		$("#region_name").html('');
		$("#zone_name").html('');
		$('#chemistdetails').html('');	
	}
	
	$.patchlocation = function(token) {
		if(token != '') {
			$.ajax({
				url: '<?=Bootstrap::$baseUrl?>/Ajax/patch',
				data: 'token='+token,
				success: function(response) {//alert(response);return false;
					if($.trim(response) != 0) {
						$("#city_name").html(response['cname']);
						$("#area_name").html(response['aname']);
						$("#region_name").html(response['rname']);
						$("#zone_name").html(response['zname']);
					}
					else {
						$("#city_name").html('');
						$("#area_name").html('');
						$("#region_name").html('');
						$("#zone_name").html('');
					}
				}
			});
			
			$.ajax({
				url: '<?=Bootstrap::$baseUrl?>/Doctor/showchemist',
				data: 'ptoken='+token,
				success: function(response) {//alert(response);return false;
					if($.trim(response) != '') {
						$('#chemistdetails').html(response);
					}
					else {
						$('#chemistdetails').html('');
					}
				}
			});
		}
		else {
			$("#city_name").html('');
			$("#area_name").html('');
			$("#region_name").html('');
			$("#zone_name").html('');
			$('#chemistdetails').html('');
		}	
	}
	
	$("input:checkbox").each(function (index) {
        this.checked = (index < mvp);
    }).click(function () {
        en_dis= $("input:checkbox:checked").length < mvp
        $('#button-id').prop('disabled',en_dis);
    }).change(function () {
        if ($(this).siblings(':checked').length >= mvp) {
            this.checked = false;
        }
    });
});
</script>