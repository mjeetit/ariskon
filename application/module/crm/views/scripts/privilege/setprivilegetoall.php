<style>.container { border:2px solid #ccc; width:300px; height: 108px; overflow-y: scroll; padding-top:5px; }</style>
<div class="grid_12">
	<div class="module">
		<h2><span>User Privilege Lists</span></h2>
		
		<div class="module-table-body">
			<form action="" method="post" name="updateprivilege">
			<table id="myTable" class="tablesorter">
					<tr class="even">
						<td style="width:20%; text-align:left" class="bold_text">Select Designation :</td>
						<td style="width:80%; text-align:left">
						  <select name="design" id="design" style="width:300px;">
							<option value="">-- Select Designation --</option>
							<?php foreach($this->desigs as $desig){
								$select = '';
								if($this->Filterdata['design']==Class_Encryption::encode($desig['designation_id'])){
									$select = 'selected="selected"';
								}
							?>
							<option value="<?=Class_Encryption::encode($desig['designation_id'])?>" <?=$select?>><?=$desig['designation_code'].' - '.$desig['designation_name']?></option>
							<?php } ?>
						  </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  <input type="submit" name="topsave" id="topsave" value="Update" class="submit-green" />
						</td>
					</tr>
				
				 <tr class="odd">
					<td align="left" class="bold_text">Select User :</td>
					<td id="userlist"></td>
				 </tr>
				 <tr class="even">
					<td align="left" class="bold_text">Select Privilege :</td>
					<td id="desigprv"></td>
				 </tr>
				 <tr class="odd">
				 	<td></td>
					<td><input type="submit" name="bottomsave" id="bottomsave" value="Update" class="submit-green" /></td>
				 </tr>
			</table>
			</form>
			<div style="clear: both"></div>
		 </div>
	</div> 
</div>
			
<script type="text/javascript">
$('#design').change(function(){
	var token = $('#design').val();
	$('#userlist').html('');
	$('#desigprv').html('');
	$('#topsave').hide();
	$('#bottomsave').hide();
	if(token != ''){
		$.ajax({
			type: "post",
			url: "<?=Bootstrap::$baseUrl;?>/Privilege/getuser?",
			data: "token="+token,
			
			beforeSend : function() {
				$('#userlist').html("Please wait...");
			},
			success: function(msg){
				if(msg != '') {
					$('#userlist').html(msg);
					
					$.ajax({
						type: "post",
						url: "<?=Bootstrap::$baseUrl;?>/Privilege/getprivilege?",
						data: "token="+token,
						
						beforeSend : function() {
							$('#desigprv').html("Please wait...");
						},
						success: function(prv){
							if(prv != '') {
								$('#desigprv').html(prv);
								$('#topsave').show();
								$('#bottomsave').show();
							}
							else {
								$('#desigprv').html('No privilege set for selected designation!!');
								$('#topsave').hide();
								$('#bottomsave').hide();
							}
						}
					})					
				}
				else {
					$('#userlist').html('No user added yet!!');
					$('#topsave').hide();
					$('#bottomsave').hide();
				}
			}
		});
	}
});
</script>