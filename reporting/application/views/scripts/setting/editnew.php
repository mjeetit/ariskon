<div class="grid_12">
                <div class="module">
                     <h2><span><?php echo ucfirst($this->backNew);?></span></h2>
                     <div class="module-body">
                         <form name="form_company" action="" method="post"> 
						<?php if($this->type=='Designation'){?> 
                            <p>
                                <label>Designation Name</label>
                                <input type="text" name="designation_name" class="input-short" value="<?php echo $this->EditNewRec['designation_name'];?>" />
                            </p>
                            
                            <p>
                                <label>Designation Code</label>
                                <input type="text" name="designation_code" class="input-short"  value="<?php echo $this->EditNewRec['designation_code'];?>"/>
                            </p>
                            <p>
                              <label>Parent Designation</label>
                              <select name="designation_level" id="designation_level">

							<option value="">--Select--</option>

							<?php 

							$desinations = $this->ObjModel->getDesignation();

							foreach($desinations as $designation){

							$select = '';

							if($designation['designation_id']==$this->EditNewRec['designation_level']){

							  $select = 'selected="selected"';

							}

							?>

							<option value="<?php echo $designation['designation_id']?>" <?php echo $select;?>><?php echo $designation['designation_name'].'('.$designation['designation_code'].')'?></option>

							<?php }?>

							</select>
                            </p>
						<?php } ?> 
						 <?php if($this->type=='Department'){?> 
						       <p>
                                <label>Department Name</label>
                                <input type="text" name="department_name" class="input-short" value="<?php echo $this->EditNewRec['department_id'];?>" />
                            </p>
					 <?php } ?> 
					 
					  <?php if($this->type=='Salaryhead'){?> 
						 <p>
                                <label>Salary Head</label>
                                <input type="text" name="salary_title" class="input-short" value="<?php echo $this->EditNewRec['salary_title'];?>"  />
                            </p>
                            <p>
                                <label>Type</label>
								<select name="salary_type" class="input-short" >
								<option value="1" <?php if($this->EditNewRec['salary_type']=='1'){ echo 'selecte="selected"'; }?>>Earning</option>
								<option value="2" <?php if($this->EditNewRec['salary_type']=='2'){ echo 'selecte="selected"'; }?>>Deduction</option>
								</select>
                            </p>
                            <p>
                                <label>Pro-Data Calculation</label>
                                <input type="radio" name="prodata_status" value="1" <?php if($this->EditNewRec['prodata_status']=='1'){ echo 'checked="checked"'; }?> class="input-short" /> Yes
                                <input type="radio" name="prodata_status" value="0" <?php if($this->EditNewRec['prodata_status']=='0'){ echo 'checked="checked"'; }?> /> No
                            </p>
                            <p>
                                <label>Sequence Number</label>
                                 <input type="text" name="sequence" class="input-short" value="<?php echo $this->EditNewRec['sequence'];?>" />
                            </p>
                   
					 <?php } ?>
					  <?php if($this->type=='Detectsalaryhead'){?>
					  <input type="hidden" name="salary_title" value="<?php echo $this->EditNewRec['salary_title'];?>"/>
					   <p>
                                <label>Deduction Slary Head</label>
                                <input type="text" name="salary_title" class="input-short" value="<?php echo $this->EditNewRec['salary_title'];?>" />
                            </p> 
					 
					 <?php } ?>
                            <fieldset>
                                <input class="submit-green" type="submit" name="<?php echo $this->type;?>" value="Update" /> 
                            </fieldset>
                        </form>
                     </div> <!-- End .module-body -->

                </div>  <!-- End .module -->
        		<div style="clear:both;"></div>
            </div>