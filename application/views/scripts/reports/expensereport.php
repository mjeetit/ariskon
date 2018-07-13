 <?php
 //echo print_r($_SESSION);die;
 ?>
 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Expense Report</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="get">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<?php if($_SESSION['AdminLoginID']!=1){?>
							<tr>
								<?php if($_SESSION['AdminDesignation']<8){?>
								<td colspan="4">
									<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'expensereport','Mode'=>'All'))?>" class="button">
										<span>All Report<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="All Report" /></span>
									</a>
									&nbsp;
									<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'expensereport','Mode'=>'self'))?>" class="button">
										<span>Self Report<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="button" /></span>
									</a>
								</td>
								<?php }
								?>
								<td colspan="2">
									<input type="hidden" name="export_option" value="2"/>
									<input type="hidden" name="user_id" value="<?=$_SESSION['AdminLoginID']?>"/>
									<input type="submit" name="export" class="submit-green" value="Export" />
								</td>
							</tr>
							<?php } ?>
                              <?php if($_SESSION['AdminLoginID']==1){  //echo "<pre>";print_r(CommonFunction::getAssociative($this->empandHQ['RBM'],'user_id','name'));die;?>
                                <tr>
                                 <td colspan="2">RBM &nbsp;<?php  echo $this->htmlSelect('user_id',CommonFunction::getAssociative($this->empandHQ['RBM'],'user_id','name'),$this->filter['user_id'],array('class'=>'input-medium'));?></td>
                                 <td colspan="2">Department &nbsp;<?php  echo $this->htmlSelect('department_id',CommonFunction::getAssociative($this->filterdapartment,'department_id','department_name'),$this->filter['department_id'],array('class'=>'input-medium'));?></td>
                                 <td colspan="2">Designation &nbsp;<?php  echo $this->htmlSelect('designation_id',CommonFunction::getAssociative($this->filterdesignation,'designation_id','designation_name'),$this->filter['designation_id'],array('class'=>'input-medium'));?></td>
                                 <td colspan="2">From Date<br /><input name="from_date" id="from_date" value="<?php echo $this->filter['from_date']?>"  class="input-short" /></td>
				 <td colspan="2">To Date<br /><input name="to_date" id="to_date" value="<?php echo $this->filter['to_date']?>"  class="input-medium" /></td>
                               </tr>
							   <tr>
                                 <td colspan="2">ABM &nbsp;<?php  echo $this->htmlSelect('tocken_abm',CommonFunction::getAssociative($this->empandHQ['ABM'],'user_id','name'),$this->filter['tocken_abm'],array('class'=>'input-medium'));?></td>
                                 <td colspan="2">BE &nbsp;<?php  echo $this->htmlSelect('tocken_be',CommonFunction::getAssociative($this->empandHQ['BE'],'user_id','name'),$this->filter['tocken_be'],array('class'=>'input-medium'));?></td>
                                 <td colspan="2">Headquater &nbsp;<?php  echo $this->htmlSelect('headquater_id',CommonFunction::getAssociative($this->empandHQ['HQ'],'headquater_id','headquater_name'),$this->filter['headquater_id'],array('class'=>'input-medium'));?></td>
								 <td colspan="2">
								 <input type="submit" name="search" class="submit-green" value="Search" /></td>
								 <td colspan="2">
								 <select name="export_option">
								 <option value="1">HQ Wise</option>
								 <option value="2">Employee Wise</option>
								 </select><br />
								 <input type="submit" name="export" class="submit-green" value="Export" /></td>
                               </tr>
                              <?php } ?>
                                <tr>
									<th style="width:3%; text-align:center" id="noClass1">#</th>
									<th style="width:10%; text-align:center">Name</th>
									<th style="width:10%; text-align:center">Employee Code</th>
									<th style="width:8%; text-align:center">Designation</th>
									<th style="width:8%; text-align:center">HQ</th>
									<th style="width:10%; text-align:center">Total Expense</th>
									<th style="width:8%; text-align:center">Total Approve</th>
									<th style="width:10%; text-align:center"><span> Month </span><span> Of </span><span> Expense </span></th>
									<th style="width:8%; text-align:center" id="noClass2">Action</th>
                                </tr>
                                <tr id="filterrow">
                                    <td style="width:3%;background-color:#eee"></td>
                                    <th style="width:10%; text-align:center">Name</th>
									<th style="width:10%; text-align:center">Employee Code</th>
									<th style="width:8%; text-align:center">Designation</th>
									<th style="width:8%; text-align:center">HQ</th>
									<th style="width:10%; text-align:center">Total Expense</th>
									<th style="width:8%; text-align:center">Total Approve</th>
									<th style="width:10%; text-align:center" id="month">Month Of Expense</th>
                                    <td style="width:8%;background-color:#eee" ></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->expensesrep['Records']){
								for($i=0;$i<count($this->expensesrep['Records']);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="user_id" value="<?php echo $this->expensesrep['Records'][$i]['user_id']?>" /></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['first_name'].' '.$this->expensesrep['Records'][$i]['last_name'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['employee_code'];?></td>
							
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['designation_name'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['headquater_name'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['expense']+$this->expensesrep['Records'][$i]['fare_amount']+$this->expensesrep['Records'][$i]['mixed_amount'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['approve_am'];?></td>
							<td align="center"><?php echo $this->expensesrep['Records'][$i]['expense_date'];?></td>
							<td align="center"><a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'expensedetails','user_id'=>$this->expensesrep['Records'][$i]['user_id'],'month'=>$this->expensesrep['Records'][$i]['expense_date']),'default',true);?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a></td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="6">No Record Found!...</td>
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
$(function() {
		$("#from_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		$("#to_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});

	});
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
 $('#myTable thead tr#filterrow th#month').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-1" style="width:80%"  placeholder="Month" />' );
    } );
    // DataTable
$('#datepicker-1').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
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