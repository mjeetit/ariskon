<form name="form4" action="" method="post"  enctype="multipart/form-data"> 
<table width="100%">

                    <thead>

					<tr>

					 <th colspan="7" align="left">Employeemnet Detail</th>

						</tr>

						<tr class="odd"> 

						<td width="40px" align="left"><b>Comapny</b></td><td width="40px" align="left"><b>Designation</b></td><td width="40px" align="left"><b>From</b></td><td width="40px" align="left"><b>To</b></td><td width="40px"><b>Joinig CTC</b></td><td width="40px"><b>Leaving CTC</b></td><td width="40px"><b>Reasion Of Leaving</b></td>

						</tr> 

						<?php for($i=0;$i<3;$i++){
						   $class = ($i%2==0)?'even':'odd';?>
						  <tr class="<?php echo  $class;?>">

						<td width="40px" align="left"><input type="text" name="company[]" id="company" style="width:100px" value="<?php echo $this->UserDetail['Employeement'][$i]['compnay'];?>" class="input-medium"/></td> 

						<td width="40px" align="left"> <input type="text" name="designation[]" id="designation" value="<?php echo $this->UserDetail['Employeement'][$i]['designation'];?>" class="input-medium"/></td>

						<td width="100px" align="left"><input type="text" name="from_year[]" id="from_year" style="width:40px" value="<?php echo $this->UserDetail['Employeement'][$i]['from_year'];?>" class="input-medium"/>yy<input type="text" name="from_month[]" id="from_month" style="width:40px" value="<?php echo $this->UserDetail['Employeement'][$i]['from_month'];?>" class="input-medium"/>mm</td>

						<td width="100px" align="left"><input type="text" name="to_year[]" id="to_year" style="width:40px" value="<?php echo $this->UserDetail['Employeement'][$i]['to_year'];?>" class="input-medium"/>yy<input type="text" name="to_month[]" id="to_month" style="width:40px" value="<?php echo $this->UserDetail['Employeement'][$i]['to_month'];?>" class="input-medium"/>mm</td>

						<td width="40px" align="left"> <input type="text" name="joining_ctc[]" id="joining_ctc" style="width:100px" value="<?php echo $this->UserDetail['Employeement'][$i]['joining_ctc'];?>" class="input-medium"/></td>

						 <td width="40px" align="left"> <input type="text" name="leaving_ctc[]" id="leaving_ctc" style="width:100px" value="<?php echo $this->UserDetail['Employeement'][$i]['leaving_ctc'];?>" class="input-medium"/></td> 

						 <td width="100px" align="left" id="aftetd"> <input type="text" name="reasion_of_leaving[]" id="reasion_of_leaving" value="<?php echo $this->UserDetail['Employeement'][$i]['reasion_of_leaving'];?>" class="input-medium"/></td> 

						</tr> 

						<?php }?>

					   <tr id="beforecomp">

							<td width="40px">&nbsp;</td>

							<td width="40px" align="right">

								<input type="button" name="adduser" value="Add More" onclick="addcompany();" class="input-medium"/>

							</td>

							<td width="40px" align="right" colspan="5">

								<input type="submit" name="employeement" value="Update" class="submit-green"/>

							</td>

						</tr>

                    </thead>

                </table>
</form>				