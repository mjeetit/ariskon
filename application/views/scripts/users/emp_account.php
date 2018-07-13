<form name="form8" action="" method="post"  enctype="multipart/form-data">
<table width="100%" id="table8">

                    <thead>

					<tr>

					 <th colspan="2" align="left">Account Detail</th>

					</tr>

					<tr class="odd">

					<td width="150px">Account Number</td><td><input type="text" name="account_number" value="<?php echo $this->UserDetail['AccountDetail']['account_number'];?>"  class="input-medium"/></td>

					</tr>

					<tr class="even">

					<td width="150px">Bank Name</td><td><input type="text" name="bank_name" value="<?php echo $this->UserDetail['AccountDetail']['bank_name'];?>"  class="input-medium"/></td>

					</tr>

					<tr class="odd">

					<td width="150px">Bank Branch Name</td><td><input type="text" name="bank_branch_name" value="<?php echo $this->UserDetail['AccountDetail']['bank_branch_name'];?>"  class="input-medium"/></td>

					</tr>

					<tr class="even">

					<td width="150px">Branch IFSC Code</td><td><input type="text" name="branch_IFSC_code" value="<?php echo $this->UserDetail['AccountDetail']['branch_IFSC_code'];?>"  class="input-medium"/></td>

					</tr>

					<tr class="odd">

					<td width="150px">Provident Fund Account Number</td><td><input type="text" name="prov_account_number" value="<?php echo $this->UserDetail['AccountDetail']['prov_account_number'];?>"  class="input-medium"/></td>

					</tr>

					<tr class="evevn">

						<td width="40px" align="right" colspan="2">

							<input type="submit" name="accounts" value="Update" class="submit-green"/>

						</td>

					</tr>

                    </thead>

                </table>
	</form>			