 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Manual Arrier</span></h2>
                    
                    <div class="module-table-body">
                    	 <form name="form_salary" action="" method="post" id="form1"> 
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<?php for($i=0;$i<5;$i++){?>
							<tr>
							 <td>Select Employee &nbsp;<?php  echo $this->htmlSelectUser(array('user_id'),$this->filteruserList,$this->filter['user_id'],array('class'=>'input-medium'));?></td>
							 <td>Arrier Month<input name="date[]" id="date<?php echo $i;?>"  class="input-short"/></td>
							 <td>Arrier Days<input name="salary_days[]" id="salary_days"  class="input-short"/></td>
						      </tr>
							 <?php }?> 
							  <tr>
							  <td colspan="3" align="right"><input type="submit" name="submit" class="submit-green" /></td>
						      </tr>
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div>

<script type="text/javascript">
$(function() {
		$("#date0").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm"
		});
		$("#date1").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm"
		});
		$("#date2").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm"
		});
		$("#date3").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm"
		});
		$("#date4").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm"
		});
	});


</script>


	