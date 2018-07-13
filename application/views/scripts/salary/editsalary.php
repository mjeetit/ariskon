 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Edit Salary</span></h2>
                    
                    <div class="module-table-body">
                    	 <form name="form_salary" action="" method="post" id="form1"> 
							<input type="hidden" name="Update" value="<?php echo $this->editsalarydetail['Update']?>" />
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<tr><td colspan="2"><a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'salaryprocessing'),'default',true)?>" class="button">
							 <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a></td></tr>
                             
					<tr class="odd">

					 <th colspan="2" align="left">Salary Amount Detail</th>
						<?php 
						foreach($this->editsalarydetail as $key=>$amounts){
							//print_r($amounts);die;
						if($key==0){?>

					      <input type="hidden" name="date" id="date" value="<?php echo $amounts['date']?>" class="input-short"/>	

						<?php }

						$class = ($key%2==0)?'even':'odd';
								?>
						<tr class="<?php echo  $class;?>" id="tr_<?php echo $key; ?>">

						<?php if($amounts['salaryhead_id']!=100){ ?>  

							<td width="40px"><?php echo $this->ObjModel->getSalaryHeadName($amounts['salaryhead_id']);?></td> 

							<td width="40px"> <input type="text" name="amount[<?php echo $amounts['salaryhead_id']; ?>]" id="earning_amount" value="<?php echo $amounts['amount']?>" class="input-short"/>&nbsp;<a  onclick="deleteextrahead('<?php echo $this->url(array('controller'=>'Ajax','action'=>'deletehead','salaryhead_id'=>$amounts['salaryhead_id'],'user_id'=>$amounts['user_id'],'date'=>$amounts['date']),'default',true)?>','<?php echo $key; ?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" /></a></td>

							<?php }else{?>

							<td width="40px"><?php echo 'Loan';?></td> 

							<td width="40px">

							<input type="text" name="amount[<?php echo $amounts['salaryhead_id']; ?>]" id="loan_amount" value="<?php echo $amounts['amount']?>" class="input-short"/>

							<?php if($amounts['salaryheade_type']==2){ ?>

							X <input type="text" name="emi_time" id="emi_time" onblur="calcullate_emi()" value="1" class="input-short"/>  

						<?php }?>

							</td> 

							<?php }?>

						</tr>

						<?php }?> 
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
					    <tr class="odd">
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
						<tr class="odd"><td colspan="2"><input type="checkbox" name="edit_template" value="1" />&nbsp;Do you want Update Extra Head in Template</td><tr>
						<?php  $Arrears =  $this->ObjModel->getArrierSlalaryAmount($this->user_id,$amounts['date']);
						if(!empty($Arrears)){

					//print_r($Arrears);die;

						?>

							<tr>

						<th colspan="2" align="left">Arrears of <?php echo date('F Y',strtotime($Arrears[0]['date']))?></th>

						</tr>

						  <?php foreach($Arrears as $key=>$amounts){

						if($key==0){?>

					      <input type="hidden" name="arear_date" id="arear_date" value="<?php echo $Arrears[0]['date'];?>"/>	

						<?php }?>

					

						<tr>   

							<td width="40px"><?php echo $amounts['salary_title'];?></td> 

							<td width="40px"> <input type="text" name="arear_amount[<?php echo $amounts['salaryhead_id']; ?>]" id="earning_arear_amount" value="<?php echo $amounts['amount']?>" class="input-short"/>
					<input type="hidden" name="salaryhead_type[<?php echo $amounts['salaryhead_id']; ?>]" id="salaryhead_type" value="<?php echo $amounts['salaryheade_type']?>" class="input-short"/>		
							</td> 

						</tr>

						<?php } ?>

						<tr>

						<td colspan="2" align="left">Do you want to process this month <input type="checkbox" name="arear_status" <?php echo ($Arrears[0]['satus']==1)?'checked="checked"':'';?> value="<?php echo $Arrears[0]['date']?>"  class="input-short"/></td>

						</tr>

						<?php } ?>

						<tr>

						<td colspan="2" align="center"><input type="submit" name="save_salary" value="Update" class="submit-green" /></td>

						</tr>
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div>

<script>
   function deleteextrahead(url,id){
     if(confirm('Do You realy want to Delete This Head?')){
       $.ajax({
                url: url,
                success: function(data) {
                   //alert(data);
                   $("#tr_"+id).remove();
                }
               });
     }
   }
</script>


	