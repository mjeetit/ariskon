 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Delete Employee</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<tr><td colspan="2">This Employee May Reporting Authority. So Please Assign Another Athority Here</td></tr>
                             
					<tr class="odd">

					<td>Selete Authority</td>
					<td><select name="parent_id">
					<option value="">--Select--</option>
					<?php foreach($this->parentlist as $parents){?>
					   <option value="<?php echo $parents['user_id']?>"><?php echo $parents['first_name'].' '.$parents['last_name'].'('.$parents['designation_code'].')' ?></option>
					   <?php }?>
					</select></td></tr>
					<tr><td>Reasion of Delete</td><td><textarea name="reasion"></textarea></td></tr>
					<tr><td colspan="2" align="center"><input type="submit" name="delete" class="submit-green" value="Delete" /></td>
					

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
	