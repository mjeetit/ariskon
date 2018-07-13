<form name="form3" action="" method="post"  enctype="multipart/form-data"> 
<table width="100%">

                    <thead>

					<tr>

					 <th colspan="6" align="left">Education Detail</th>

						</tr>

						<tr class="odd"> 

						<td width="40px" align="left"><b>Degree</b></td><td width="40px" align="left"><b>Degree Name</b></td><td width="40px" align="left"><b>School/collage</b></td><td width="40px"><b>Board/University</b></td><td width="40px"><b>Percentage Mark/CGPA</b></td><td width="40px"><b>Year of Passing</b></td>

						</tr> 

						<?php $degrees = array('10 th','10+2','Graduation','Post Graduation');

						foreach($degrees as $key=>$degree){
							$class = ($key%2==0)?'even':'odd';?>
							<tr class="<?php echo  $class;?>">

						<td width="40px" align="left"><input type="text" name="degree[]" id="degree" value="<?php echo $degree;?>" readonly="readonly" style="width:80px" class="input-medium"/></td> 

						<td width="40px" align="left"> <input type="text" name="degree_name[]" id="degree_name" value="<?php echo $this->UserDetail['Education'][$key]['degree_name'];?>" class="input-medium"/></td>

						<td width="40px" align="left"> <input type="text" name="collage[]" id="collage" value="<?php echo $this->UserDetail['Education'][$key]['collage'];?>" class="input-medium"/></td>

						<td width="40px" align="left"> <input type="text" name="board[]" id="board" value="<?php echo $this->UserDetail['Education'][$key]['board'];?>" class="input-medium"/></td>

						 <td width="40px" align="left"> <input type="text" name="per_mark[]" id="per_mark" value="<?php echo $this->UserDetail['Education'][$key]['per_mark'];?>" class="input-medium"/></td> 

						 <td width="40px" align="left" id="aftetd"> <input type="text" name="year_passing[]" id="year_passing" value="<?php echo $this->UserDetail['Education'][$key]['year_passing'];?>" class="input-medium"/></td> 

						</tr> 

						<?php }?>

					   <tr id="beforetr">

							<td width="40px">&nbsp;</td>

							<td width="40px" align="right">

								<input type="button" name="adduser" value="Add More" onclick="addeducation();"/>

							</td>

							<td width="40px" align="right" colspan="4">

								<input type="submit" name="education" value="Update" class="submit-green"/>

							</td>

						</tr>

                    </thead>

                </table>
</form>				