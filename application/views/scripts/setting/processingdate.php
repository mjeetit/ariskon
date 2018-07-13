 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Salary Processing Duration</span></h2>
                    
                    <div class="module-table-body">
                    <form method="post" action="" name="salary" id="salary"> 
                        <table id="myTable" class="tablesorter">
                            <tbody>
								<tr>
								<td colspan="2" align="center">
							<table style="width:50%">	
								<thead>
                                <tr>
									<th colspan="2">Salary Processing Duration</th>
                                </tr>
                            </thead>
                                <tbody>
								<tr>
								<td>
								From Date<select name="from_date" class="input-short">
								   <?php for($i=1;$i<=31;$i++){
								   $fromselect = '';
								    if($this->Duration['from_date']==$i){
									   $fromselect = 'selected="selected"'; 
									}
								  ?>
								  <option value="<?php echo $i?>" <?php echo $fromselect?>><?php echo $i;?></option>
								  <?php } ?>
								</select>
								</td>
								<td>
								  To Date<select name="to_date" class="input-short">
								  <?php for($i=1;$i<=31;$i++){
								    $todate = '';
								    if($this->Duration['to_date']==$i){
									   $todate = 'selected="selected"'; 
									}
								  ?>
								  <option value="<?php echo $i?>" <?php echo $todate?>><?php echo $i;?></option>
								  <?php } ?>
								</select>
								</td>
								</tr>
								<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Update" class="submit-green" /></td></tr>
                            </tbody>
							</table>
								</td>
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
