 <div class="full_w">
                <div class="h_title">Add Loan User</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Loan','action'=>'loanuserlist'),'default',true)?>" class="button">Back</a>
				  <div class="sep1"></div>
                </div>
                <form name="form_company" action="" method="post" id="form1">  
                 <table width="100%">
                    <thead>
					  <tr> 
							<th colspan="2" align="left">Department Detail</th> 
						</tr> 
						 <tr> 
							<td width="40px">Business Unit</td> 
							<td width="40px"> 
							<select name="bunit_id" id="bunit_id" onchange="changeStatusBusiness(this.value,'');"> 
							    <option value="">---Select--</option>
									 <?php foreach($this->ObjModel->getBusinessToCompany() as $bunits){?>
									  <option value="<?php echo $bunits['bunit_id'];?>"><?php echo $bunits['bunit_name']?></option>
									 <?php }?>
							</select>
							</td> 
						</tr> 
					   <tr>
							<td width="40px">Department</td>
							<td width="40px">
								<select name="department_id" id="department_id" onchange="changeStatusDepartment(this.value,'');">
								   <option value="">---Select--</option>
								</select>
							</td>
						</tr>
					  <tr>
							<td width="40px">Designation</td>
							<td width="40px">
								<select name="designation_id" id="designation_id" onchange="changeStatusdesignation(this.value,'');">
								    <option value="">---Select--</option>
								</select>
							</td>
						</tr>
						 <tr>
							<td width="40px">Applied By</td>
							<td width="40px">
								<select name="parent_id" id="parent_id">
								    <option value="">---Select--</option>
								</select>
							</td>
						</tr>
						 <tr>
							<td width="40px">Loan Amount</td>
							<td width="40px">
							<input type="text" name="loan_amount" id="loan_amount" />
							</td>
						</tr>
						<tr>
							<td width="40px">Loan Amount Including Tax</td>
							<td width="40px">
							<input type="text" name="loan_including_tax" id="loan_including_tax" readonly="readonly" />
							</td>
						</tr>
						 <tr>
							<td width="40px">Rate Of Interest</td>
							<td width="40px">
							<input type="text" name="loan_interest" id="loan_interest" />
							</td>
						</tr>
						 <tr>
							<td width="40px">No of EMI</td>
							<td width="40px">
							<input type="text" name="no_of_emi" id="no_of_emi" />
							</td>
						</tr>
						 <tr>
							<td width="40px">Interest Type</td>
							<td width="40px">
							 <input type="radio" name="interest_type" id="interest_type" value="1" onclick="calculate_interest(this.value)"/>Compound Interest <input type="radio" name="interest_type" id="interest_type" value="2" onclick="calculate_interest(this.value)"/>Simple Interest
							</td>
						</tr>
						<tr>
							<td width="40px">Deduct From Salary</td>
							<td width="40px">
							<input type="radio" name="deduct_from_sal" id="deduct_from_sal" value="1"/>Yes <input type="radio" name="deduct_from_sal" id="deduct_from_sal" value="0" />No
							</td>
						</tr>
						<tr>
						<td width="40px" align="right" colspan="2">
							<input type="submit" name="addloanuser" value="Add"/>
						</td>
					</tr>
                    </thead>
                </table> 
			    </form>
            </div>
  </div>
        <div class="clear"></div>
    </div>