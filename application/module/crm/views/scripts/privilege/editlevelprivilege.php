<div class="grid_12">
	<div class="module">
		<h2><span>Designation Wise Default Privilege Lists</span></h2>
		
		<div class="module-table-body">
			<form action="" method="post" name="updateprivilege">
			<table id="myTable" class="tablesorter">
				<thead>							
					<tr>
						<th style="width:20%; text-align:left">Designation Name</td>
						<th style="width:70%; text-align:left">Default Privilege</th>
						<th style="width:10%; text-align:left"><input type="submit" name="topsave" value="Update" class="submit-green" /></th>
					</tr>
				</thead>
				
				<tbody>
				 <tr>
					<td></td>
					<td colspan="2"><?=$this->viewData?></td>
				 </tr>
				 <tr>
				 	<td></td>
					<td colspan="2"><input type="submit" name="bottomsave" value="Update" class="submit-green" /></td>
				 </tr>
				</tbody>
			</table>
			</form>
			<div style="clear: both"></div>
		 </div>
	</div> 
</div>
			
<script type="text/javascript">
$.ShowModule = function(moduleID) {
	if($('#module'+moduleID).is(':checked')) {
		$('#sub'+moduleID).show();
	}
	else {
		$('#sub'+moduleID).hide();
		$('#sub'+moduleID).find('input[type=checkbox]:checked').removeAttr('checked');
	}
}

$.ShowAction = function(moduleID) {
	if($('#module'+moduleID).is(':checked')) {
		$('#action'+moduleID).show();
	}
	else {
		$('#action'+moduleID).hide();
	}
}
</script>	