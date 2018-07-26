<div class="grid_12">
	<div class="module">
	<h2><span>New Allowtment</span></h2>
		<div class="module-body">
			<form name="form_company" action="" method="post"> 
				<table width="70%" style="border:none">
					<thead>
						<tr>
							<td align="center" style="border:none">
								<table style="width:70%">
								   	<tr>
								   		<th colspan="2">Add Detail</th>
								   	</tr> 
									<tr>
										<?php echo $this->empAccForm;?>
									</tr>
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
$(function() {
		$("#allowt_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
});

</script>