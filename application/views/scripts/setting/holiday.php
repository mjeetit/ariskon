 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Holidays</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="4">
							<a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addholiday'),'default',true)?>" class="button add"><span>Add New Holiday<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Salary Head" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:20%; text-align:center">Month</th>
									<th style="width:20%; text-align:center">Date</th>
									<th style="width:20%; text-align:center">Day</th>
									<th style="width:20%; text-align:center">Holiday Detail</th>
                                </tr>
                                <tr id="filterrow">
                                    <th style="width:20%; text-align:center" id="month">Month</th>
                                    <th style="width:20%; text-align:center" id="date">Date</th>
                                    <th style="width:20%; text-align:center">Day</th>
                                    <th style="width:20%; text-align:center">Holiday Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->hilidayslist){
								for($i=0;$i<count($this->hilidayslist);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
						<td align="center"><?php echo date('M-Y',strtotime($this->hilidayslist[$i]['month']))?></td>
						<td align="center"><?php echo $this->hilidayslist[$i]['holiday_date']?></td>
						<td align="center"><?php echo $this->hilidayslist[$i]['day']?></td>
						<td align="center"><?php echo $this->hilidayslist[$i]['holiday_name']?></td>

								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="3">No Record Found!...</td>
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

    $('#myTable thead tr#filterrow th#date').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-1" style="width:80%" placeholder="Search '+title+'" />' );
    } );

     $('#myTable thead tr#filterrow th#month').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-2" style="width:80%" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    $( "#datepicker-1" ).datepicker({
        dateFormat:"yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
    $( "#datepicker-2" ).datepicker({
        dateFormat:"M-yy",
        changeMonth: true,
        changeYear: true,
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
 });
 </script>