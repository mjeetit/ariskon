<div class="grid_12">
	<!-- Example table -->
	<div class="module">
		<h2><span>Attandance List</span></h2>
		<div class="module-table-body">
			<form action="" method="post" name="attandanceForm" id="attandanceForm">
			<input type="hidden" name="Mode" id="Mode" />
			<input type="hidden" name="attandance_id" id="attandance_id" />
			<table id="myTable" class="tablesorter">
				<thead>
				<tr>
				<td colspan="9">
				 <a href="<?php echo $this->url(array('controller'=>'Attandance','action'=>'uploadeattandance'),'default',true)?>" class="button add">
				<span>Uploade Attandance<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="New article" /></span>
			</a>&nbsp;
			<a href="<?php echo $this->url(array('controller'=>'Attandance','action'=>'manualattandance'),'default',true)?>" class="button add">
				<span>Manual Attandance<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="New article" /></span>
			</a>&nbsp;
			<a href="<?php echo Bootstrap::$baseUrl.'public/AttandanceSheet/AttandanceFormat.xls';?>" class="button add">
				<span>Download Format<img src="<?php echo IMAGE_LINK;?>/download.png"  width="12" height="9" alt="Download Format" /></span>
			</a>
		</td>
				</tr>
					<tr>
						<th style="width:15%">Employee Code</th>
						<th style="width:15%">Department</th>
						<th style="width:15%">Designation</th>
						<th style="width:7%">Month</th>
						<th style="width:10%">Year</th>
						<th style="width:13%">Total Present</th>
						<th style="width:13%">Total Absent</th>
						<th style="width:13%">Total Leave</th>
						<th style="width:5%" id="noClass1">Action</th>
					</tr>
					<tr id="filterrow">
						<th style="width:15%">Employee Code</th>
						<th style="width:15%">Department</th>
						<th style="width:15%">Designation</th>
						<th style="width:7%">Month</th>
						<th style="width:10%" id="year">Year</th>
						<th style="width:13%">Total Present</th>
						<th style="width:13%">Total Absent</th>
						<th style="width:13%">Total Leave</th>
						<td style="width:5%;background-color:#eee"></td>
					</tr>
				</thead>
				<tbody>
				 <?php 
				 if($this->Attandancelist){
					for($i=0;$i<count($this->Attandancelist);$i++){
					  $class = ($i%2==0)?'even':'odd';
					  $totalcount = @array_count_values($this->Attandancelist[$i]);
					?>
					<tr class="<?php echo  $class;?>">
					<td align="center"><?php echo $this->Attandancelist[$i]['employee_code']?></td>
					<td align="center"><?php echo $this->Attandancelist[$i]['department_name']?></td>
					<td align="center"><?php echo $this->Attandancelist[$i]['designation_name']?></td>
					<td align="center"><?php echo $this->Attandancelist[$i]['month']?></td>
					<td align="center"><?php echo $this->Attandancelist[$i]['year']?></td>
					<td align="center"><?php echo $totalcount['P']?></td>
					<td align="center"><?php echo $totalcount['A']?></td>
					<td align="center"><?php echo $totalcount['L']?></td>
					<td align="center"><a href="<?php echo $this->url(array('controller'=>'Attandance','action'=>'editattandance','attandance_id'=>$this->Attandancelist[$i]['attandance_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>&nbsp;|&nbsp;<a href="javascript:void();" onclick="deleteattandance('<?php echo $this->Attandancelist[$i]['attandance_id']?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" /></a>
					</td>
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

<script  type="text/javascript">
  function deleteattandance(attandance_id){
     if(confirm('Are you sure? You want to delete This?')){
     $("#Mode").val('Delete');
	 $("#attandance_id").val(attandance_id);
	 $("#attandanceForm").submit();
	}
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
        $(this).html( '<input type="text" placeholder="'+title+'" />' );
    } );
 	 $('#myTable thead tr#filterrow th#year').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-1" style="width:70%" placeholder="Year" />' );
    } );

 	$('#datepicker-1').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
    // DataTable

     
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