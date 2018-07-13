 <div class="full_w">
                <div class="h_title"><?php echo ucfirst($this->back);?></div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>$this->backNew),'default',true)?>" class="button back">Back</a>
				  <div class="sep1"></div>
                </div>
           
			  <form name="data_setting" action="" method="post"> 
                <table width="100%">
                    <thead>
					  <tr>
                      <td width="40px">Business Unit</td>
						    <td width="40px">
							<select name="bunit_id">
							 <option value="">--Select--</option>
							 <?php $bunits =  $this->ObjModel->getBissnessUnit();
							foreach($bunits as  $bunit){?>
							<option value="<?php echo  $bunit['bunit_id']; ?>"><?php echo  $bunit['bunit_name']; ?></option>
							<?php } ?>
							</select>
                        </tr>
					  <td width="40px">Comapny</td>
						    <td width="40px">
							<select name="company_code">
							 <option value="">--Select--</option>
							<?php $companies =  $this->ObjModel->getCompany();
							foreach($companies as  $company){?>
							<option value="<?php echo  $company['company_code']; ?>"><?php echo  $company['company_name']; ?></option>
							<?php } ?>
							</select>
                        </tr>
					 <tr>
						    <td colspan="2" align="center"><input type="submit" name="b2c" value="Add"  class="button add" /></td>
                        </tr>
                    </thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>