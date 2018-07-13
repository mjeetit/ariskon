 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Expense Request List</span></h2>
                    
                    <div class="module-table-body">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr><td colspan="11">
					       <form action="" method="get">		
							<table>
							<tr>
							 <td>Select Employee</td>
							 <td><?php  echo $this->HtmlSelectUser('user_id',$this->expenseusers,$this->filter['user_id'],array('class'=>'input-medium'));?></td>
							 <td>From Month&nbsp;<input name="from_date" id="from_date" value="<?php echo $this->filter['from_date']?>"  class="input-short" /></td>
							 <td>To Month&nbsp;<input name="to_date" id="to_date"  class="input-short" value="<?php echo $this->filter['to_date']?>" /></td>
							 <td><input type="submit" name="search" class="submit-green" value="Search" /></td>
							 <td><?php if($_SESSION['AdminLoginID']==1){ ?><input type="submit" name="export_expense" class="submit-green" value="Export Approved Expense" /><?php } ?></td>
							</tr>
						 </table>
						 </form>
						 </td>
						 </tr>	
                                <tr>
									<th style="width:15%">Employee Name</th>
									<th style="width:15%">Employee Code</th>
									<th style="width:15%">Department</th>
									<th style="width:15%">Designation</th>
									<th style="width:15%">Headquater</th>
									<th style="width:15%">Expanse Amount</th>
									<th style="width:15%">Fare Amount</th>
									<th>Mixed Amount</th>
									<th style="width:15%">Approve Ammount</th>
									<th style="width:15%">Expense<span>Month</span></th>
									<th style="width:5%" id="noClass1">Action</th>
                                </tr>
                                 <tr id="filterrow">
									<th style="width:15%">Employee Name</th>
									<th style="width:15%">Employee Code</th>
									<th style="width:15%">Department</th>
									<th style="width:15%">Designation</th>
									<th style="width:15%">Headquater</th>
									<th style="width:15%">Expanse Amount</th>
									<th style="width:15%">Fare Amount</th>
									<th>Mixed Amount</th>
									<th style="width:15%">Approve Ammount</th>
									<th style="width:15%" id="month">Expense <span>Month</span></th>
                                    <td style="width:10%;background-color:#eee" ></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->expenserequest){
								for($i=0;$i<count($this->expenserequest);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
						
						<td align="center"><?php echo $this->expenserequest[$i]['first_name'].' '.$this->expenserequest[$i]['last_name']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['employee_code']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['department_name']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['designation_name']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['headquater_name']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['EXPAMT']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['FARE']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['Mixed']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['APPAMT']?></td>
						<td align="center"><?php echo $this->expenserequest[$i]['EXPMONTH']?></td>

						<td align="center"><a href="<?php echo $this->url(array('controller'=>'Expense','action'=>'approve','user_id'=>$this->expenserequest[$i]['user_id'],'month'=>$this->expenserequest[$i]['EXPMONTH']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
								</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="3">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
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
        $(this).html( '<input type="text" id="datepicker-1" style="width:60%"  placeholder="Month" />' );
    } );
    // DataTable
$('#datepicker-1').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'MM-yy',
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
 });
  $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
    });
});
</script>			
