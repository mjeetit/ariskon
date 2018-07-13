<div class="grid_12">
                <div class="module">
                     <h2><span>Edit User</span></h2>
                     <div class="module-body">
                        
						<table width="70%" style="border:none">
							<thead> 
							 <tr>
							 <td colspan="2">
							 <a href="<?php echo $this->url(array('controller'=>'Users','action'=>'user'),'default',true)?>" class="button">
							 <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();" onclick="showform('1');" class="button"><span>Official Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('2');" class="button">
							  <span>Personal Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('3');" class="button">
							  <span>Education Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('4');" class="button">
							  <span>Employeement Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('5');" class="button">
							  <span>Salary Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('6');" class="button">
							  <span>Documents Detail<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Documents Detail" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('7');" class="button">
							  <span>Leave Approval<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Leave Approval" /></span>
                        </a>
							  <a href="javascript:void();"  onclick="showform('8');" class="button">
							  <span>Bank Account<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Bank Account" /></span>
                        </a>
						<a href="javascript:void();"  onclick="showform('9');" class="button">
							  <span>Location<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Location" /></span>
                        </a>
						<a href="javascript:void();"  onclick="showform('10');getexpensetemplateforedit('<?php echo $this->UserDetail['user_id']?>','<?php echo $this->UserDetail['parent_id'];?>','<?php echo $this->UserDetail['designation_id'];?>','<?php echo $this->UserDetail['department_id'];?>','<?php echo $this->UserDetail['bunit_id'];?>');" class="button">
							  <span>Expense<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Location" /></span>
                        </a>
						</td>
							 </tr>
						<tr> 
						
						
				<tr id="table1">		
				<td colspan="2" align="center"> 
			  <!--Official Detail-->
			    <?php include 'emp_officialdetail.php';?>
				</td>
				</tr>
                
				<tr id="table2">		
				<td colspan="2" align="center">  
			   <!--Personal Detail-->
			  <?php include 'emp_personaldetail.php';?>
				</td>
				</tr>
				<tr id="table3">		
				<td colspan="2" align="center"> 
			   <!--Education Detail-->	
				<?php include 'emp_educationdetail.php';?>
				</td>
				</tr>
				<tr id="table4">		
				<td colspan="2" align="center"> 
				<!--Employeement Detail-->	
				<?php include 'emp_employementdetail.php';?>
				</td>
				</tr>
				<tr id="table5">		
				<td colspan="2" align="center"> 
				<!--Salary  Detail-->	
				<?php include 'emp_salaary.php';?>
				</td>
				</tr>
				  
				<tr id="table6">		
				<td colspan="2" align="center"> 
			   <!--Document  Detail-->	
				<?php include 'emp_documents.php';?>
				</td>
				</tr>
				 	
				<tr  id="table7">		
				<td colspan="2" align="center"> 
			   <!--Leave Approval-->
				<?php include 'emp_leaves.php';?>
				</td>
				</tr>
			    
				<tr  id="table8">		
				<td colspan="2" align="center"> 
				 <!--Account Detail -->
				 <?php include 'emp_account.php';?>
				</td>
				</tr>
			    <tr id="table9">		
				<td colspan="2" align="center"> 
				<!--Location Detail -->
				 <?php include 'emp_location.php';?>
				</td>
				</tr>
			 <tr id="table10">		
			 <td colspan="2" align="center"> 	
			<!--Expense Detail-->
			 <?php include 'emp_expense.php';?>
				</td>
				</tr>
			</thead>
		</table>
	 </div> <!-- End .module-body -->

</div>  <!-- End .module -->
<div style="clear:both;"></div>
</div>
			
<script type="text/javascript">

$(document).ready(function() {

	disabledOnload();

});

function addeducation(){

  $("#beforetr").before('<tr><td width="40px" align="left"><select name="degree[]" id="degree" style="width:80px"><option value="Diploma">Diploma</option></select></td><td width="40px" align="left"> <input type="text" name="degree_name[]" id="degree_name" class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="collage[]" id="collage" class="input-medium"/></td><td width="40px" align="left"><input type="text" name="board[]" id="board" class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="per_mark[]" id="per_mark" class="input-medium"/></td><td width="40px" align="left" id="aftetd"> <input type="text" name="year_passing[]" id="year_passing" class="input-medium"/></td></tr>');

}

function addcompany(){

  $("#beforecomp").before('<tr><td width="40px" align="left"><input type="text" name="company[]" id="company" style="width:100px" class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="designation[]" id="designation" class="input-medium"/></td><td width="200px" align="left"><input type="text" name="from_year[]" id="from_year" style="width:40px" class="input-medium"/>yy<input type="text" name="from_month[]" id="from_month" style="width:40px" class="input-medium"/>mm</td><td width="200px" align="left"><input type="text" name="to_year[]" id="to_year" style="width:40px" class="input-medium"/>yy<input type="text" name="to_month[]" id="to_month" style="width:40px" class="input-medium"/>mm</td><td width="40px" align="left"> <input type="text" name="board[]" id="board" style="width:100px" class="input-medium"/></td><td width="40px" align="left"> <input type="text" name="per_mark[]" id="per_mark" style="width:100px" class="input-medium"/></td><td width="40px" align="left" id="aftetd"> <input type="text" name="year_passing[]" id="year_passing" class="input-medium"/></td></tr>');

}



changeStatusBusiness('<?php echo $this->UserDetail['bunit_id'];?>','<?php echo $this->UserDetail['department_id'];?>');

changeStatusDepartment('<?php echo $this->UserDetail['department_id'];?>','<?php echo $this->UserDetail['designation_id'];?>');

changeStatusdesignation('<?php echo $this->UserDetail['designation_id'];?>','<?php echo $this->UserDetail['parent_id'];?>','<?php echo $this->UserDetail['sub_designation_id'];?>');

editsalarytemplate('<?php echo $this->UserDetail['user_id']?>','<?php echo $this->UserDetail['bunit_id'];?>','<?php echo $this->UserDetail['department_id'];?>','<?php echo $this->UserDetail['designation_id'];?>');

Editleavedetail('<?php echo $this->UserDetail['user_id']?>','<?php echo $this->UserDetail['designation_id'];?>','<?php echo $this->UserDetail['parent_id'];?>','<?php echo $this->UserDetail['emp_type'];?>');

showprovident('<?php echo $this->UserDetail['provident']?>');

getNextRecord('<?php echo $this->UserDetail['bunit_id'];?>',5,'zone_id','<?php echo $this->UserDetail['Location']['zone_id'];?>');
getNextRecord('<?php echo $this->UserDetail['Location']['zone_id'];?>',6,'region_id','<?php echo $this->UserDetail['Location']['region_id'];?>');
getNextRecord('<?php echo $this->UserDetail['Location']['region_id'];?>',7,'area_id','<?php echo $this->UserDetail['Location']['area_id'];?>');
getNextRecord('<?php echo $this->UserDetail['Location']['area_id'];?>',8,'headquater_id','<?php echo $this->UserDetail['Location']['headquater_id'];?>');
getNextRecord('<?php echo $this->UserDetail['Location']['headquater_id'];?>',9,'city_id','<?php echo $this->UserDetail['Location']['city_id'];?>');
getNextRecord('<?php echo $this->UserDetail['Location']['city_id'];?>',10,'street_id','<?php echo $this->UserDetail['Location']['street_id'];?>');

getexpensetemplateforedit('<?php echo $this->UserDetail['user_id']?>','<?php echo $this->UserDetail['parent_id'];?>','<?php echo $this->UserDetail['designation_id'];?>','<?php echo $this->UserDetail['department_id'];?>','<?php echo $this->UserDetail['bunit_id'];?>');
getNextRecord1('<?php echo $this->UserDetail['Location']['region_id'];?>',11,'headquater_id','<?php echo $this->UserDetail['Location']['assign_headquater1'];?>');
getNextRecord2('<?php echo $this->UserDetail['Location']['region_id'];?>',11,'headquater_id','<?php echo $this->UserDetail['Location']['assign_headquater2'];?>');

$(function() {

		$("#doj").datepicker({

			showOn: "button",

			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",

			buttonImageOnly: true,

			dateFormat: "yy-mm-dd"

		});

		$("#dob").datepicker({

			showOn: "button",

			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",

			buttonImageOnly: true,

			dateFormat: "yy-mm-dd"

		});

});

</script>