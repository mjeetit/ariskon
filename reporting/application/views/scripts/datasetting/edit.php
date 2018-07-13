 <div class="full_w">
                <div class="h_title">Company</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>$this->back),'default',true)?>" class="button back">Back</a>
				  <div class="sep1"></div>
                </div>
           
			  <form name="form_company" action="" method="post"> 
                <table width="100%">
                    <thead>
					<?php if($this->level==1){?> 
					  <input type="hidden" name="company_code" class="classtext" value="<?php echo  $this->EditRec['company_code']?>"/>
					  <tr>
                      <td width="40px">Comapny Name</td>
						    <td width="40px"><input type="text" name="company_name" class="classtext" value="<?php echo  $this->EditRec['company_name']?>"/></td>
                        </tr>
					 <tr>
                      <td width="40px">Comapny Address</td>
						    <td width="40px"><textarea name="company_address" class="classtext" rows="10"><?php echo $this->EditRec['company_address']?></textarea></td>
                        </tr>	
					 <?php } ?> 
					 <?php if($this->level==2){?> 
					  <input type="hidden" name="bunit_id" class="classtext" value="<?php echo  $this->EditRec['bunit_id']?>"/>
					  <tr>
                      <td width="40px">Business Unit</td>
						    <td width="40px"><input type="text" name="bunit_name" class="classtext" value="<?php echo  $this->EditRec['bunit_name']?>"/></td>
                        </tr>
					 <tr>
					 <?php } ?> 
					  <?php if($this->level==3){ //print_r($this->EditRec);die;?> 
					    <input type="hidden" name="id" class="classtext" value="<?php echo  $this->EditRec['id']?>"/>
					  <tr>
							<td width="40px">Country</td>
								<td width="40px"><select name="country_id">
								<option value="">--Select Country--</option>
								<?php 
								 $countries = $this->ObjModel->gerCountryList();
								foreach($countries as $country){?>
								<option value="<?php echo $country['country_id']?>" <?php if($country['country_id']==$this->EditRec['country_id']){ echo 'selected="selected"';}?>><?php echo $country['country_name']?></option>
								<?php } ?>
								</select></td>
						  </tr>
					 <?php } ?> 
					 <tr>
						    <td colspan="2" align="center"><input type="submit" name="<?php echo $this->level;?>" value="Update"  class="button add" /></td>
                        </tr>
                    </thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>