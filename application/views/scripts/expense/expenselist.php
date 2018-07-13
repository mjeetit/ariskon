	 <div class="grid_12"> 
	            <!-- Example table -->
                <div class="module"> 
                	<h2><span>Expense List</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="7">
						    <a href="<?php echo $this->url(array('controller'=>'Expense','action'=>'addexpense'),'default',true)?>" class="button add">
                        	<span>Add Expense<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Region" /></span>
                        </a>
						<?php if($_SESSION['AdminLoginID']==1){?>&nbsp;&nbsp;
							<a href="<?php echo $this->url(array('controller'=>'Expense','action'=>'manualexpense'),'default',true)?>" class="button add">
                        	<span>Manual Expense<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Manual Expense" /></span>
                        </a>
						<?php } ?>
					</td>
					</tr>
                             
						<tr>
						<th>Name</th>
						<th>Expense Amount</th>
						<th>Fare Amount</th>
						<th>Mixed Amount</th>
						<th>Approve Amount</th>
						<th>Expense Month</th>
						<th>Action</th>
						</tr>
						<tr id="filterrow">
							<th>Name</th>
							<th>Expense Amount</th>
							<th>Fare Amount</th>
							<th>Mixed Amount</th>
							<th>Approve Amount</th>
							<th id="month">Expense Month</th>
							<td style="background-color:#eee"></td>
						</tr>
						</thead>
						<tbody>
						<?php if(!empty($this->expenselist)){ 
						foreach($this->expenselist as $i=>$expenses){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $expenses['first_name']?></td>
						 <td><?php echo $expenses['Amount']?></td>
						  <td><?php echo $expenses['Fare']?></td>
						  <td><?php echo $expenses['Mixed']?></td>
						 <td><?php echo $expenses['Approve']?></td>
						 <td><?php echo $expenses['Month']?></td>
						 <td align="center">
						 <a href="<?php echo $this->url(array('controller'=>'Expense','action'=>'viewexpense','month'=>$expenses['Month']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
						 </td>
						 </tr>
						 <?php } }?>
                       	</tbody>
                        </table>
                       
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 --> 
<script type="text/javascript">
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
        $(this).html( '<input type="text" id="datepicker-1" style="width:80%" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    $( "#datepicker-1" ).datepicker({
        dateFormat:"MM-yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
    // Apply the filter
    $("#myTable thead input").on('keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );
 });
 </script>
