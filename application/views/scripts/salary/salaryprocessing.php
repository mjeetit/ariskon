 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Salary Processing</span></h2>
                    
                    <div class="module-table-body">
					 <!--<form method="get" action="" name="filter" id="filter"> 
					 <table id="myTable1" class="tablesorter">
                        	<thead>
					 <tr>
							<th colspan="12" align="center">Search Option</th></tr>
							<tr><td>Employee</td><td><?php  echo $this->htmlSelectUser('user_id',$this->filteruser,$this->filter['user_id'],array('class'=>'input-medium'));?></td>
							<td>Designation</td><td><?php  echo $this->htmlSelect('designation_id',CommonFunction::getAssociative($this->filterdesignation,'designation_id','designation_name'),$this->filter['designation_id'],array('class'=>'input-medium'));?></td>
							<td>Serch Word</td><td><input type="text" name="search_word" value="<?php echo $this->filter['search_word']?>" /></td>
							<td><input type="submit" name="search" value="Serach"  class="submit-green" /></td></tr>
					</thead>
					</table>
					</form>-->		
                    <form method="post" action="" name="salary" id="salary"> 
					<input type="hidden" name="salary_date" value="<?php echo $this->ObjModel->FromDate;?>" />
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr><td colspan="13">Notice: Salary will Process Between <?php echo $this->processingdate['from_date']; ?> to <?php echo $this->processingdate['to_date'];?></td></tr>
							<tr>

						<td colspan="5">Generate Salary:<select name="Mode" onchange="Onchageformsubmit('salary')">

					   <option value="">--Select--</option>

					   <option value="Bulk">Processed</option>

					   </select>
 
					   </td>
					   <td colspan="2"><a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'addarrier'),'default',true)?>" class="button">
                        	<span>Add Arrier<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Arrier" /></span>
                        </a></td>
					   <td colspan="2"><a href="<?php echo $this->url(array('controller'=>'Expense','action'=>'getexpenselist'),'default',true)?>" class="button">
                        	<span>Expense List<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Expense List" /></span>
                        </a></td>
					   <td colspan="2"><input type="submit" name="recalculate" value="SyncExpense" class="submit-green" /></td>
					   <td colspan="2"><input type="submit" name="Exportsalary" value="ExportSalary" class="submit-green" /></td>	

						</tr>

                                <tr>
									<th style="width:5%" id="noClass1">#</th>
									<th style="width:10%">Employee Name</th>
									<th style="width:10%">Employee Code</th>
									<th style="width:10%">Designation</th>
									<th style="width:10%">Month</th>
									<th style="width:10%">Paid Days</th>
									<th style="width:10%">Earning Total</th>
									<th style="width:10%">ESI</th>
									<th style="width:10%">PF</th>
									<th style="width:10%">Detection Total</th>
									<th style="width:10%">Expense</th>
									<th style="width:10%">Net Salary</th>
									<th style="width:10%" id="noClass2">Action</th>
                                </tr>
                                <tr id="filterrow">
                                	<td style="width:5%;background-color:#eee"></td>
                                	<th style="width:10%">Employee Name</th>
									<th style="width:10%">Employee Code</th>
									<th style="width:10%">Designation</th>
									<th style="width:10%">Month</th>
									<th style="width:10%">Paid Days</th>
									<th style="width:10%">Earning Total</th>
									<th style="width:10%">ESI</th>
									<th style="width:10%">PF</th>
									<th style="width:10%">Detection Total</th>
									<th style="width:10%">Expense</th>
									<th style="width:10%">Net Salary</th>
                                	<td style="width:5%;background-color:#eee"></td>	
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 $records = $this->salarylist['Records'];
							 if($records){
								for($i=0;$i<count($records);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td width="10px" <?php echo $color;?>><input type="checkbox" name="user_id[]" style="width:10px" value="<?php echo $records[$i]['user_id'];?>" />
								
								</td>
								<td align="left"><?php echo $records[$i]['name'];?></td>
								<td align="left"><?php echo $records[$i]['employee_code'];?></td>
								<td align="center"><?php echo $records[$i]['designation_name'];?></td>
								<?php 
								 $this->ObjModel->_salaryDate = $this->ObjModel->FromDate;
								 
								 $this->ObjModel->CalculateSalary($records[$i]['user_id'],'test.pdf');
								 //echo "<pre>";print_r($this->ObjModel->_salaryData);die;
								 ?>
								<td align="center"><?php echo  date('M-Y',strtotime($this->ObjModel->FromDate));?></td>
								<td align="center"><?php echo  $this->ObjModel->_salaryData['Paid_days'];?></td>
								<td align="center"><?php echo  number_format($this->ObjModel->_salaryData['EarningsTotal'],2);?></td>
								<td align="center"><?php echo  number_format($this->ObjModel->_salaryData['EsiAmount'],2);?></td>
								<td align="center"><?php echo  number_format($this->ObjModel->_salaryData['ProvidentAmount'],2);?></td>
								<td align="center"><?php echo  number_format($this->ObjModel->_salaryData['DeductionsTotal'],2);?></td>
								<td align="center"><?php echo  number_format($this->ObjModel->_salaryData['ExpenseTotal'],2);?></td>
								<td align="center"><?php echo  number_format($this->ObjModel->_salaryData['GrandTotal'],2);?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'editsalary','user_id'=>$records[$i]['user_id'],'salary_date'=>$this->ObjModel->FromDate),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
								| <a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'salaryprocessing','Mode'=>'Single','user_id'=>$records[$i]['user_id'],'salary_date'=>$this->ObjModel->FromDate,'Type'=>'Final'),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="Generate Salary Slip" /></a>&nbsp;|
								 <a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'salaryprocessing','Mode'=>'Single','user_id'=>$records[$i]['user_id'],'salary_date'=>$this->ObjModel->FromDate,'Type'=>'Test'),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/test_salary.png" title="Test Salary Slip" /></a>
								 &nbsp;|
								 <a onclick="fancyboxopenforchequeno('<?php echo $this->url(array('controller'=>'Salary','action'=>'chequenumber','user_id'=>$records[$i]['user_id'],'salary_date'=>$this->ObjModel->FromDate),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/cheque-icon.png" title="Cheque Number" /></a>
						</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="13">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        </form>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
<script type="text/javascript">
function fancyboxopenforchequeno(url){
 $.fancybox({
        "width": "40%",
        "height": "40%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
 $(function(){
 var table = $('#myTable').DataTable({
   pageLength: 30,
   "order": [[ 0, "desc" ]],
   orderCellsTop: true
  });
     // Setup - add a text input to each footer cell
    $('#myTable thead tr#filterrow th').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable

     
    // Apply the filter
    $("#myTable thead input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );
    $("#noClass1").removeClass();
    $("#noClass2").removeClass();
 });
  $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
        $("#noClass2").removeClass();
    });
});
 </script>