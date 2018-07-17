 <div class="full_w">

                <div class="h_title"><?php echo ucfirst($this->back);?></div>

				<div class="entry">

                    <div class="sep"></div>

                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>$this->back),'default',true)?>" class="button back">Back</a>

				  <div class="sep1"></div>

                </div>

           

			  <form name="form_company" action="" method="post"> 

                <table width="100%">

                    <thead>

					

					<?php if($this->level==1){?> 

					  <tr>

                      <td width="40px">98-Comapny Name</td>

						    <td width="40px"><input type="text" name="company_name" class="classtext"/></td>

                        </tr>

					 <tr>

                      <td width="40px">Comapny Address</td>

						    <td width="40px"><textarea name="company_address" class="classtext" rows="10"></textarea></td>

                        </tr>	

					 <?php } ?> 

					 

					 <?php if($this->level==2){?>

					    <tr>

						   <td width="40px">Business Unit</td>

						   <td width="40px"><input type="text" name="bunit_name" class="classtext"/></td>

                        </tr>	

					 <?php }?>

					 

					  <?php if($this->level==3){?> 

					   <tr>

							<td width="40px">Country</td>

								<td width="40px"><select name="country_id">

								<option value="">--Select Country--</option>

								<?php 

								 $countries = $this->ObjModel->gerCountryList();

								foreach($countries as $country){?>

								<option value="<?php echo $country['country_id']?>"><?php echo $country['country_name']?></option>

								<?php } ?>

								</select></td>

						  </tr>

					 <?php } ?>

					  <?php if($this->level==4){?> 

					   <tr>

							<td width="40px">Business Unit</td>

								<td width="40px"><select name="bunit_id">

								<option value="">--Select Bussness Unit--</option>

								<?php 

								 $bunits = $this->ObjModel->getBissnessUnit();

								foreach($bunits as $bunit){?>

								<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

								<?php } ?>

								</select></td>

						  </tr>

						 <tr>

							<td width="40px">Zone Name</td>

								<td width="40px"><input type="text" name="zone_name" class="classtext"/></td>

						  </tr>

					 <?php } ?>

					

					 <?php if($this->level==5){?> 

					   <tr>

							<td width="40px">Business Unit</td>

								<td width="40px"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

								<option value="">--Select Bussness Unit--</option>

								<?php 

								 $bunits = $this->ObjModel->getBissnessUnit();

								foreach($bunits as $bunit){?>

								<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

								<?php } ?>

								</select></td>

						  </tr>

						 <tr>

							<td width="40px">Zone Name</td>

								<td width="40px"><select name="zone_id"  id="zone_id">

								<option value="">--Select Zone--</option>

								</select></td>

						  </tr>

						 <tr>

							<td width="40px">Region Name</td>

								<td width="40px"><input type="text" name="region_name" class="classtext"/></td>

						  </tr>

					 <?php } ?>

					 

					  <?php if($this->level==6){?> 

					   <tr>

							<td width="40px">Business Unit</td>

								<td width="40px"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

								<option value="">--Select Bussness Unit--</option>

								<?php 

								 $bunits = $this->ObjModel->getBissnessUnit();

								foreach($bunits as $bunit){?>

								<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

								<?php } ?>

								</select></td>

						  </tr>

						 <tr>

							<td width="40px">Zone Name</td>

								<td width="40px"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

								<option value="">--Select Zone--</option>

								</select></td>

						  </tr>

						  <tr>

							<td width="40px">Region Name</td>

								<td width="40px"><select name="region_id"  id="region_id">

								<option value="">--Select Region--</option>

								</select></td>

						  </tr>

						  <tr>

							<td width="40px">Area Code</td>

								<td width="40px"><input type="text" name="area_code" class="classtext"/></td>

						  </tr>

						 <tr>

							<td width="40px">Area Name</td>

								<td width="40px"><input type="text" name="area_name" class="classtext"/></td>

						  </tr>

					 <?php } ?>

					  

					  <?php if($this->level==7){?> 

					  <input type="hidden" name="office_type" id="office_type" value="<?php echo $this->ID;?>" />

					   <tr>

							<td width="40px">Business Unit</td>

								<td width="40px"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

								<option value="">--Select Bussness Unit--</option>

								<?php 

								 $bunits = $this->ObjModel->getBissnessUnit();

								foreach($bunits as $bunit){?>

								<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

								<?php } ?>

								</select></td>

						  </tr>

						 <tr>

							<td width="40px">Zone Name</td>

								<td width="40px"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

								<option value="">--Select Zone--</option>

								</select></td>

						  </tr>

						  <tr>

							<td width="40px">Region Name</td>

								<td width="40px"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');">

								<option value="">--Select Region--</option>

								</select></td>

						  </tr>

						  <tr>

							<td width="40px">Area Name</td>

								<td width="40px"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'city_id','');">

								<option value="">--Select Area--</option>

								</select></td>

						  </tr>

						   <tr>

							<td width="40px">City</td>

								<td width="40px"><select name="city_id"  id="city_id" >

								<option value="">--Select City--</option>

								</select></td>

						  </tr>

						   <tr>

							<td width="40px">Office Name</td>

								<td width="40px"><input type="text" name="office_name" class="classtext"></td>

						  </tr>

						   <tr>

							<td width="40px">Office Address</td>

								<td width="40px"><textarea name="headoffice_address" rows="7" class="classtext"></textarea></td>

						  </tr>

					 <?php } ?>

					 

					  <?php if($this->level==8){?> 

					   <tr>

							<td width="40px">Business Unit</td>

								<td width="40px"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

								<option value="">--Select Bussness Unit--</option>

								<?php 

								 $bunits = $this->ObjModel->getBissnessUnit();

								foreach($bunits as $bunit){?>

								<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

								<?php } ?>

								</select></td>

						  </tr>

						 <tr>

							<td width="40px">Zone Name</td>

								<td width="40px"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

								<option value="">--Select Zone--</option>

								</select></td>

						  </tr>

						  <tr>

							<td width="40px">Region Name</td>

								<td width="40px"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');">

								<option value="">--Select Region--</option>

								</select></td>

						  </tr>

						  <tr>

							<td width="40px">Area Name</td>

								<td width="40px"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'headoff_id','');">

								<option value="">--Select Area--</option>

								</select></td>

						  </tr>

						  <!-- <tr>

							<td width="40px">HeadQuater Address</td>

								<td width="40px"><select name="headoff_id"  id="headoff_id">

								<option value="">--Select HeadQuater--</option>

								</select></td>

						  </tr>-->

						   <tr>

							<td width="40px">City</td>

								<td width="40px"><input type="text" name="city_name" /></td>

						  </tr>

					 <?php } ?>

					 

					  <?php if($this->level==9){?> 

					   <tr>

							<td width="40px">Business Unit</td>

								<td width="40px"><select name="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');">

								<option value="">--Select Bussness Unit--</option>

								<?php 

								 $bunits = $this->ObjModel->getBissnessUnit();

								foreach($bunits as $bunit){?>

								<option value="<?php echo $bunit['bunit_id']?>"><?php echo $bunit['bunit_name']?></option>

								<?php } ?>

								</select></td>

						  </tr>

						 <tr>

							<td width="40px">Zone Name</td>

								<td width="40px"><select name="zone_id"  id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');">

								<option value="">--Select Zone--</option>

								</select></td>

						  </tr>

						  <tr>

							<td width="40px">Region Name</td>

								<td width="40px"><select name="region_id"  id="region_id" onchange="getNextRecord(this.value,7,'area_id','');">

								<option value="">--Select Region--</option>

								</select></td>

						  </tr>

						  <tr>

							<td width="40px">Area Name</td>

								<td width="40px"><select name="area_id"  id="area_id" onchange="getNextRecord(this.value,8,'city_id','');">

								<option value="">--Select Area--</option>

								</select></td>

						  </tr>

						   <tr>

							<td width="40px">City</td>

								<td width="40px"><select name="city_id"  id="city_id" >

								<option value="">--Select City--</option>

								</select></td>

						  </tr>

						   <tr>

							<td width="40px">Street</td>

								<td width="40px"><input type="text" name="street_name" /></td>

						  </tr>

					 <?php } ?>

					 <tr>

						    <td colspan="2" align="center"><input type="submit" name="<?php echo $this->level;?>" value="Add"  class="button add" /></td>

                        </tr>

                    </thead>

                </table>

				</form>

            </div>

        </div>

        <div class="clear"></div>

    </div>

