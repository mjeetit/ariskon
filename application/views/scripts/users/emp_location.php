<form name="form9" action="" method="post"  enctype="multipart/form-data">
<table width="100%">
                    <thead>		    
								 <tr class="odd">
									<td style="border:none">Zone Name</td>
										<td style="border:none"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">
										<option value="">--Select Zone--</option>
										</select></td>
								  </tr>
								  <tr class="even" id="hideReagon">
									<td style="border:none">Region Name</td>
										<td style="border:none"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');getNextRecord1(this.value,11,'headquater_id','');getNextRecord2(this.value,11,'headquater_id','');"  class="input-medium">
										<option value="">--Select Region--</option>
										</select></td>
								  </tr>
								  <tr class="odd" id="hideArea">
									<td style="border:none">Area Name</td>
										<td style="border:none"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'headquater_id','');"  class="input-medium">
										<option value="">--Select Area--</option>
										</select></td>
								  </tr>
								  <tr class="even" id="hideHeadQuater">
									<td style="border:none">HeadQuater</td>
										<td style="border:none"><select name="headquater_id"  id="headquater_id"  onchange="getNextRecord(this.value,9,'city_id','');"  class="input-medium">
										<option value="">--Select HeadQuater--</option>
										</select>
										
										</td>
								  </tr>
			<!--There is no use of these lines with new scenario -->
<!--
								  <tr class="even">
									<td style="border:none">Assigned Headquater 1</td>
										<td style="border:none"><select name="assign_headquater1"  id="assign_headquater1"   class="input-medium">
										<option value="">--Assigned HeadQuater--</option>
										</select>
										
										</td>
								  </tr>
								  <tr class="even">
									<td style="border:none">Assigned Headquater 2</td>
										<td style="border:none"><select name="assign_headquater2"  id="assign_headquater2" class="input-medium">
										<option value="">--Assigned HeadQuater--</option>
										</select>
										</td>
								  </tr>
								  
								   <tr class="odd">
									<td style="border:none">City</td>
										<td style="border:none"><select name="city_id"  id="city_id" onchange="getNextRecord(this.value,10,'street_id','');"  class="input-medium" >
										<option value="">--Select City--</option>
										</select></td>
								  </tr>
								 <tr class="even">
									<td style="border:none">Street</td>
										<td style="border:none"><select name="street_id"  id="street_id"  class="input-medium">
										<option value="">--Select Street--</option>
										</select></td>
								  </tr>
-->
						<td align="right">

							<input type="submit" name="location" value="Update" class="submit-green"/>

						</td>

					</tr>

                    </thead>

                </table>
</form>				