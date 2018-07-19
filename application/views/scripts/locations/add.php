<div class="grid_12">
    <div class="module">
		<h2><span>New <?php echo ucfirst($this->back);?></span></h2>
	    <div class="module-body">
			<form name="form_company" action="" method="post"> 
				<table width="70%" style="border:none">
					<thead>
					<tr>
					<td align="center" style="border:none">
						<table style=" width:70%">
							<thead>
								<tr>
									<th colspan="2">Add <?php echo ucfirst($this->back);?>
									</th>
								 </tr>
								<?php if($this->level==1){?> 
								<tr class="even">
									<td style="border:none">Comapny Name</td>
									<td style="border:none">
										<input type="text" name="company_name" class="input-medium"/>
									</td>
								</tr>
								<tr class="odd">
									<td style="border:none" valign="top">Comapny Address</td>
									<td style="border:none">
										<textarea name="company_address" class="input-medium" rows="10"></textarea>
									</td>
								</tr>	
								<?php } ?> 

			 					<?php if($this->level==2){?>

								<tr>
								   <td style="border:none">Business Unit</td>
								   <td style="border:none"><input type="text" name="bunit_name" class="input-medium"/></td>
								</tr>	
							<?php }?>

							<?php if($this->level==3){?> 
								<tr>
									<td style="border:none">Country</td>
									<td style="border:none">
										<select name="country_id">
											<option value="">--Select Country--</option>
										<?php 
											$countries = $this->ObjModel->gerCountryList();

											foreach($countries as $country){?>

												<option value="<?php echo $country['country_id']?>"><?php echo $country['country_name']?></option>

										<?php } ?>
										</select>
									</td>
								</tr>
			 					<?php } ?>
		
							  	<?php if($this->level==4){?> 
						  	 	<tr>
									<td style="border:none">Business Unit</td>
									<td style="border:none">
										<select name="bunit_id">
											<option value="">--Select Bussness Unit--</option>
											<?php 
						 						$bunits = $this->ObjModel->getBissnessUnit();

												foreach($bunits as $bunit){?>

													<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

											<?php } ?>
										</select>
									</td>
								</tr>

								<tr>
									<td style="border:none">Zone Name</td>
									<td style="border:none">
										<input type="text" name="zone_name" class="input-medium"/>
									</td>
								</tr>
								<?php } ?>

								<?php if($this->level==5){?> 
								<tr>
									<td style="border:none">Business Unit</td>
									<td style="border:none">
										<select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">
											<option value="">--Select Bussness Unit--</option>
										<?php 

											 $bunits = $this->ObjModel->getBissnessUnit();

											foreach($bunits as $bunit){?>

											<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

											<?php } ?>

											</select></td>

									  </tr>

									 <tr>
										<td style="border:none">Zone Name</td>
										<td style="border:none">
											<select name="zone_id"  id="zone_id">
												<option value="">--Select Zone--</option>
											</select>
										</td>
									  </tr>

									 <tr>
										<td style="border:none">Region Name</td>
										<td style="border:none">
											<input type="text" name="region_name" class="input-medium"/>
										</td>
									  </tr>

								 <?php } ?>

								 

								  <?php if($this->level==6){?> 

								   <tr>

										<td style="border:none">Business Unit</td>

											<td style="border:none"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

											<option value="">--Select Bussness Unit--</option>

											<?php 

											 $bunits = $this->ObjModel->getBissnessUnit();

											foreach($bunits as $bunit){?>

											<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

											<?php } ?>

											</select></td>

									  </tr>

									 <tr>

										<td style="border:none">Zone Name</td>

											<td style="border:none"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

											<option value="">--Select Zone--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Region Name</td>

											<td style="border:none"><select name="region_id"  id="region_id">

											<option value="">--Select Region--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Area Code</td>

											<td style="border:none"><input type="text" name="area_code" class="input-medium"/></td>

									  </tr>

									 <tr>

										<td style="border:none">Area Name</td>

											<td style="border:none"><input type="text" name="area_name" class="input-medium"/></td>

									  </tr>

								 <?php } ?>

								  

								  <?php if($this->level==7){?> 

								   <tr>

										<td style="border:none">Business Unit</td>

											<td style="border:none"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

											<option value="">--Select Bussness Unit--</option>

											<?php 

											 $bunits = $this->ObjModel->getBissnessUnit();

											foreach($bunits as $bunit){?>

											<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

											<?php } ?>

											</select></td>

									  </tr>

									 <tr>

										<td style="border:none">Zone Name</td>

											<td style="border:none"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

											<option value="">--Select Zone--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Region Name</td>

											<td style="border:none"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');">

											<option value="">--Select Region--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Area Name</td>

											<td style="border:none"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'city_id','');">

											<option value="">--Select Area--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">City</td>

											<td style="border:none"><select name="city_id"  id="city_id" >

											<option value="">--Select City--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">Office Name</td>

											<td style="border:none"><input type="text" name="office_name" class="input-medium"></td>

									  </tr>

									   <tr>

										<td style="border:none">Office Address</td>

											<td style="border:none"><textarea name="headoffice_address" rows="7" class="input-medium"></textarea></td>

									  </tr>

									   <tr>

										<td style="border:none">Office Type</td>

											<td style="border:none"><select name="office_type"  id="office_type" >

											<option value="">--Select Type--</option>

											<option value="2">Corporate Office</option>

											<option value="1">Branch Office</option>

											<option value="3">HeadQuater</option>

											</select></td>

									  </tr>

								 <?php } ?>

								 

								  <?php if($this->level==8){?> 

								   <tr>

										<td style="border:none">Business Unit</td>

											<td style="border:none"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

											<option value="">--Select Bussness Unit--</option>

											<?php 

											 $bunits = $this->ObjModel->getBissnessUnit();

											foreach($bunits as $bunit){?>

											<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

											<?php } ?>

											</select></td>

									  </tr>

									 <tr>

										<td style="border:none">Zone Name</td>

											<td style="border:none"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

											<option value="">--Select Zone--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Region Name</td>

											<td style="border:none"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');">

											<option value="">--Select Region--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Area Name</td>

											<td style="border:none"><select name="area_id"  id="area_id">

											<option value="">--Select Area--</option>

											</select></td>

									  </tr>

									  <!-- <tr>

										<td style="border:none">HeadQuater Address</td>

											<td style="border:none"><select name="headoff_id"  id="headoff_id">

											<option value="">--Select HeadQuater--</option>

											</select></td>

									  </tr>-->

									   <tr>

										<td style="border:none">HeadQuater Name</td>

											<td style="border:none"><input type="text" name="headquater_name"  class="input-medium"/></td>

									  </tr>

									  <tr>

										<td style="border:none">HeadQuater Address</td>

											<td style="border:none"><textarea cols="5" rows="5" name="headquater_address"></textarea></td>

									  </tr>

								 <?php } ?>

								 

								  <?php if($this->level==9){?> 

								   <tr>

										<td style="border:none">Business Unit</td>

											<td style="border:none"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

											<option value="">--Select Bussness Unit--</option>

											<?php 

											 $bunits = $this->ObjModel->getBissnessUnit();

											foreach($bunits as $bunit){?>

											<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

											<?php } ?>

											</select></td>

									  </tr>

									 <tr>

										<td style="border:none">Zone Name</td>

											<td style="border:none"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

											<option value="">--Select Zone--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Region Name</td>

											<td style="border:none"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');">

											<option value="">--Select Region--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Area Name</td>

											<td style="border:none"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'headquater_id','');">

											<option value="">--Select Area--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">HeadQuater</td>

											<td style="border:none"><select name="headquater_id"  id="headquater_id" >

											<option value="">--Select HeadQuater--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">City</td>

											<td style="border:none"><input type="text" name="city_name" class="input-medium"/></td>

									  </tr>

								 <?php } ?>

								 

								  <?php if($this->level==10){?> 

								   <tr>

										<td style="border:none">Business Unit</td>

											<td style="border:none"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

											<option value="">--Select Bussness Unit--</option>

											<?php 

											 $bunits = $this->ObjModel->getBissnessUnit();

											foreach($bunits as $bunit){?>

											<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

											<?php } ?>

											</select></td>

									  </tr>

									 <tr>

										<td style="border:none">Zone Name</td>

											<td style="border:none"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

											<option value="">--Select Zone--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Region Name</td>

											<td style="border:none"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');">

											<option value="">--Select Region--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Area Name</td>

											<td style="border:none"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'headquater_id','');">

											<option value="">--Select Area--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">HeadQuater</td>

											<td style="border:none"><select name="headquater_id"  id="headquater_id" onchange="getNextRecord(this.value,9,'city_id','');">

											<option value="">--Select HeadQuater--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">City</td>

											<td style="border:none"><select name="city_id"  id="city_id" >

											<option value="">--Select City--</option>

											</select></td>

									  </tr>

					                  <tr>

										<td style="border:none">Location Type</td>

											<td style="border:none"><select name="location_type_id">

											<option value="">--Select Location Type--</option>

											<?php 

											 $ltypes = $this->ObjModel->getLocationType();

											foreach($ltypes as $ltype){?>

											<option value="<?=$ltype['location_type_id']?>"><?=$ltype['location_type_name']?></option>

											<?php } ?>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">Patch Name</td>

											<td style="border:none"><input name="patch_name" type="text" class="input-medium" /></td>

									  </tr>

								 <?php } ?>
								 
								 <?php if($this->level==11){?> 

								   <tr>

										<td style="border:none">Business Unit</td>

											<td style="border:none"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

											<option value="">--Select Bussness Unit--</option>

											<?php 

											 $bunits = $this->ObjModel->getBissnessUnit();

											foreach($bunits as $bunit){?>

											<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

											<?php } ?>

											</select></td>

									  </tr>

									 <tr>

										<td style="border:none">Zone Name</td>

											<td style="border:none"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

											<option value="">--Select Zone--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Region Name</td>

											<td style="border:none"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');">

											<option value="">--Select Region--</option>

											</select></td>

									  </tr>

									  <tr>

										<td style="border:none">Area Name</td>

											<td style="border:none"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'headquater_id','');">

											<option value="">--Select Area--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">HeadQuater</td>

											<td style="border:none"><select name="headquater_id"  id="headquater_id" onchange="getNextRecord(this.value,9,'city_id','');">

											<option value="">--Select HeadQuater--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">City</td>

											<td style="border:none"><select name="city_id"  id="city_id" >

											<option value="">--Select City--</option>

											</select></td>

									  </tr>

									   <tr>

										<td style="border:none">Office Name</td>

											<td style="border:none"><input name="office_name" type="text" class="input-medium" /></td>

									  </tr>
									  
									   <tr>
					                    <td>Office Address</td><td><textarea name="office_address" cols="10" rows="6"></textarea></td>
					                </tr>
					                <tr>
					                    <td>Office Type</td><td>
										   <select name="office_type" class="input-medium">
										   <option value="1">Corporate Office</option>
										    <option value="2">Branch Office</option>
											 <option value="3">HeadQuater</option>
										   </select>                                    
					                    </td>
										</tr>

								 <?php } ?>

								 <tr>

					<td colspan="2" align="center"><input type="submit" name="<?php echo $this->level;?>" value="Add"  class="submit-green" /></td>

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