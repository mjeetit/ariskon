<div class="grid_12">
	<!-- Example table -->
	<div class="module">
		<h2><span>Attandance List</span></h2>
		<div class="module-table-body">
			<form action="" method="post" name="attandanceForm" id="attandanceForm">
			
			<table id="myTable" class="tablesorter">
				<thead>
				<?php if($_SESSION['AdminLoginID']!=1){?>
							<tr>
							<td colspan="2">
							
							<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'attandancereport','Mode'=>'All'))?>" class="button">
							<span>All Report<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="All Report" /></span></a>
							&nbsp;
							<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'attandancereport','Mode'=>'self'))?>" class="button">
							<span>Self Report<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="button" /></span></a></td>
							</tr>
				<?php } ?>
				</tr>
					<tr>
						<th style="width:15%;text-align:center">Employee Code</th>
						<th style="width:15%;text-align:center">Department</th>
						<th style="width:15%;text-align:center">Designation</th>
						<th style="width:7%;text-align:center">Month<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>
						<th style="width:13%;text-align:center">Present Day</th>
						<th style="width:13%;text-align:center">Absent Day</th>
						<th style="width:13%;text-align:center">Leave Day</th>
						<th style="width:13%;text-align:center">Half Day</th>
						<th style="width:13%;text-align:center">Leave Without Pay</th>
					</tr>
					<tr id="filterrow">
	                    <th style="width:15%;text-align:center">Employee Code</th>
						<th style="width:15%;text-align:center">Department</th>
						<th style="width:15%;text-align:center">Designation</th>
						<th style="width:7%;text-align:center" id="month">Month</th>
						<th style="width:13%;text-align:center">Present Day</th>
						<th style="width:13%;text-align:center">Absent Day</th>
						<th style="width:13%;text-align:center">Leave Day</th>
						<th style="width:13%;text-align:center">Half Day</th>
						<th style="width:13%;text-align:center">Leave Without Pay</th>
                    </tr>
				</thead>
				<tbody>
				 <?php 
				 if($this->attandnceRep){
					for($i=0;$i<count($this->attandnceRep);$i++){
					  $class = ($i%2==0)?'even':'odd';
					  $totalcount = @array_count_values($this->attandnceRep[$i]);
					?>
					<tr class="<?php echo  $class;?>">
					<td align="center"><?php echo $this->attandnceRep[$i]['employee_code']?></td>
					<td align="center"><?php echo $this->attandnceRep[$i]['department_name']?></td>
					<td align="center"><?php echo $this->attandnceRep[$i]['designation_name']?></td>
					<td align="center"><?php echo date('F Y',strtotime($this->attandnceRep[$i]['year'].'-'.$this->attandnceRep[$i]['month'].'-1'));?></td>
					<td align="center"><?php echo $totalcount['P']?></td>
					<td align="center"><?php echo $totalcount['A']?></td>
					<td align="center"><?php echo $totalcount['L']?></td>
					<td align="center"><?php echo $totalcount['HL']?></td>
					<td align="center"><?php echo $totalcount['H']?></td>
					
					</tr>
					<?php }} else{ ?>
					<tr>
					<td align="center" colspan="9">No Record Found!...</td>
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
        dateFormat: 'MM yy',
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
    });
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