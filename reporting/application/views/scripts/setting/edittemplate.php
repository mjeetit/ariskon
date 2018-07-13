<div class="grid_12">
<div class="module">
	 <h2><span>Edit Template</span></h2>
	 <div class="module-body">
	   <form name="data_setting" action="" method="post"> 
	   <input type="hidden" name="salary_template_id" value="<?php echo $this->salaryTemplate['salary_template_id']; ?>"   />   
	   <table width="70%" style="border:none">
		<thead>
		  <tr>
			<td align="center" style="border:none">
			 <table style="width:80%">
                    <thead>
					 <tr class="even">
                      <td width="400px">Business Unit</td>
					  <td>
					     <select name="bunit_id" id="bunit_id" onchange="changeStatusBusiness(this.value,'');">
							    <option value="">---Select--</option>
									 <?php foreach($this->ObjModel->getBusinessToCompany() as $bunits){
									   $selected = '';
									   if($this->salaryTemplate['bunit_id']==$bunits['bunit_id']){
									      $selected = 'selected="selected"';
									   }
									  ?>
									  <option value="<?php echo $bunits['bunit_id'];?>" <?php echo $selected;?>><?php echo $bunits['bunit_name']?></option>
									 <?php }?>
							</select>
					    </td>
                        </tr>
					  <tr  class="odd">
                      <td>Department</td>
						 <td>
							<select name="department_id" id="department_id" onchange="changeStatusDepartment(this.value,'');">
								   <option value="">---Select--</option>
								</select>
							</td>
                        </tr>
					<tr class="even">
                      <td>Designation</td>
					  <td><select name="designation_id" id="designation_id">
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
							  <?php
							 if($this->salaryTemplate['salaryhead_id']!=''){
							   $dectedhead = explode(',',$this->salaryTemplate['salaryhead_id']);
							foreach($dectedhead as $head){?>
							   <option value="<?php echo $head?>"><?php echo $this->ObjModel->getSalaryHeadName($head);?></option>
							<?php }}?>
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
							<?php
							 if($this->salaryTemplate['detsalaryhead_id']!=''){
							$dectedhead = explode(',',$this->salaryTemplate['detsalaryhead_id']);
							foreach($dectedhead as $head){?>
							   <option value="<?php echo $head?>"><?php echo $this->ObjModel->getSalaryHeadName($head);?></option>
							<?php }}?>
							</select></td>
                        </tr>
                    </thead>
                </table>
			     <table style="width:80%">
                    <thead>
					<input type="hidden" name="salary_template_id" value="<?php echo $this->salaryTemplate[0]['salary_template_id'];?>" />
					  <tr>
                      <th colspan="2">Salary Amount</th>
					  </tr>
					  <tr class="even"><td colspan="2"><strong>Earning Head</strong></td></tr>
					  <?php $addsalaryheads = explode(',',$this->salaryTemplate['salaryhead_id']);
					  foreach($addsalaryheads as  $addsalaryhead){?>
					   <tr class="odd">
					   <td><?php echo $this->ObjModel->getSalaryHeadName($addsalaryhead);?></td>
					   <td><input type="text" name="amount[]" value="<?php echo $this->salaryTemplateAmount[$addsalaryhead];?>" class="input-short"/></td>
					   </tr>
					  <?php } ?>
					  <?php if($this->salaryTemplate['detsalaryhead_id']!=''){?>
					   <tr class="even"><td colspan="2"><strong>Detection Salary</strong></td></tr>
					  <?php $detectsalaryheads = explode(',',$this->salaryTemplate['detsalaryhead_id']);
					  foreach($detectsalaryheads as  $detectsalaryhead){?>
					   <tr class="odd">
					   <td><?php echo $this->ObjModel->getSalaryHeadName($detectsalaryhead);?></td>
					    <td><input type="text" name="amount[]" value="<?php echo $this->salaryTemplateAmount[$detectsalaryhead];?>" class="input-short"/></td>
					   </tr>
					  <?php } }?>
					  <tr>
						<td colspan="2" align="center"><input type="submit" name="update_template" value="Update Template" class="submit-green" /></td>
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

changeStatusBusiness('<?php echo $this->salaryTemplate['bunit_id']; ?>','<?php echo $this->salaryTemplate['department_id']; ?>');

changeStatusDepartment('<?php echo $this->salaryTemplate['department_id']; ?>','<?php echo $this->salaryTemplate['designation_id']; ?>');



function selectAllOptions(id)

	{

		var ref = document.getElementById(id);

		for(i=0; i<ref.options.length; i++)

			ref.options[i].selected = true;

	}

selectAllOptions('salaryhead_id');

selectAllOptions('detsalaryhead_id');

</script>		   
