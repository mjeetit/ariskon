 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Master ESI Setting</span></h2>
                    
                    <div class="module-table-body">
                    <form method="post" action="" name="salary" id="salary"> 
                        <table id="myTable" class="tablesorter">
                            <tbody>
								<tr>
								<td colspan="2" align="center">
							<table style="width:50%">	
								<thead>
                                <tr>
									<th colspan="2">ESI Setting</th>
                                </tr>
                            </thead>
                                <tbody>
								
							   <tr class="even">
								<td>ESI Status</td>
								<td>ON&nbsp;<input type="radio" name="esi_status" value="1"  <?php if($this->esisett['esi_status']=='1'){ echo 'checked="checked"';}?>/>&nbsp;OFF&nbsp;<input type="radio" name="esi_status" value="0" <?php if($this->esisett['esi_status']=='0'){ echo 'checked="checked"';}?> /></td>
								</tr>
								<tr class="odd">
								<td>ESI Added By Company</td>
								<td>By Percentage<input type="radio" name="esi_type" value="0"  <?php if($this->esisett['esi_type']=='0'){ echo 'checked="checked"';}?>/>By Fixed Amount<input type="radio" name="esi_type" value="1"   <?php if($this->esisett['esi_type']=='1'){ echo 'checked="checked"';}?>/></td>
								</tr>
								<tr class="even">
								<td>ESI Value By Company</td>
								<td><input type="text" name="comapny_esi_value"  class="input-short" value="<?php echo $this->esisett['comapny_esi_value'];?>"/></td>
								</tr>
								<tr class="odd">
								<td>ESI Calculate On</td>
								<td>
								<select name="esi_on" class="input-short">
								<option value="0">--Select Salary Head</option>
								  <?php foreach($this->ObjModel->getSalaryhead() as $head){
								  $selected = '';
								  if($this->esisett['esi_on']==$head['salaryhead_id']){
								    $selected = 'selected="selected"';
								  }
								  ?>
								  <option value="<?php echo $head['salaryhead_id']?>" <?php echo $selected;?>><?php echo $head['salary_title']?></option>
								  <?php } ?>
								</select> +
								<select name="extra_esi_on" class="input-short">
								<option value="0">--Select Salary Head</option>
								   <?php foreach($this->ObjModel->getSalaryhead() as $head){
								     $selected = '';
									  if($this->esisett['extra_esi_on']==$head['salaryhead_id']){
										$selected = 'selected="selected"';
									  }
								   ?>
								  <option value="<?php echo $head['salaryhead_id']?>" <?php echo $selected;?>><?php echo $head['salary_title']?></option>
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
