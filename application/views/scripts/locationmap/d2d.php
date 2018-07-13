<div class="grid_12">
                <div class="module">
                     <h2><span>New <?php echo ucfirst($this->back);?></span></h2>
                     <div class="module-body">
                       <form name="data_setting" action="" method="post"> 
					   <table width="70%" style="border:none">
						<thead>
						  <tr>
							<td align="center" style="border:none">
								<table style=" width:70%">
									<thead>
									<tr>
										<td colspan="2" align="left" style="border:none">
											<a href="<?php echo $this->url(array('controller'=>'Locationmap','action'=>'desigtodepart'),'default',true)?>" class="button back">
											<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
											</a>
											</td>
										</tr>
										<tr>
										<th colspan="2">Add <?php echo ucfirst($this->back);?></th>
						    </tr>
							<tr class="even">
								  <td style="border:none">Designation</td>
										<td style="border:none">
											<select name="designation_id">
												 <option value="">--Select--</option>
												 <?php $designations =  $this->ObjModel->getDesignation();
												foreach($designations as  $designation){?>
												<option value="<?php echo  $designation['designation_id']; ?>"><?php echo  $designation['designation_name']; ?></option>
												<?php } ?>
												</select>
										</td>
							</tr>
							 <tr class="odd">
								  <td style="border:none" valign="top">Department</td>
								  <td style="border:none">
										<select name="department_id">
									 <option value="">--Select--</option>
									 <?php $departments =  $this->ObjModel->getDepartment();
									foreach($departments as  $department){?>
									<option value="<?php echo  $department['department_id']; ?>"><?php echo  $department['department_name']; ?></option>
									<?php } ?>
									</select>
						  </td>
					</tr>
					 <tr>
						<td colspan="2" align="center"><input type="submit" name="d2d" value="Add"  class="submit-green" /></td>
						</tr>	
				</thead>
			</table>
		</td>
	</tr>
</thead>
</table>
	</form>
 </div> <!-- End .module-body -->

</div>  <!-- End .module -->
<div style="clear:both;"></div>
</div>					   
