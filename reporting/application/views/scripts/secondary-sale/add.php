<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
<div class="grid_12">
    <div class="module">
    <h2><span>New Secondary Sale</span></h2>
		<div class="module-body">
			<form name="form_company" action="" method="post"> 
				<table width="70%" style="border:none">
					<thead>
						<tr> 
							<td colspan="12"> 
								<a href="<?php echo $this->url(array('controller'=>'Secondary-Sale','action'=>'index'),'default',true)?>" class="button">
								<span>Back<img src="<?php echo IMAGE_LINK;?>/arrow-180.gif"  width="12" height="9" alt="Add New"/>
								</span>
								</a>
							</td>
						</tr>
						<tr>
							<td align="center" style="border:none">
							<table style=" width:70%">
							<thead>
								<tr><th colspan="2">Add Secondary Sale</th></tr>
								<?php
									if($_SESSION['AdminLoginID']==1)
									{
								?>
								<tr class="odd">
									<td style="border:none">Select BE,ABM,RBM</td>
									<td style="border:none">
										<select class="input-medium" required name="user_id" onchange="getheadquarter(this.value)">
											<option value="">-- Select BE,ABM,RBM --</option>
											<?php 
										foreach($this->users as $users)
										{
									?>
											<option value="<?=Class_Encryption::encode($users['user_id'])?>"><?=$users['name']?></option>
								<?php	} ?>
										</select>
									</td>
								</tr>
						<?php	}	?>
								<tr class="odd">
									<td style="border:none">From</td>
									<td style="border:none"><input type="text" name="from" id="datepicker-1" class="input-medium" placeholder="YYYY-MM-DD"/></td>
								</tr>
								<tr class="even">
									<td style="border:none" valign="top">To</td>
									<td style="border:none"><input type="text" name="to" id="datepicker-2" class="input-medium" placeholder="YYYY-MM-DD"/></td>
								</tr>
							<?php
								if($_SESSION['AdminDesignation'] != 8)
								{
							?>
								<tr class="odd">
									<td style="border:none" valign="top"> Headquarter</td>
									<td style="border:none">
										<select class="input-medium" required name="headquater_id" id="headquater_id">
											<option value="">-- Select Headquarter --</option>
											<?php 
										foreach($this->headquarters as $headquarter)
										{
									?>
											<option value="<?=Class_Encryption::encode($headquarter['headquater_id'])?>"><?=$headquarter['headquater_name']?></option>
								<?php	} ?>
										</select>
									</td>
								</tr>
							<?php } ?>
								<tr>
									<td style="border:none" valign="top">Amount</td>
									<td style="border:none"><input type="text" name="amount" class="input-medium"/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><input type="submit" name="SalesADD" value="Add Sales"  class="submit-green"/></td>
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
 $(function(){
$('#datepicker-1').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
$('#datepicker-2').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
 });
$(function() {
	$("form[name='form_company']").validate({
    // Specify validation rules
    rules:{
		from: "required",
		to: "required",
		amount:"required"
    },
    // Specify validation error messages
    messages: {
		from: "Please select a date",
		to: "Please select a date",
		amount: "Please enter some amount"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});
</script>