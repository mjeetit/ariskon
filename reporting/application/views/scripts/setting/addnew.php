<div class="grid_12">
                <div class="module">
                     <h2><span><?php echo ucfirst($this->back);?></span></h2>
                     <div class="module-body">
                         <form name="form_company" action="" method="post"> 
						<?php if($this->type=='Designation'){?> 
                            <p>
                                <label>Designation Name</label>
                                <input type="text" name="designation_name" class="input-short" />
                            </p>
                            
                            <p>
                                <label>Designation Code</label>
                                <input type="text" name="designation_code" class="input-short" />
                            </p>
                            <p>
                              <label>Parent Designation</label>
						   <select name="designation_level" id="designation_level" class="input-short" >
							<option value="0">--Select--</option>
							<?php 
							$desinations = $this->ObjModel->getDesignation();
							foreach($desinations as $designation){?>
							<option value="<?php echo $designation['designation_level']?>"><?php echo $designation['designation_name'].'('.$designation['designation_code'].')'?></option>
							<?php }?>
							</select>
                            </p>
						<?php } ?> 
						 <?php if($this->type=='Department'){?> 
						       <p>
                                <label>Department Name</label>
                                <input type="text" name="department_name" class="input-short" />
                            </p>
					 <?php } ?> 
					 
					  <?php if($this->type=='Salaryhead'){?> 
						   <p>
                                <label>Salary Head</label>
                                <input type="text" name="salary_title" class="input-short" />
                            </p>
							<p>
                                <label>Type</label>
								<select name="salary_type" class="input-short" >
								<option value="1">Earning</option>
								<option value="2">Deduction</option>
								</select>
                            </p>
                             <p>
                                <label>Pro-Data Calculation</label>
                                <input type="radio" name="prodata_status" value="1" checked="checked" class="input-short" /> Yes
                                <input type="radio" name="prodata_status" value="0" class="input-short" /> No
                            </p>
                            <p>
                                <label>Sequence Number</label>
                                 <input type="text" name="sequence" class="input-short" />
                            </p>
                            
                   
					 <?php } ?>
					  <?php if($this->type=='Detectsalaryhead'){?>
					   <p>
                                <label>Deduction Slary Head</label>
                                <input type="text" name="salary_title" class="input-short" />
                            </p> 
					 
					 <?php } ?>
                            <fieldset>
                                <input class="submit-green" type="submit" name="<?php echo $this->type;?>" value="Add" /> 
                            </fieldset>
                        </form>
                     </div> <!-- End .module-body -->

                </div>  <!-- End .module -->
        		<div style="clear:both;"></div>
            </div>