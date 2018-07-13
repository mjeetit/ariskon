<form name="form6" action="" method="post"  enctype="multipart/form-data">
<table width="100%" id="table6">

                    <thead>

					<?php if(!empty($this->UserDetail['Document'])){ ?>

					<tr>

					 <th colspan="2" align="left">Uploaded Document Detail</th>

					</tr>

					<?php  foreach($this->UserDetail['Document'] as $documnrts){

					?>

					<tr class="odd">

					 <td><?php $doc = $this->ObjModel->getDocumentsType($documnrts['document_type']);

					 echo $doc[0]['type_name'];

					  ?></td><td><?php echo $documnrts['file_name']?></td>

					</tr>

					<?php } } ?>

					<tr>

					 <th colspan="2" align="left">Document Detail</th>

					</tr>

					<tr>

						<td colspan="2" align="center">

								<table id="document">

								<tr class="odd">

								<td>Documents Type

								<select name="document_type[]">

								<option value="">--Select Category--</option>

								<?php $doctypes = $this->ObjModel->getDocumentsType();

								foreach($doctypes as $doc){

								?>

								<option value="<?php echo $doc['type_id']?>"><?php echo $doc['type_name']?></option>

								<?php } ?>

								</select>

								</td>

								<td><input type="file" name="documnet[]" /></td>

								</tr>

								</table>

						 </td>

					</tr>

				   <tr>	

							<td width="40px"><input type="button" name="add_doc" value="Add More" onclick="documnet_add()"/></td>

							<td width="40px" align="right">

								<input type="submit" name="documents" value="Update" class="submit-green"/>

							</td>

					</tr>

                    </thead>

                </table>
</form>				