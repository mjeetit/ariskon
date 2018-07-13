<div class="grid_12">
                <div class="module">
                     <h2><span>Edit Profile</span></h2>
                     <div class="module-body">
                        
						<table width="70%" style="border:none">
							<thead> 
						<tr> 
						
						
				<tr id="table1">		
				<td colspan="2" align="center"> 
			  <!--Official Detail-->
			  <form action="" method="post">
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

								<input type="text" name="personal_email"  class="input-medium"  value="<?php echo $this->UserDetail['personal_email'];?>"/>

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
					  <tr><td colspan="2">
					 <table width="100%">

                    <thead>

					<tr>

					 <th colspan="6" align="left">Education Detail</th>

						</tr>

						<tr class="odd"> 

						<td width="40px" align="left"><b>Degree</b></td><td width="40px" align="left"><b>Degree Name</b></td><td width="40px" align="left"><b>School/collage</b></td><td width="40px"><b>Board/University</b></td><td width="40px"><b>Percentage Mark/CGPA</b></td><td width="40px"><b>Year of Passing</b></td>

						</tr> 

						<?php $degrees = array('10 th','10+2','Graduation','Post Graduation','Post Graduation2','Diploma');

						foreach($degrees as $key=>$degree){
							$class = ($key%2==0)?'even':'odd';?>
							<tr class="<?php echo  $class;?>">

						<td width="40px" align="left"><input type="text" name="degree[]" id="degree" value="<?php echo $degree;?>" readonly="readonly" style="width:80px" class="input-medium"/></td> 

						<td width="40px" align="left"> <input type="text" name="degree_name[]" id="degree_name" value="<?php echo $this->UserDetail['Education'][$key]['degree_name'];?>" class="input-medium"/></td>

						<td width="40px" align="left"> <input type="text" name="collage[]" id="collage" value="<?php echo $this->UserDetail['Education'][$key]['collage'];?>" class="input-medium"/></td>

						<td width="40px" align="left"> <input type="text" name="board[]" id="board" value="<?php echo $this->UserDetail['Education'][$key]['board'];?>" class="input-medium"/></td>

						 <td width="40px" align="left"> <input type="text" name="per_mark[]" id="per_mark" value="<?php echo $this->UserDetail['Education'][$key]['per_mark'];?>" class="input-medium"/></td> 

						 <td width="40px" align="left" id="aftetd"> <input type="text" name="year_passing[]" id="year_passing" value="<?php echo $this->UserDetail['Education'][$key]['year_passing'];?>" class="input-medium"/></td> 
						 

						</tr> 

						<?php }?>

					   <tr id="beforetr">

							<td width="40px">&nbsp;</td>

							<td width="40px" align="right" colspan="3">

								<input type="button" name="adduser" value="Add More" onclick="addeducation();"/>

							</td>

							

						</tr>

                    </thead>

                </table>
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
				</td>
				</tr>
                
			</thead>
		</table>
	 </div> <!-- End .module-body -->

</div>  <!-- End .module -->
<div style="clear:both;"></div>
</div>
<script type="text/javascript">
function addeducation(){

  $("#beforetr").before('<tr><td width="40px" align="left"><input type="text" name="degree[]" id="degree" class="input-medium"></td><td width="40px" align="left"> <input type="text" name="degree_name[]" id="degree_name" class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="collage[]" id="collage" class="input-medium"/></td><td width="40px" align="left"><input type="text" name="board[]" id="board" class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="per_mark[]" id="per_mark" class="input-medium"/></td><td width="40px" align="left" id="aftetd"> <input type="text" name="year_passing[]" id="year_passing" class="input-medium"/></td></tr>');

}
</script>
