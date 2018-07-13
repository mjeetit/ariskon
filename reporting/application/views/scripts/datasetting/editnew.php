 <div class="full_w">
                <div class="h_title"><?php echo ucfirst($this->back);?></div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>$this->backNew),'default',true)?>" class="button back">Back</a>
				  <div class="sep1"></div>
                </div>
           
			  <form name="form_company" action="" method="post"> 
                <table width="100%">
                    <thead>
					
					<?php if($this->type=='Designation'){?> 
					<input type="hidden" name="designation_id" value="<?php echo $this->EditNewRec['designation_id'];?>"/>
					  <tr>
                      <td width="40px">Designation Name</td>
						    <td width="40px"><input type="text" name="designation_name" value="<?php echo $this->EditNewRec['designation_name'];?>" class="classtext"/></td>
                        </tr>
					 <?php } ?> 
					 
					<?php if($this->type=='Department'){?> 
					<input type="hidden" name="department_id" value="<?php echo $this->EditNewRec['department_id'];?>"/>
					  <tr>
                      <td width="40px">Department Name</td>
						    <td width="40px"><input type="text" name="department_name" value="<?php echo $this->EditNewRec['department_name'];?>" class="classtext"/></td>
                        </tr>
					 <?php } ?> 
					 
					 <?php if($this->type=='Salaryhead'){?> 
					 <input type="hidden" name="salary_title" value="<?php echo $this->EditNewRec['salary_title'];?>"/>
					  <tr>
                      <td width="40px">Salary Head</td>
						    <td width="40px"><input type="text" name="salary_title" value="<?php echo $this->EditNewRec['salary_title'];?>" class="classtext"/></td>
                        </tr>
					 <?php } ?>
					 
					 <tr>
						    <td colspan="2" align="center"><input type="submit" name="<?php echo $this->type;?>" value="Update"  class="button add" /></td>
                        </tr>
                    </thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>