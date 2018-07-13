<form name="form2" action="" method="post"  enctype="multipart/form-data">
<table width="100%">

                    <thead>

						 <tr>

							<th colspan="2" align="left">Personal Detail</th>

						</tr>

						 <tr class="odd">

							<td width="40px">First Name</td>

							<td width="40px"><input type="text" name="first_name" id="first_name" value="<?php echo $this->UserDetail['first_name'];?>" class="input-medium"/></td>

						</tr>

						<tr class="even">

							<td width="40px">Last Name</td>

							<td width="40px"><input type="text" name="last_name" id="last_name" value="<?php echo $this->UserDetail['last_name'];?>" class="input-medium"/></td>

						</tr>

						 <tr class="odd">

							<td width="40px">Father's Name</td>

							<td width="40px">

								<input type="text" name="father_name" value="<?php echo $this->UserDetail['father_name'];?>"  class="input-medium"/>

							</td>

						</tr>

						 <tr class="even">

							<td width="40px">Mother's Name</td>

							<td width="40px">

								<input type="text" name="mother_name" value="<?php echo $this->UserDetail['mother_name'];?>"  class="input-medium"/>

							</td>

						</tr>

						 <tr class="odd">

							<td width="40px">Date Of Birth</td>

							<td width="40px">

								<input type="text" name="dob" id="dob" value="<?php echo $this->UserDetail['dob'];?>"  class="input-medium"/>

							</td>

						</tr>

						 <tr class="even">

							<td>Gender</td>

							<td>

								Male<input type="radio" name="sex" id="sex" value="Male" <?php if($this->UserDetail['sex']=='Male'){ echo 'checked="checked"';}?> />Female<input type="radio" name="sex" id="sex" value="Female" <?php if($this->UserDetail['sex']=='Female'){ echo 'checked="checked"';}?> />

							</td>

						 </tr>
						  <tr class="odd">

							<td>Blood Group</td>

							<td>

								<input type="text" name="blood_group"  class="input-medium"  value="<?php echo $this->UserDetail['blood_group'];?>" />

							</td>

						 </tr>

						 <tr class="even">

							<td width="40px">Personal Email</td>

							<td width="40px">

								<input type="text" name="personal_email" value="<?php echo $this->UserDetail['personal_email'];?>"  class="input-medium"/>

							</td>

						</tr>
						 <tr class="odd">

						<td>Contact Number</td>

							<td>

								<input type="text" name="contact_number"  class="input-medium"  value="<?php echo $this->UserDetail['contact_number'];?>"/>

							</td>

						</tr>

 						<tr class="even">

						<td>Emergency Contact</td>

							<td>

								<input type="text" name="emergency_contact"  class="input-medium"  value="<?php echo $this->UserDetail['emergency_contact'];?>"/>

							</td>

						</tr>

					   <tr class="odd">

						<th colspan="2" align="left">Corespondance Address</th>

					   </tr>

					    <tr class="even">

							<td width="40px">Address</td>

							<td width="40px">

								<input type="text" name="cores_address" value="<?php echo $this->UserDetail['cores_address'];?>"  class="input-medium"/>

							</td>

						</tr>

						<tr class="odd">

							<td width="40px">City</td>

							<td width="40px">

								<input type="text" name="cores_city" value="<?php echo $this->UserDetail['cores_city'];?>"  class="input-medium"/>

							</td>

						</tr>

						<tr class="even">

							<td width="40px">State</td>

							<td width="40px">

								<input type="text" name="cores_state" value="<?php echo $this->UserDetail['cores_state'];?>"  class="input-medium"/>

							</td>

						</tr>

					   <tr class="odd">

							<td width="40px">Post Code</td>

							<td width="40px">

								<input type="text" name="cores_postalcode" value="<?php echo $this->UserDetail['cores_postalcode'];?>"  class="input-medium"/>

							</td>

					  </tr>	

					  <tr class="even">

							<td width="40px">Country</td>

							<td width="40px">

								<input type="text" name="cores_country" value="<?php echo $this->UserDetail['cores_country'];?>"  class="input-medium"/>

							</td>

					  </tr>

					   <tr class="odd">

						<th colspan="2" align="left">Permanent Address</th>

					   </tr>

					    <tr class="even">

							<td width="40px">Address</td>

							<td width="40px">

								<input type="text" name="permanent_address" value="<?php echo $this->UserDetail['permanent_address'];?>"  class="input-medium"/>

							</td>

						</tr>

						<tr class="odd">

							<td width="40px">City</td>

							<td width="40px">

								<input type="text" name="permanent_city" value="<?php echo $this->UserDetail['permanent_city'];?>"  class="input-medium"/>

							</td>

						</tr>

						<tr class="even">

							<td width="40px">State</td>

							<td width="40px">

								<input type="text" name="permanent_state" value="<?php echo $this->UserDetail['permanent_state'];?>"  class="input-medium"/>

							</td>

						</tr>

					   <tr class="odd">

							<td width="40px">Post Code</td>

							<td width="40px">

								<input type="text" name="permanent_postalcode" value="<?php echo $this->UserDetail['permanent_postalcode'];?>"  class="input-medium"/>

							</td>

					  </tr>	

					  <tr class="even">

							<td width="40px">Country</td>

							<td width="40px">

								<input type="text" name="permanent_country" value="<?php echo $this->UserDetail['permanent_country'];?>"  class="input-medium"/>

							</td>

					  </tr>

					   <tr>

					    <td colspan="2" align="right">

						 <input type="submit" name="personal_detail"  value="Update" class="submit-green"/>

						 </td>

					  </tr>			

                    </thead>

                </table>
	</form>			