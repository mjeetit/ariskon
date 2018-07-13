 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Manual Arrier</span></h2>
                    
                    <div class="module-table-body">
                    	 <form name="form_salary" action="" method="post" id="form1"> 
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<tr>
							 <td>Select Employee &nbsp;<?php  echo $this->htmlSelectUser('user_id',$this->filteruserList,$this->filter['user_id'],array('class'=>'input-medium'));?></td>
							 <td>Arrier Month<input name="date" id="date"  class="input-short"/></td>
							 <td>Arrier Days<input name="salary_days" id="salary_days"  class="input-short"/></td>
							 <td><input type="radio" name="arr_mode" value="0"/>Credit<input type="radio" name="arr_mode" value="1"/>Debit</td>
							  <td><input type="submit" name="submit" class="submit-green" /></td>
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
		$("#date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm"
		});
	});


</script>


	