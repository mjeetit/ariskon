	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Settings</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $this->appusers[0]['id']?>" />
                        <table id="myTable" class="tablesorter">
                        	<thead>
						<tr>
						<td colspan="2" align="center">
						<table style="width:60%">
						<tr class="even">
							<td class="bold_text">Employee Code: <?php echo $this->appusers[0]['employee_code']?></td>
							
							<td class="bold_text">Emplyee Name: <?php echo $this->appusers[0]['first_name'].' '.$this->appusers[0]['last_name']?></td>
							
							</tr>
						  <tr  class="odd">
							<td>App Lock</td>
							<?php
							$al = ($this->appusers[0]['reporing_lock']=='1')?'checked="checked"':'';
							$al1 = ($this->appusers[0]['reporing_lock']=='0')?'checked="checked"':'';
							?>
							<td><input type="radio" name="reporing_lock" value="1" <?php echo $al?> />Yes&nbsp;<input type="radio" name="reporing_lock" value="0" <?php echo $al1?>/>No</td>
						  </tr>
						  <tr class="even">
							<td>Emei Check</td>
							<?php
							$ec = ($this->appusers[0]['emei_check']=='1')?'checked="checked"':'';
							$ec1 = ($this->appusers[0]['emei_check']=='0')?'checked="checked"':'';
							?>
							<td><input type="radio" name="emei_check" value="1" <?php echo $ec?>/>Yes&nbsp;<input type="radio" name="emei_check" value="0" <?php echo $ec1?>/>No</td>
						  </tr>
						  <tr class="odd">
						  <?php
							$as = ($this->appusers[0]['auto_sync']=='1')?'checked="checked"':'';
							$as1 = ($this->appusers[0]['auto_sync']=='0')?'checked="checked"':'';
							?>
							<td>Auto Sync</td>
							<td><input type="radio" name="auto_sync" value="1" <?php echo $as?>/>Yes&nbsp;<input type="radio" name="auto_sync" value="0" <?php echo $as1?>/>No</td>
						  </tr>
						  <tr class="even">
							<td>TP Validation</td>
							<?php
							$tv = ($this->appusers[0]['tp_check']=='1')?'checked="checked"':'';
							$tv1 = ($this->appusers[0]['tp_check']=='0')?'checked="checked"':'';
							?>
							<td><input type="radio" name="tp_check" value="1" <?php echo $tv?>/>Yes&nbsp;<input type="radio" name="tp_check" value="0" <?php echo $tv1?>/>No</td>
						  </tr>
						  <tr class="odd">
							<td>Check Lock Condition</td>
							<?php
							$la = ($this->appusers[0]['lock_action']=='1')?'checked="checked"':'';
							$la1 = ($this->appusers[0]['lock_action']=='0')?'checked="checked"':'';
							?>
							<td><input type="radio" name="lock_action" value="1" <?php echo $la?>/>Yes&nbsp;<input type="radio" name="lock_action" value="0" <?php echo $la1?>/>No</td>
						  </tr>
						  <tr class="even">
							<td>Check Report Validation</td>
							<?php
							$crv = ($this->appusers[0]['check_validation']=='1')?'checked="checked"':'';
							$crv1 = ($this->appusers[0]['check_validation']=='0')?'checked="checked"':'';
							?>
							<td><input type="radio" name="check_validation" value="1" <?php echo $crv;?> />Yes&nbsp;<input type="radio" name="check_validation" value="0" <?php echo $crv1;?> />No</td>
						  </tr>
						  <tr class="odd">
							<td>Auto Sync Options</td>
							<td><input type="checkbox" name="sync_options[]" value="1" <?php echo (in_array('1',explode(',',$this->appusers[0]['sync_options'])))?'checked="checked"':''?> />User list&nbsp;
							<input type="checkbox" name="sync_options[]" value="2" <?php echo (in_array('2',explode(',',$this->appusers[0]['sync_options'])))?'checked="checked"':''?>/>Meeting Type&nbsp;
							<input type="checkbox" name="sync_options[]" value="3" <?php echo (in_array('3',explode(',',$this->appusers[0]['sync_options'])))?'checked="checked"':''?>/>Headquaters&nbsp;
							<input type="checkbox" name="sync_options[]" value="4" <?php echo (in_array('4',explode(',',$this->appusers[0]['sync_options'])))?'checked="checked"':''?>/>PatchList&nbsp;</td>
						  </tr>
						  <tr class="even">
							<td>Auto Lock In</td>
							<td><input type="text" name="lock_limit" value="<?php echo $this->appusers[0]['lock_limit'];?>" />Days</td>
						  </tr>
						  <tr class="odd">
							<td colspan="2" align="center"><input type="submit" name="update" value="Update" class="submit-green" /> &nbsp;<a href="<?php echo $this->url(array('controller'=>'Appsettings','action'=>'appsettings'),'default',true)?>"><input type="button" class="submit-green" name="back" value="Back" /></a></td>
						  </tr>
						</table>

						</td>
						</tr>
                            </thead>
                        </table>
                       
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 --> 
