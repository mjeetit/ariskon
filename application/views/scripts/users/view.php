 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Employee Detail</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<tbody>
                             
                               <tr>

						  <th colspan="2" align="left">Official Detail</th> 

						</tr>

						<tr class="odd"> 

							<td width="40px">User Type</td>

							<td width="40px"><?php echo 'Employee';?></td> 

						</tr>   

						 <tr class="even"> 

							<td width="40px">Business Unit</td> 

							<td width="40px"><?php echo $this->UserDetail['bunit_name']?></td> 

						</tr> 

					   <tr class="odd">

							<td width="40px">Department</td>

							<td width="40px"><?php echo $this->UserDetail['department_name']?></td>

						</tr>

					  <tr class="even">

							<td width="40px">Designation</td>

							<td width="40px"><?php echo $this->UserDetail['designation_name']?></td>

						</tr>

						 <tr class="odd">

							<td width="40px">Reporting Manager</td>

							<td width="40px"><?php echo $this->UserDetail['Parent']?></td>

						</tr>

						<tr class="even">

							<td width="40px">Reporting Office</td>

							<td width="40px"><?php echo $this->UserDetail['office_name']?></td>

						</tr>

						<tr class="odd">

							<td width="40px">Date Of Joining</td>

							<td width="40px"><?php echo $this->UserDetail['doj']?></td>

						</tr>

						<tr class="even">

							<td width="40px">CTC</td>

							<td width="40px"><?php echo $this->UserDetail['ctc']?></td>

					   </tr>

					   <tr class="odd">

							<td width="40px">Provident</td>

							<td width="40px"><?php echo ($this->UserDetail['office_name']==1)?'Yes':'No';?></td>

					   </tr>

					   <tr id="prov_id"  class="even">

							<td width="40px">Provident Pecentage</td>

							<td width="40px"><?php echo $this->UserDetail['provident_pecentage']?></td>

					   </tr>

					     <tr class="odd">

							<td width="40px">PAN Card Number</td>

							<td width="40px"><?php echo $this->UserDetail['Account']['pancard_number']?></td>

					   </tr>

                       <tr>
						  <th colspan="2" align="left">Personal Detail</th> 
					   </tr>
					   <tr class="odd"> 

							<td width="40px">Employee Name</td>

							<td width="40px"><?php echo $this->UserDetail['first_name'].' '.$this->UserDetail['last_name'];?></td> 

						</tr>   

						 <tr class="even"> 

							<td width="40px">Father's Name</td> 

							<td width="40px"><?php echo $this->UserDetail['father_name']?></td> 

						</tr> 

					   <tr class="odd">

							<td width="40px">Mother's Name</td>

							<td width="40px"><?php echo $this->UserDetail['mother_name']?></td>

						</tr>

					  <tr class="even">

							<td width="40px">Designation</td>

							<td width="40px"><?php echo $this->UserDetail['designation_name']?></td>

						</tr>

						 <tr class="odd">

							<td width="40px">Reporting Manager</td>

							<td width="40px"><?php echo $this->UserDetail['Parent']?></td>

						</tr>

						<tr class="even">

							<td width="40px">Reporting Office</td>

							<td width="40px"><?php echo $this->UserDetail['office_name']?></td>

						</tr>

						<tr class="odd">

							<td width="40px">Date Of Joining</td>

							<td width="40px"><?php echo $this->UserDetail['doj']?></td>

						</tr>

						<tr class="even">

							<td width="40px">CTC</td>

							<td width="40px"><?php echo $this->UserDetail['ctc']?></td>

					   </tr>

					   <tr class="odd">

							<td width="40px">Provident</td>

							<td width="40px"><?php echo ($this->UserDetail['office_name']==1)?'Yes':'No';?></td>

					   </tr>

					   <tr id="prov_id"  class="even">

							<td width="40px">Provident Pecentage</td>

							<td width="40px"><?php echo $this->UserDetail['provident_pecentage']?></td>

					   </tr>

					     <tr class="odd">

							<td width="40px">PAN Card Number</td>

							<td width="40px"><?php echo $this->UserDetail['Account']['pancard_number']?></td>

					   </tr>

					   
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
 
        </div>
        <div class="clear"></div>
    </div>
	