	 <div class="grid_12"> 
	            <!-- Example table -->
                <div class="module"> 
                	<h2><span>Manual Salary</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
                             
						<tr>
						<th>Salaey Head</th>
						<th>Amount</th>
                        <th>Arrier Head</th>
						<th>Arrier Amount</th>
					</tr>
						<?php if(!empty($this->salaryhead)){ 
						foreach($this->salaryhead as $i=>$heads){ //echo "<pre>";print_r($heads);die;
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $heads['salary_title']?></td>
						 <td><input type="text" name="salary[<?php echo $heads['salaryhead_id']?>]" /></td>
						 <td><?php echo $heads['salary_title']?></td>
                         <td><input type="text" name="arrier[<?php echo $heads['salaryhead_id']?>]" /></td>
						 </tr>
						 <?php } }?>
						 <tr>
						  <td>Earningtotal<input type="text" name="earningtotal"/></td>
						   <td>Deduction Total<input type="text" name="deductiontotal" /></td>
						    <td>Grand Total<input type="text" name="grandtotal" /></td>
						 </tr>
						  <tr>
						  <td>Comp. Cont PF<input type="text" name="ESIComp"/></td>
						   <td>Comp. Cont. ESI<input type="text" name="PFComp" /></td>
							
						 </tr>
						 <tr>
						 
						 <td colspan="4" align="center"><input type="submit" name="submit" value="Submit" /></td>
						 </tr>
                            </thead>
                        </table>
                       
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 --> 
