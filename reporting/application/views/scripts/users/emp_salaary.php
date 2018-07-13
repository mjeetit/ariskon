<form name="form15" action="" method="post"  enctype="multipart/form-data">
<table width="100%">

                    <thead>

					<tr id="afterid">

					 <th colspan="2" align="left">Salary Detail</th>

					</tr>

					<tr>

					<td colspan="2">

					<table id="template"></table>

					</td>

					</tr>
					 <tr>
					<td><strong>Extra Earning Head</strong></td>
					<td><select id="" onchange="extrahead(this.value,1,'earnigtable');">
					 <option value="">--Select Head--</option>
					 <?php foreach($this->ObjModel->getSalaryhead() as $earning){ ?>
					   <option value="<?php echo $earning['salaryhead_id'];?>"><?php echo $earning['salary_title'];?></option>   
					 <?php } ?>
					</select></td>
					</tr>
						<tr>
						<td colspan="2">
						<table id="earnigtable"></table>
						</td>
						</tr>
					<tr>
					<td><strong>Extra Deduction Head</strong></td>
					<td><select id="" onchange="extrahead(this.value,2,'deducttable');">
					 <option value="">--Select Head--</option>
					 <?php foreach($this->ObjModel->getDetectionSalaryhead() as $deduction){ ?>
					   <option value="<?php echo $deduction['salaryhead_id'];?>"><?php echo $deduction['salary_title'];?></option>   
					 <?php } ?>
					</select></td>
					</tr>
						<tr>
						<td colspan="2">
						<table id="deducttable"></table>
						</td>
						</tr>
					   <tr>

							<td width="40px">&nbsp;</td>

							<td width="40px" align="right">

								<input type="submit" name="salary_deatil" value="Update" class="submit-green"/>

							</td>

						</tr>
						
						<tr><td colspan="2"><strong>CTC Change Date:</strong>
						<input type="text" name="ctc_change_date" id="ctc_change_date" class="input-short" />
						</td></tr>

                    </thead>

                </table>
	</form>	
<script>	
$(function() {

		$("#ctc_change_date").datepicker({

			showOn: "button",

			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",

			buttonImageOnly: true,

			dateFormat: "yy-mm-dd"

		});

});
</script>		