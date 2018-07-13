 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Employee CRM Privileges</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
                        <table id="myTable" class="tablesorter">
                        	<tbody>
								<tr class="odd">
									<td><?php echo $this->privilegecrm;?></td>
                   				</tr>
								<tr class="even" align="left">
									<td><input type="submit" name="addpriv" value="Save Privilege" class="submit-green" /></td>
                   				</tr>
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
 
        </div>
        <div class="clear"></div>
    </div>
	
	<script type="text/javascript">
	$.ShowModule = function(moduleID) {
			if($('#module'+moduleID).is(':checked')) {
				$('#sub'+moduleID).show();
			}
			else {
				$('#sub'+moduleID).hide();
				$('#sub'+moduleID).find('input[type=checkbox]:checked').removeAttr('checked');
				//$('#sub'+moduleID).find('input[type=checkbox]:checked').remove();
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