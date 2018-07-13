<div class="grid_12">
<div class="module">
	 <h2><span>Add Template</span></h2>
	 <div class="module-body">
	   <form name="data_setting" action="" method="post"> 
	   <table width="70%" style="border:none">
		<thead>
		  <tr>
			<td align="center" style="border:none">
			<?php if(empty($this->templatehead)){?>
					<table style="width:80%">
                    <thead>
					    <tr class="even"><td>Business Unit</td><td>
					     <select name="bunit_id" id="bunit_id" onchange="changeStatusBusiness(this.value);">
							    <option value="">---Select--</option>
									 <?php foreach($this->ObjModel->getBusinessToCompany() as $bunits){?>
									  <option value="<?php echo $bunits['bunit_id'];?>"><?php echo $bunits['bunit_name']?></option>
									 <?php }?>
							</select>
					    </td>
                        </tr>
					  <tr class="odd">
                      <td>Department</td>
						    <td>
							<select name="department_id" id="department_id" onchange="changeStatusDepartment(this.value);">
								   <option value="">---Select--</option>
								</select>
							</td>
                        </tr>
					<tr class="even">
                      <td>Designation</td>
						    <td>
							<select name="designation_id" id="designation_id" onchange="changeStatusdesignation(this.value);">
								    <option value="">---Select--</option>
								</select>
							</td>
                        </tr>
					<tr class="odd">
                       <td valign="top">Earning Salary Head:<select name="salaryhead" id="salaryhead" multiple="multiple">
					         <?php foreach($this->ObjModel->getSalaryhead() as $salaryhead){?>
									  <option value="<?php echo $salaryhead['salaryhead_id'];?>"><?php echo $salaryhead['salary_title']?></option>
									 <?php }?>
							</select>
							&nbsp;<input type="button" onclick="removeseletion();" name="rem" value="<<"  style="width:30px;"/>

							&nbsp;<input type="button" onclick="addseletion();" name="but" value=">>"  style="width:30px;"/></td>	

						    <td valign="top"><select name="salaryhead_id[]" id="salaryhead_id" multiple="multiple">

							</select></td>

                        </tr>

					<tr class="even">

                       <td valign="top">Deduction Salary Head:<select name="salaryheadDet" id="salaryheadDet" multiple="multiple">

					         <?php foreach($this->ObjModel->getDetectionSalaryhead() as $salaryhead){?>

									  <option value="<?php echo $salaryhead['salaryhead_id'];?>"><?php echo $salaryhead['salary_title']?></option>

									 <?php }?>

							</select>

							&nbsp;<input type="button" onclick="removeseletionDet();" name="rem" value="<<"  style="width:30px;"/>

							&nbsp;<input type="button" onclick="addseletionDet();" name="but" value=">>"  style="width:30px;"/></td>	

						    <td valign="top"><select name="detsalaryhead_id[]" id="detsalaryhead_id" multiple="multiple">

							</select></td>

                        </tr>

						<tr class="odd">

						<td colspan="2" align="center"><input type="submit" name="add_template" value="Add Template" class="button add" /></td>

						</tr>

                    </thead>

                </table>
			<?php }else{?>
			        <table style="width:80%">

                    <thead>

					<input type="hidden" name="salary_template_id" value="<?php echo $this->templatehead['salary_template_id'];?>" />

					  <tr>

                      <th colspan="2">Salary Amount</th>

					  </tr>

					  <tr class="even"><td colspan="2">Addition Salary</td></tr>

					  <?php $addsalaryheads = explode(',',$this->templatehead['salaryhead_id']);

					  foreach($addsalaryheads as  $addsalaryhead){?>

					  <input type="hidden" name="salaryhead_id[]" value="<?php echo $addsalaryhead;?>" />

					   <tr class="odd">

					   <td><?php echo $this->ObjModel->getSalaryHeadName($addsalaryhead);?></td>

					   <td><input type="text" name="amount[]" /></td>

					   </tr>

					  <?php } ?>

					  <?php if($this->templatehead['detsalaryhead_id']!=''){?>

					   <tr class="even"><td colspan="2"><strong>Deduction Salary</strong></td></tr>

					  <?php $detectsalaryheads = explode(',',$this->templatehead['detsalaryhead_id']);

					  foreach($detectsalaryheads as  $detectsalaryhead){?>

					    <input type="hidden" name="salaryhead_id[]" value="<?php echo $detectsalaryhead;?>" />

					   <tr class="odd">

					   <td><?php echo $this->ObjModel->getSalaryHeadName($detectsalaryhead);?></td>

					    <td><input type="text" name="amount[]"/></td>

					   </tr>

					  <?php } }?>

					  <tr class="even">

					  <td colspan="2">

					  <input type="submit" name="add_amount" value="Add Amount" class="button add" />

					  </td>

					  </tr>

					  </thead>

					  </table>
			<?php }?>
		</td>
	</tr>
</thead>
</table>
</form>
</div> <!-- End .module-body -->

</div>  <!-- End .module -->
<div style="clear:both;"></div>
</div>
<script type="text/javascript">

function addseletion(){ 

   $('#salaryhead option:selected').appendTo('#salaryhead_id');

} 

function removeseletion(){ 

   $('#salaryhead_id option:selected').appendTo('#salaryhead');

}

function addseletionDet(){ 

   $('#salaryheadDet option:selected').appendTo('#detsalaryhead_id');

} 

function removeseletionDet(){ 

   $('#detsalaryhead_id option:selected').appendTo('#salaryheadDet');

}  

</script>		   
