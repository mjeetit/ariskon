 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Cheque Detail</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<tr><td colspan="2">Please Fill correct Chque Number</td></tr>
                             
					<tr class="odd">

					<td>Cheque Number</td>
					<td>
					<input type="hidden" name="cheque_id" value="<?php echo $this->chequenumber['cheque_id']?>"/>
					<input type="text" name="cheque_number" class="input-medium"  value="<?php echo $this->chequenumber['cheque_number']?>"/></td>
					<tr><td colspan="2" align="center"><input type="submit" name="delete" class="submit-green" value="Save" /></td></tr>

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
	