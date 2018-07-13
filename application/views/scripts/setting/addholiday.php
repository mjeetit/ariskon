 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Holidays</span></h2>
                    
                    <div class="module-table-body">
                    <form method="post" action="" name="salary" id="salary"> 
                        <table id="myTable" class="tablesorter">
                            <tbody>
								<tr>
								<td colspan="2" align="center">
							<table style="width:50%">	
								<thead>
                                <tr>
									<th colspan="3">Holiday</th>
                                </tr>
                            </thead>
                                <tbody>
								<tr>
								<td>Region</td>
								<td>
								<select name="region_id">
								<option value="0">For All Region</option>
								<?php foreach($this->Region as $region){
								  $selected = '';
								  if($region['region_id']==$this->submitdata['region_id']){
								 	 $selected = 'selected="selected"';
								   }
								  ?>
								  <option value="<?php echo $region['region_id']?>" <?php echo $selected;?>><?php echo $region['region_name']?></option>
								<?php } ?>
								</select>
								</td></tr>
							   <tr class="even">
								<td>Month</td>
								<td><select name="months">
								<option value="">--Select Month--</option>
								<?php for ($m=1; $m<=12; $m++) { 
								  
								  $month = date('F Y', mktime(0,0,0,$m, 1, date('Y')));
								  $year_month = date('m', mktime(0,0,0,$m, 1, date('Y'))).','.date('Y', mktime(0,0,0,$m, 1, date('Y')));
								  $selected = '';
								  if($year_month==$this->submitdata['months']){
								 	 $selected = 'selected="selected"';
								   }
								  ?>
								 <option value="<?php echo $year_month?>" <?php echo $selected;?>><?php echo $month?></option>
								 <?php }?>
								</select>
								</td><td colspan="2" align="center"><input type="submit" name="submit" value="Add" class="submit-green" /></td>
								<?php if(!empty($this->submitdata['months'])){ 
									$exploded_date = explode(',',$this->submitdata['months']);
									$month = $exploded_date[0];
									$year = $exploded_date[1];
								?>
								 <tr><td colspan="3"><table><?php 
									for($d=1; $d<=31; $d++)
									{
										$class = ($d%2==0)?'even':'odd';
										$time=mktime(12, 0, 0, $month, $d, $year);          
										if (date('m', $time)==$month)
										 $checked = '';
										 $day_name = '';
										 $date = date('Y-m-d', $time);
										 $day_name = (isset($this->hilidayslist[$date]))?$this->hilidayslist[$date]['day']:'';
										 if(((date('l', $time)=='Saturday' || date('l', $time)=='Sunday') && empty($this->hilidayslist)) || isset($this->hilidayslist[$date])){
										   $checked = 'checked="checked"';
										   $day_name = (isset($this->hilidayslist[$date]))?$this->hilidayslist[$date]['holiday_name']:date('l', $time);
										 }	
										?>
										<tr class="<?php echo  $class;?>"><td><input type="checkbox" name="holidays[<?php echo $date?>]" <?php echo $checked;?> /></td><td><?php echo date('l(d-m-Y)', $time)?></td><td><input type="text" class="input-medium" name="holiday_name[<?php echo $date?>]" value="<?php echo $day_name;?>" /><input type="hidden" name="day[<?php echo $date?>]" value="<?php echo date('l', $time);?>" /></td></tr>	       
								<?php	} ?>
								<tr><td colspan="2" align="center"><input type="submit" name="addholiday" value="Add" class="submit-green" /></td></tr>
								</table></td></tr>	
								<?php } ?>
								</tr>
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
