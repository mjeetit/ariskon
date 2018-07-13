<form name="form1" action="" method="post"  enctype="multipart/form-data"> 
<table width="100%">

                    <thead>

					<tr>

							<th colspan="2" align="left">Login Detail</th>

						</tr>

						<tr class="odd"> 

							<td width="40px">User Type</td>

							<td> <select name="user_type" id="user_type"  class="input-medium"> 

							   <option value="2" <?php if($this->UserDetail['user_type']==2){ echo 'selected="selected"'; }?>>Employee</option>

							   <option value="3" <?php if($this->UserDetail['user_type']==3){ echo 'selected="selected"';}?>>System Operator</option>

							   <option value="4" <?php if($this->UserDetail['user_type']==4){ echo 'selected="selected"';}?>>Admin</option>

							   <option value="5" <?php if($this->UserDetail['user_type']==5){ echo 'selected="selected"';}?>>Supplier</option>

							   <option value="6" <?php if($this->UserDetail['user_type']==6){ echo 'selected="selected"';}?>>CFA</option>

							   <option value="7" <?php if($this->UserDetail['user_type']==7){ echo 'selected="selected"';}?>>Holesaler</option>

							   </select>

							

						</td> 

						</tr>
						<tr>  

						  <th colspan="2" align="left">Official Detail</th> 
						</tr>

						</tr>  

						 <tr class="odd"> 

							<td width="40px">Business Unit</td> 

							<td width="40px"> 

							<select name="bunit_id" id="bunit_id" onchange="changeStatusBusiness(this.value,'');"  class="input-medium"> 

							    <option value="">---Select--</option>

									 <?php foreach($this->ObjModel->getBusinessToCompany() as $bunits){

									 $selected ='';

									 if($this->UserDetail['bunit_id']==$bunits['bunit_id']){

									   $selected ='selected="selected"';

									 }?>

									  <option value="<?php echo $bunits['bunit_id'];?>" <?php echo $selected;?>><?php echo $bunits['bunit_name']?></option>

									 <?php }?>

							</select>

							</td> 

						</tr> 

					   <tr class="even">

							<td width="40px">Department</td>

							<td width="40px">

								<select name="department_id" id="department_id" onchange="changeStatusDepartment(this.value,'');"  class="input-medium">

								   <option value="">---Select--</option>

								</select>

							</td>

						</tr>

					  <tr class="odd">

							<td width="40px">Designation</td>

							<td width="40px">

								<select name="designation_id" id="designation_id" onchange="changeStatusdesignation(this.value,'');"  class="input-medium">

								    <option value="">---Select--</option>

								</select>

							</td>

						</tr>
						<tr class="odd">

							<td width="40px">Grade</td>

							<td width="40px">

								<select name="grade" id="grade"  class="input-medium">

								    <option value="">---Select--</option>
									<option value="1" <?php if($this->UserDetail['grade']==1){ echo 'selected="selected"';}?>>Grade 1</option>
									<option value="2" <?php if($this->UserDetail['grade']==2){ echo 'selected="selected"';}?>>Grade 2</option>

								</select>

							</td>

						</tr>


						 <tr class="even">

							<td width="40px">Reporting Manager</td>

							<td width="40px">

								<select name="parent_id" id="parent_id"  class="input-medium">

								    <option value="">---Select--</option>

								</select>

							</td>

						</tr>

						<tr class="odd">

							<td width="40px">Reporting Office</td>

							<td width="40px">

								<select name="office_id" id="office_id"  class="input-medium">

								    <option value="">---Select--</option>

								<?php $offices = $this->ObjModel->getOfficeInfo();

								foreach($offices as $office){ 

								$select = '';

								if($office['office_id']==$this->UserDetail['office_id']){

								  $select = 'select="selected"';

								}

								?>

								 <option value="<?php echo $office['office_id']?>" <?php echo $select;?>><?php echo $office['office_name']?></option>

							 <?php	}?>	

								</select>

							</td>

						</tr>
						<tr class="even">

							<td>Official Email</td>

							<td>

							<input type="text" name="email"  class="input-medium" value="<?php echo $this->UserDetail['email'];?>"/>

							</td>

						</tr>


						<tr class="even">

							<td width="40px">Date Of Joining</td>

							<td width="40px">

							<input type="text" name="doj" id="doj" value="<?php echo $this->UserDetail['doj'];?>"  class="input-medium"/>

							</td>

						</tr>

						<tr class="odd">

							<td width="40px">CTC</td>

							<td width="40px">

							<input type="text" name="ctc" value="<?php echo $this->UserDetail['ctc'];?>"  class="input-medium"/>

							</td>

					   </tr>

					   <tr class="even">

							<td width="40px">Provident</td>

							<td width="40px">

							No<input type="radio" name="provident" value="0" <?php if($this->UserDetail['provident']==0){ echo 'checked="checked"';}?> onclick="showprovident(this.value)" />

							Yes<input type="radio" name="provident" value="1" <?php if($this->UserDetail['provident']==1){ echo 'checked="checked"';}?>  onclick="showprovident(this.value)" />

							</td>

					   </tr>
					   
					   <tr id="prov_type" style="display:none" class="odd">
							<td>Provident Type</td>
							<td>

							Provident By Percentage<input type="radio" name="provident_type" value="0" <?php if($this->UserDetail['provident_type']==0){ echo 'checked="checked"';}?>/>

							Provident By Fixed Amount<input type="radio" name="provident_type" value="1" <?php if($this->UserDetail['provident_type']==1){ echo 'checked="checked"';}?> />

							</td>

					   </tr>

					   <tr style="display:none" id="prov_id" class="even">

							<td width="40px">Provident Pecentage</td>

							<td width="40px">

							<input type="text" name="provident_pecentage" value="<?php echo $this->UserDetail['provident_pecentage'];?>"  class="input-medium"/>

							</td>

					   </tr>
					   
					   <tr class="odd">

							<td width="40px">Company Providend Status</td>

							<td width="40px">

							No<input type="radio" name="comp_prov_status" value="0" <?php if($this->UserDetail['comp_prov_status']==0){ echo 'checked="checked"';}?>/>

							Yes<input type="radio" name="comp_prov_status" value="1" <?php if($this->UserDetail['comp_prov_status']==1){ echo 'checked="checked"';}?> />

							</td>

					   </tr> 
					   
					      <tr class="even">

							<td width="40px">Company Provident Pecentage</td>

							<td width="40px">

							<input type="text" name="prov_percentage_comp" value="<?php echo $this->UserDetail['prov_percentage_comp'];?>"  class="input-medium"/>

							</td>

					   </tr>
						 <tr class="even">
							<td>Expense Type</td>
								<td><select name="expense_type" class="input-medium">
								 <option value="1" <?php if($this->UserDetail['expense_type']==1){ echo 'selected="selected"';}?>>Actual Expense</option>
								 <option value="2" <?php if($this->UserDetail['expense_type']==2){ echo 'selected="selected"';}?>>Fixed Expense</option>
								 <option value="3" <?php if($this->UserDetail['expense_type']==3){ echo 'selected="selected"';}?>>Mixed Expense</option>
								</select>
								</td>
							</tr>
						 <tr  class="odd">

							<td>ESI</td>

							<td>

							No<input type="radio" name="esi_status" value="0" <?php if($this->UserDetail['esi_status']==0){ echo 'checked="checked"';}?>/>

							Yes<input type="radio" name="esi_status" value="1" <?php if($this->UserDetail['esi_status']==1){ echo 'checked="checked"';}?>/>

							</td>

					   </tr>
					    <tr class="even">

							<td>ESI Pecentage</td>

							<td>

							<input type="text" name="esi_percentage" id="esi_percentage" value="<?php echo $this->UserDetail['esi_percentage'];?>"  class="input-medium"/>

							</td>

					   </tr>
					   
					    <tr  class="odd">

							<td>Company ESI Status</td>

							<td>

							No<input type="radio" name="comp_esi_status" value="0" <?php if($this->UserDetail['comp_esi_status']==0){ echo 'checked="checked"';}?>/>

							Yes<input type="radio" name="comp_esi_status" value="1" <?php if($this->UserDetail['comp_esi_status']==1){ echo 'checked="checked"';}?>/>

							</td>

					   </tr>
					   
					    <tr class="even">

							<td>Company ESI Percentage</td>

							<td>

							<input type="text" name="esi_percentage_comp" id="esi_percentage_comp" value="<?php echo $this->UserDetail['esi_percentage_comp'];?>"  class="input-medium"/>

							</td>

					   </tr>
							
					     <tr class="odd">

							<td width="40px">PAN Card Number</td>

							<td width="40px">

							<input type="text" name="pancard_number" value="<?php echo $this->UserDetail['AccountDetail']['pancard_number'];?>"   class="input-medium"/>

							</td>

					   </tr>
					   <tr class="even">
							<td>Employee Type</td>
								<td><select name="emp_type" id="emp_type" class="input-medium">
								 <option value="1" <?php if($this->UserDetail['emp_type']==1){ echo 'selected="selected"';}?>>Permanent</option>
								 <option value="2" <?php if($this->UserDetail['emp_type']==2){ echo 'selected="selected"';}?>>Probation</option>
								</select>
								</td>
							</tr>
						 <tr class="even">
							<td>Notice Period</td>
								<td><input type="text" name="notice_period" value="<?php echo $this->UserDetail['notice_period'];?>"  class="input-medium"/></td>
					   </tr>	
					
						<tr>

						<td colspan="2" align="right"><input type="submit" name="officialdetail" value="Update" class="submit-green" /></td>

						</tr>

                    </thead> 

                </table> 
	</form>			