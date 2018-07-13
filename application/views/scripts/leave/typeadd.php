<div class="grid_12">
                <div class="module">
                     <h2><span>Add New Leave Type Detail</span></h2>
                     <div class="module-body">
                       <form name="data_setting" action="" method="post"> 
					   <table width="70%" style="border:none">
						<thead>
						  <tr>
							<td align="center" style="border:none">
								<table style=" width:70%">
									<thead>
									<tr>
										<td colspan="2" align="left" style="border:none">
									<a href="<?php echo $this->url(array('controller'=>'Leave','action'=>'typelist'),'default',true)?>" class="button back">
											<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
											</a>
											</td>
										</tr>
										<tr>
										<th colspan="2">Add New Type</th>
										</tr>
										<tr class="odd">
										  <td>Leave Type Name<span class="strick">*</span> :</td>
										  <td><input type="text" name="typeName" id="typeName" class="input-medium"/></td>
										</tr>
								<tr class="even">
									 <td style="border:none">Leave Type Description :</td>
									<td style="border:none">
									<textarea name="typeDesc" id="typeDesc" rows="5" cols="38"></textarea>
									</td>
								</tr>
								
								<tr class="odd">
									 <td style="border:none">Credit Period in Year :</td>
									<td style="border:none">
										<select name="creditPeriod" id="creditPeriod" class="input-medium">

                        	<option value="Quarterly">Quarterly</option>

                            <option value="Half Yearly" selected="selected">Half Yearly</option>

                            <option value="Annualy">Annualy</option>

                        </select>
									</td>
								</tr>
						<tr class="even">
									 <td style="border:none">Carry Forward to Next Year :</td>
									<td style="border:none">
									<input type="radio" name="carryForward" id="carryForward" value="1" onclick="javascript:$.ShowHideCarry(1);" /> Yes
                        			<input type="radio" name="carryForward" id="carryForward" value="0" onclick="javascript:$.ShowHideCarry(0);" checked="checked" /> No
									</td>
								</tr>

								<tr class="even" id="carryRow">
									 <td style="border:none">Total Leave for Carry Forward :</td>
									<td style="border:none"><input type="text" name="carryforward_number" id="carryforward_number" class="input-small" /></td>
								</tr>

								<tr class="even">
									<td style="border:none">Encashment Setting :</td>
									<td style="border:none">
									<input type="radio" name="encashment_status" id="encashment_status" value="1" onclick="javascript:$.ShowHideEncashment(1);" /> Yes

                        <input type="radio" name="encashment_status" id="encashment_status" value="0" onclick="javascript:$.ShowHideEncashment(0);" checked="checked" /> No
									</td>
								</tr>

								<tr class="even" id="encashmentRow">
									 <td style="border:none">Total Leave for Encashment :</td>
									<td style="border:none"><input type="text" name="encashment_number" id="encashment_number" class="input-small" /></td>
								</tr>

					 <tr>
						<td colspan="2" align="center"><input type="submit" name="<?php echo $this->type;?>" value="ADD Type" class="submit-green" /></td>
						</tr>	
				</thead>
			</table>
		</td>
	</tr>
</thead>
</table>
			</form>
		 </div> <!-- End .module-body -->

	</div>  <!-- End .module -->
	<div style="clear:both;"></div>
</div>
					   
<script type="text/javascript">
$(document).ready(function() {
	$("#carryRow").hide();
	$("#encashmentRow").hide();

	$.ShowHideCarry = function(value) {
		if(value==1) {
			$("#carryRow").show();
		}
		else {
			$("#carryRow").hide();
		}
	}

	$.ShowHideEncashment = function(value) {
		if(value==1) {
			$("#encashmentRow").show();
		}
		else {
			$("#encashmentRow").hide();
		}
	}
});
</script>