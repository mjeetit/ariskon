<div class="grid_12">
                <div class="module">
                     <h2><span>New User</span></h2>
                     <div class="module-body">
                        <form name="form_company" action="" method="post"> 
						<table width="70%" style="border:none">
							<thead>
							 <tr>
							 <td colspan="2">
							 <a href="<?php echo $this->url(array('controller'=>'Users','action'=>'user'),'default',true)?>" class="button">
							 <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();" onclick="showform('1');" class="button"><span>Official Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('2');" class="button">
							  <span>Personal Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('3');" class="button">
							  <span>Education Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('4');" class="button">
							  <span>Employeement Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('5');" class="button">
							  <span>Salary Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('6');providentcalculate();" class="button">
							  <span>Documents Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Documents Detail" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('7');leavedetail('');" class="button">
							  <span>Leave Approval<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Leave Approval" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('8');" class="button">
							  <span>Bank Account<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Bank Account" /></span>
                        </a>
						<a href="javascript:void();"  onclick="showform('9');" class="button">
							  <span>Location<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Location" /></span>
                        </a>
						<a href="javascript:void();"  onclick="showform('10');getexpensetemplate('');" class="button">
							  <span>Expense<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Location" /></span>
                        </a>
						</td>
							 </tr>
						<tr>
						<td colspan="2" align="center" style="border:none"> 
						
			  <!--Official Detail-->

                 <table width="100%" id="table1">
                    <thead>
					<tr>
							<th colspan="2" align="left">Login Detail</th>
						</tr>
						<tr class="even"> 

							<td>User Type</td>

							<td> <select name="user_type" id="user_type"  class="input-medium"> 

							   <option value="2">Employee</option>

							   <option value="3">System Operator</option>

							   <option value="4">Admin</option>

							   <option value="5">Supplier</option>

							   <option value="6">CFA</option>

							   <option value="7">Holesaler</option>

							   </select>

								

						</td> 

						</tr>  

						<tr class="odd">   

							<td>Username</td> 

							<td> <input type="text" name="username" id="username" class="input-medium"/></td> 

						</tr> 

						<tr class="even"> 

							<td>Password</td> 

							<td><input type="password" name="password" id="password" class="input-medium"/></td> 

						</tr> 

						<tr class="odd"> 

							<td>Confirm Password</td> 

							<td><input type="password" name="confirm_pass" id="confirm_pass" class="input-medium"/></td> 

						</tr> 

					  <tr class="even"> 

							<th colspan="2" align="left">Official Detail</th> 

						</tr> 

						 <tr class="odd"> 

							<td>Business Unit</td> 

							<td> 

							<select name="bunit_id" id="bunit_id" onchange="changeStatusBusiness(this.value,'');getNextRecord(this.value,5,'zone_id','');"  class="input-medium"> 

							    <option value="">---Select--</option>

									 <?php foreach($this->ObjModel->getBusinessToCompany() as $bunits){?>

									  <option value="<?php echo $bunits['bunit_id'];?>"><?php echo $bunits['bunit_name']?></option>

									 <?php }?>

							</select>

							</td> 

						</tr> 

					   <tr class="even">

							<td>Department</td>

							<td>

								<select name="department_id" id="department_id" onchange="changeStatusDepartment(this.value,'');"  class="input-medium">

								   <option value="">---Select--</option>

								</select>

							</td>

						</tr>

					  <tr class="odd">

							<td>Designation</td>

							<td>

								<select name="designation_id" id="designation_id" onchange="changeStatusdesignation(this.value,'');salarytemplate('');leavedetail('');"  class="input-medium">

								    <option value="">---Select--</option>

								</select>

							</td>

						</tr>
						<tr class="odd">

							<td width="40px">Sub-Designation</td>

							<td width="40px">

								<select name="sub_designation_id" id="sub_designation_id"  class="input-medium">

								    <option value="">---Select--</option>

								</select>

							</td>

						</tr>

						 <tr class="even">

							<td>Reporting Manager</td>

							<td>

								<select name="parent_id" id="parent_id"  class="input-medium">

								    <option value="">---Select--</option>

								</select>

							</td>

						</tr>

						<tr class="odd">

							<td>Reporting Office</td>

							<td>

								<select name="office_id" id="office_id"  class="input-medium">

								    <option value="">---Select--</option>

								<?php $offices = $this->ObjModel->getOfficeInfo();

								foreach($offices as $office){ ?>

								 <option value="<?php echo $office['office_id']?>"><?php echo $office['office_name']?></option>

							 <?php	}?>	

								</select>

							</td>

						</tr>

						<tr class="even">

							<td>Official Email</td>

							<td>

							<input type="text" name="email"  class="input-medium"/>

							</td>

						</tr>

						<tr class="odd">

							<td>Password</td>

							<td>

							<input type="password" name="email_password"  class="input-medium"/>

							</td>

						</tr>

						<tr class="even">

							<td>Date Of Joining</td>

							<td>

							<input type="text" name="doj" id="doj"  class="input-medium"/>

							</td>

						</tr>

						<tr class="odd">

							<td>CTC</td>

							<td>

							<input type="text" name="ctc"  id="ctc" class="input-medium"/>

							</td>

					   </tr>

					   <tr  class="even">

							<td>Provident</td>

							<td>

							No<input type="radio" name="provident" value="0" onclick="showprovident(this.value)"/>

							Yes<input type="radio" name="provident" value="1"  onclick="showprovident(this.value)"/>

							</td>

					   </tr>
					   <tr class="odd" id="prov_type" style="display:none">
							<td>Provident Type</td>
							<td>

							Provident By Percentage<input type="radio" name="provident_type" value="0"/>

							Provident By Fixed Amount<input type="radio" name="provident_type" value="1" />

							</td>

					   </tr>

					   <tr class="even" style="display:none" id="prov_id">

							<td>Provident Pecentage</td>

							<td>

							<input type="text" name="provident_pecentage" id="provident_pecentage"  class="input-medium"/>

							</td>

					   </tr>
					   
					    <tr class="odd">

							<td width="40px">Company Provident Pecentage</td>

							<td width="40px">

							<input type="text" name="prov_percentage_comp"  class="input-medium"/>

							</td>

					   </tr>
					   
					    <tr class="even">
							<td>Expense Type</td>
								<td><select name="expense_type" class="input-medium">
								 <option value="1">Actual Expense</option>
								 <option value="2">Fixed Expense</option>
								 <option value="3">Mixed Expense</option>
								</select>
								</td>
							</tr>
						 <tr  class="odd">

							<td>ESI</td>

							<td>

							No<input type="radio" name="esi_status" value="0"/>

							Yes<input type="radio" name="esi_status" value="1"/>

							</td>

					   </tr>
					    <tr class="even">

							<td>ESI Pecentage</td>

							<td>

							<input type="text" name="esi_percentage" id="esi_percentage"  class="input-medium"/>

							</td>

					   </tr>
					   
					   <tr class="even">

							<td>Company ESI Pecentage</td>

							<td>

							<input type="text" name="esi_percentage_comp" id="esi_percentage_comp"   class="input-medium"/>

							</td>

					   </tr>
					   
					     <tr  class="odd">

							<td>PAN Card Number</td>

							<td>

							<input type="text" name="pancard_number"  class="input-medium"/>

							</td>

					   </tr>
					  <tr class="even">
							<td>Employee Type</td>
								<td><select name="emp_type" id="emp_type" class="input-medium">
								 <option value="1">Permanent</option>
								 <option value="2">Probation</option>
								</select>
								</td>
							</tr>
						 <tr class="even">
							<td>Notice Period</td>
								<td><input type="text" name="notice_period"  class="input-medium"/></td>
					   </tr>	


						<tr class="even">

						<td colspan="2" align="right"><input type="submit" name="officialdetail" value="Update"  class="submit-green"/></td>

						</tr>

                    </thead>

                </table> 

			   <!--Personal Detail-->

                 <table width="100%" id="table2">

                    <thead>

						 <tr>

							<th colspan="2" align="left">Personal Detail</th>

						</tr>

						 <tr  class="even">

							<td>First Name</td>

							<td><input type="text" name="first_name" id="first_name" class="input-medium"/></td>

						</tr>

						<tr  class="odd">

							<td>Last Name</td>

							<td><input type="text" name="last_name" id="last_name" class="input-medium"/></td>

						</tr>

						 <tr class="even">

							<td>Father's Name</td>

							<td>

								<input type="text" name="father_name"  class="input-medium"/>

							</td>

						</tr>

						 <tr  class="odd">

							<td>Mother's Name</td>

							<td>

								<input type="text" name="mother_name"  class="input-medium"/>

							</td>

						</tr>

						 <tr class="even">

							<td>Date Of Birth</td>

							<td>

								<input type="text" name="dob" id="dob"  class="input-medium"/>

							</td>

						</tr>

						 <tr  class="odd">

							<td>Gender</td>

							<td>

								Male<input type="radio" name="sex" id="sex" value="Male"  checked="checked"/>Female<input type="radio" name="sex" id="sex" value="Female"/>

							</td>

						 </tr>
						 <tr class="even">

							<td>Blood Group</td>

							<td>

								<input type="text" name="blood_group"  class="input-medium" />

							</td>

						 </tr>
						 <tr class="odd">

							<td>Personal Email</td>

							<td>

								<input type="text" name="personal_email"  class="input-medium"/>

							</td>

						</tr>
						
						 <tr class="even">

						<td>Contact Number</td>

							<td>

								<input type="text" name="contact_number"  class="input-medium"/>

							</td>

						</tr>

 						<tr class="odd">

						<td>Emergency Contact</td>

							<td>

								<input type="text" name="emergency_contact"  class="input-medium"/>

							</td>

						</tr>
					   <tr>

						<th colspan="2" align="left">Corespondance Address</th>

					   </tr>

					    <tr class="even">

							<td>Address</td>

							<td>

								<input type="text" name="cores_address"  class="input-medium"/>

							</td>

						</tr>

						<tr class="odd">

							<td>City</td>

							<td>

								<input type="text" name="cores_city"  class="input-medium"/>

							</td>

						</tr>

						<tr class="even">

							<td>State</td>

							<td>

								<input type="text" name="cores_state"  class="input-medium"/>

							</td>

						</tr>

					   <tr class="odd">

							<td>Post Code</td>

							<td>

								<input type="text" name="cores_postalcode"  class="input-medium"/>

							</td>

					  </tr>	

					  <tr class="even">

							<td>Country</td>

							<td>

								<input type="text" name="cores_country"  class="input-medium"/>

							</td>

					  </tr>

					   <tr class="odd">

						<th colspan="2" align="left">Permanent Address</th>

					   </tr>

					    <tr class="even">

							<td>Address</td>

							<td>

								<input type="text" name="permanent_address"  class="input-medium"/>

							</td>

						</tr>

						<tr class="odd">

							<td>City</td>

							<td>

								<input type="text" name="permanent_city"  class="input-medium"/>

							</td>

						</tr>

						<tr class="even">

							<td>State</td>

							<td>

								<input type="text" name="permanent_state"  class="input-medium"/>

							</td>

						</tr>

					   <tr class="odd">

							<td>Post Code</td>

							<td>

								<input type="text" name="permanent_postalcode"  class="input-medium"/>

							</td>

					  </tr>	

					  <tr class="even">

							<td>Country</td>

							<td>

								<input type="text" name="permanent_country"  class="input-medium"/>

							</td>

					  </tr>

					   <tr>

					    <td colspan="2" align="right">

						 <input type="submit" name="personal_detail"  value="Update" class="submit-green"/>

						 </td>

					  </tr>			

                    </thead>

                </table> 

			   <!--Education Detail-->	

				 <table width="100%" id="table3">

                    <thead>

					<tr>

					 <th colspan="6" align="left">Education Detail</th>

						</tr>

						<tr class="odd" > 

						<td align="left"><b>Degree</b></td><td align="left"><b>Degree Name</b></td><td align="left"><b>School/collage</b></td><td><b>Board/University</b></td><td><b>Percentage Mark/CGPA</b></td><td><b>Year of Passing</b></td>

						</tr> 

						<?php $degrees = array('10 th','10+2','Graduation','Post Graduation'); 

						foreach($degrees as $key=>$degree){
						$class = ($key%2==0)?'even':'odd';?>
								<tr class="<?php echo  $class;?>">

						<td align="left"><input type="text" name="degree[]" id="degree" value="<?php echo $degree;?>" readonly="readonly" style="width:80px" class="input-medium"/></td> 

						<td align="left"> <input type="text" name="degree_name[]" id="degree_name" class="input-medium"/></td>

						<td align="left"> <input type="text" name="collage[]" id="collage" class="input-medium"/></td>

						<td align="left"> <input type="text" name="board[]" id="board" class="input-medium"/></td>

						 <td align="left"> <input type="text" name="per_mark[]" id="per_mark" class="input-medium"/></td> 

						 <td align="left" id="aftetd"> <input type="text" name="year_passing[]" id="year_passing" class="input-medium"/></td> 

						</tr> 

						<?php }?>

					   <tr id="beforetr">

							<td>&nbsp;</td>

							<td align="right">

								<input type="button" name="adduser" value="Add More" onclick="addeducation();" class="input-medium"/>

							</td>

							<td align="right" colspan="4">

								<input type="submit" name="adduser" value="Update" class="submit-green"/>

							</td>

						</tr>

                    </thead>

                </table> 

			  <!--Employeement Detail-->	

				 <table width="100%" id="table4">

                    <thead>

					<tr>

					 <th colspan="7" align="left">Employeemnet Detail</th>

						</tr>

						<tr class="odd"> 

						<td align="left"><b>Comapny</b></td><td align="left"><b>Designation</b></td><td align="left"><b>From</b></td><td align="left"><b>To</b></td><td><b>Joinig CTC</b></td><td><b>Leaving CTC</b></td><td><b>Reasion Of Leaving</b></td>

						</tr> 

						<?php for($i=0;$i<3;$i++){
						$class = ($i%2==0)?'even':'odd';?>
						<tr class="<?php echo  $class;?>">

						<td align="left"><input type="text" name="company[]" id="company" style="width:100px" class="input-medium"/></td> 

						<td align="left"> <input type="text" name="designation[]" id="designation" class="input-medium"/></td>

						<td width="200px" align="left"><input type="text" name="from_year[]" id="from_year" style="width:40px" class="input-medium"/>yy<input type="text" name="from_month[]" id="from_month" style="width:40px" class="input-medium"/>mm</td>

						<td width="200px" align="left"><input type="text" name="to_year[]" id="to_year" style="width:40px" class="input-medium"/>yy<input type="text" name="to_month[]" id="to_month" style="width:40px" class="input-medium"/>mm</td>

						<td align="left"> <input type="text" name="joining_ctc[]" id="joining_ctc" style="width:100px" class="input-medium"/></td>

						 <td align="left"> <input type="text" name="leaving_ctc[]" id="leaving_ctc" style="width:100px" class="input-medium"/></td> 

						 <td align="left" id="aftetd"> <input type="text" name="reasion_of_leaving[]" id="reasion_of_leaving" class="input-medium"/></td> 

						</tr> 

						<?php }?>

					   <tr id="beforecomp">

							<td>&nbsp;</td>

							<td align="right">

								<input type="button" name="adduser" value="Add More" onclick="addcompany();" class="input-medium"/>

							</td>

							<td align="right" colspan="5">

								<input type="submit" name="adduser" value="Update" class="submit-green"/>

							</td>

						</tr>

                    </thead>

                </table>  

			   <!--Salary  Detail-->	

				 <table width="100%" id="table5">

                    <thead>

					<tr id="afterid">

					 <th colspan="2" align="left">Salary Detail</th>

					</tr>
					<tr><td colspan="2">Credit Type<input type="radio" value="0" name="heade_type" onclick="salarytemplate('');" checked="checked" />Fixed<input type="radio" value="1" name="heade_type"  onclick="salarytemplate('');" />By Percentage</td></tr>
					<tr>

					<td colspan="2">

					<table id="template"></table>

					</td>

					</tr>
					
					<tr>
					<td><strong>Extra Earning Head</strong></td>
					<td><select id="" onchange="extrahead(this.value,1,'earnigtable');">
					 <option value="">--Select Head--</option>
					 <?php foreach($this->ObjModel->getSalaryhead() as $earning){ ?>
					   <option value="<?php echo $earning['salaryhead_id'];?>"><?php echo $earning['salary_title'];?></option>   
					 <?php } ?>
					</select></td>
					</tr>
						
					<tr>
						<td colspan="2">
						<table id="earnigtable"></table>
						</td>
						</tr>
						
					<tr>
					<td><strong>Extra Deduction Head</strong></td>
					<td><select id="" onchange="extrahead(this.value,2,'deducttable');">
					 <option value="">--Select Head--</option>
					 <?php foreach($this->ObjModel->getDetectionSalaryhead() as $deduction){ ?>
					   <option value="<?php echo $deduction['salaryhead_id'];?>"><?php echo $deduction['salary_title'];?></option>   
					 <?php } ?>
					</select></td>
					</tr>
					
					<tr>
						<td colspan="2">
						<table id="deducttable"></table>
						</td>
						</tr>
						
					   <tr>

							<td>&nbsp;</td>

							<td align="right">

								<input type="submit" name="adduser" value="Update" class="submit-green"/>

							</td>

						</tr>

                    </thead>

                </table> 

			   <!--Document  Detail-->	

				  <table width="100%" id="table6">

                    <thead>

					<tr>

					 <th colspan="2" align="left">Document Detail</th>

					</tr>

					<tr>

						<td colspan="2" align="center">

								<table id="document">

								<tr class="odd">

								<td>Documents Type
 
								<select name="document_type[]">

								<option value="">--Select Category--</option>

								<?php $doctypes = $this->ObjModel->getDocumentsType();

								foreach($doctypes as $doc){

								?>

								<option value="<?php echo $doc['type_id']?>"><?php echo $doc['type_name']?></option>

								<?php } ?>

								</select>

								</td>

								<td><input type="file" name="documnet[]"  class="input-medium"/></td>

								</tr>

								</table>

						 </td>

					</tr>

				   <tr>	

							<td><input type="button" name="add_doc" value="Add More" onclick="documnet_add()"/></td>

							<td align="right">

								<input type="submit" name="adduser" value="Update" class="submit-green"/>

							</td>

					</tr>

                    </thead>

                </table>		

			   <!--Leave Approval-->

			    <table width="100%" id="table7">

                    <thead>

					<tr>

					 <th colspan="2" align="left">Leave Detail</th>

					</tr>

					<tr>

					<td colspan="2" align="center">

					 <table id="leavedetail">

					 </table>

					</td>

					</tr>

					<tr>

						<td align="right">

							<input type="submit" name="adduser" value="Update" class="submit-green"/>

						</td>

					</tr>

                    </thead>

                </table>

				 <!--Account Detail -->

			    <table width="100%" id="table8">

                    <thead>

					<tr>

					 <th colspan="2" align="left">Account Detail</th>

					</tr>

					<tr class="odd">

					<td>Account Number</td><td><input type="text" name="account_number"  class="input-medium"/></td>

					</tr>

					<tr class="even">

					<td>Bank Name</td><td><input type="text" name="bank_name"  class="input-medium"/></td>

					</tr>

					<tr class="odd">

					<td>Bank Branch Name</td><td><input type="text" name="bank_branch_name"  class="input-medium"/></td>

					</tr>

					<tr class="even">

					<td>Branch IFSC Code</td><td><input type="text" name="branch_IFSC_code"  class="input-medium"/></td>

					</tr>

					<tr class="odd">

					<td>Provident Fund Account Number</td><td><input type="text" name="prov_account_number"  class="input-medium"/></td>

					</tr>

					<tr class="even">

						<td align="right">

							<input type="submit" name="adduser" value="Update"/>

						</td>

					</tr>

                    </thead>

                </table>
				
				<!--Location Detail -->

			    <table width="100%" id="table9">

                    <thead>
	
								 <tr class="even">
									<td style="border:none">Zone Name</td>
										<td style="border:none"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');"  class="input-medium">
										<option value="">--Select Zone--</option>
										</select></td>
								  </tr>
								  <tr class="odd" id="hideReagon">
									<td style="border:none">Region Name</td>
										<td style="border:none"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');"  class="input-medium">
										<option value="">--Select Region--</option>
										</select></td>
								  </tr>
								  <tr class="even" id="hideArea">
									<td style="border:none">Area Name</td>
										<td style="border:none"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'headquater_id','');"  class="input-medium">
										<option value="">--Select Area--</option>
										</select></td>
								  </tr>
								 <tr class="odd" id="hideHeadQuater">
									<td style="border:none">HeadQuater</td>
										<td style="border:none"><select name="headquater_id"  id="headquater_id"  onchange="getNextRecord(this.value,9,'city_id','');"  class="input-medium">
										<option value="">--Select HeadQuater--</option>
										</select></td>
								  </tr>
<!--
								   <tr class="odd">
									<td style="border:none">City</td>
										<td style="border:none"><select name="city_id"  id="city_id" onchange="getNextRecord(this.value,10,'street_id','');"  class="input-medium">
										<option value="">--Select City--</option>
										</select></td>
								  </tr>
								  <tr class="odd">
									<td style="border:none">Street</td>
										<td style="border:none"><select name="street_id"  id="street_id"  class="input-medium">
										<option value="">--Select Street--</option>
										</select></td>
								  </tr>
-->
						<td align="right">

							<input type="submit" name="adduser" value="Update" class="submit-green"/>

						</td>

					</tr>

                    </thead> 

                </table>
				
				 <!--Expense Detail-->

			    <table width="100%" id="table10">

                    <thead>

					<tr>

					 <th colspan="2" align="left">Expense Detail</th>

					</tr>

					<tr>

					<td colspan="2" align="center">

					 <table id="expensetable">

					 </table>

					</td>

					</tr>

					<tr>

						<td align="right">

							<input type="submit" name="adduser" value="Update" class="submit-green"/>

						</td>

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
$(document).ready(function() {
	disabledOnload();
});

function addeducation(){
  $("#beforetr").before('<tr><td width="40px" align="left"><select name="degree[]" id="degree" style="width:80px" class="input-medium"><option value="Diploma">Diploma</option></select></td><td width="40px" align="left"> <input type="text" name="degree_name[]" id="degree_name"  class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="collage[]" id="collage"  class="input-medium"/></td><td width="40px" align="left"><input type="text" name="board[]" id="board"  class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="per_mark[]" id="per_mark"  class="input-medium"/></td><td width="40px" align="left" id="aftetd"> <input type="text" name="year_passing[]" id="year_passing"/></td></tr>');
}
function addcompany(){
  $("#beforecomp").before('<tr><td width="40px" align="left"><input type="text" name="company[]" id="company" style="width:100px" class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="designation[]" id="designation"  class="input-medium"/></td><td width="200px" align="left"><input type="text" name="from_year[]" id="from_year" style="width:40px"/>yy<input type="text" name="from_month[]" id="from_month" style="width:40px"/>mm</td><td width="200px" align="left"><input type="text" name="to_year[]" id="to_year" style="width:40px"/>yy<input type="text" name="to_month[]" id="to_month" style="width:40px"/>mm</td><td width="40px" align="left"> <input type="text" name="board[]" id="board" style="width:100px"  class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="per_mark[]" id="per_mark" style="width:100px"  class="input-medium"/></td><td width="40px" align="left" id="aftetd"> <input type="text" name="year_passing[]" id="year_passing"  class="input-medium"/></td></tr>');
}

$(function() {
		$("#doj").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		$("#dob").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
	});
</script>