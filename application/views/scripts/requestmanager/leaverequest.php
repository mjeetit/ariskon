 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Leave Request</span></h2>

                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
						      <tr>
							    <td colspan="8">
    							     <a href="<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'addleave'),'default',true)?>" class="button add">
                            	       <span>Add Leave Request<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Leave Request" /></span>
                                    </a>
					            </td>
							   </tr>
                                <tr>
                                    <th style="width:10%">Applied Date</th>
									<th style="width:10%">Leave From</th>
									<th style="width:10%">Leave To</th>
									<th style="width:10%">Leave Days</th>
									<th style="width:10%">Approved By</th>
									<th style="width:10%">Rejected By</th>
                                    <th style="width:10%">Remarks</th>
									<th style="text-align:center" id="noClass1">Request Status</th>
                                </tr>
                                <tr id="filterrow">
                                    <th style="width:10%" id="date1">Applied Date</th>
                                    <th style="width:10%;" id="date2">Leave From</th>
                                    <th style="width:10%;" id="date3">Leave To</th>
                                    <th style="width:10%">Leave Days</th>
                                    <th style="width:10%">Approved By</th>
                                    <th style="width:10%">Rejected By</th>
                                    <th style="width:10%">Remarks</th>
                                    <td style="width:10%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(count($this->leavelists) > 0) { foreach($this->leavelists as $leavelist) { ?>
								<tr>
								<td align="left"><?=$leavelist['request_date'];?></td>
								<td align="center"><?=$leavelist['leave_from'];?></td>
								<td align="center"><?=$leavelist['leave_to'];?></td>
								<td align="center"><?=$leavelist['total_days'];?></td>
								<td align="center"><?php echo substr($this->ObjModel->LeaveApprovedByUsers($leavelist['approved_approval']),0,-1);?></td>
						<td align="center"><?php echo substr($this->ObjModel->LeaveApprovedByUsers($leavelist['rejected_approval']),0,-1);?></td>
                                                                <td align="center"><?=wordwrap($leavelist['remarks'],20);?></td>
								<td align="center"><?=($leavelist['final_approval_auth']=='1')?'Approved':''?></td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="7">No leave request found till now !!</td>
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
        $(this).html( '<input type="text" id="simple" placeholder="Search '+title+'" />' );
    } );
    $('#myTable thead tr#filterrow th#date1').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="simple" class="datepicker-1"  style="width:60%"  placeholder="Date" />' );
    } );
    $('#myTable thead tr#filterrow th#date2').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-2"  style="width:60%"  placeholder="Date From" />' );
    } );
   $('#myTable thead tr#filterrow th#date3').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-3" style="width:60%"  placeholder="Date To" />' );
    } );
    // DataTable
$('.datepicker-1').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
$('#datepicker-2').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
$('#datepicker-3').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
    // Apply the filter
    $("#myTable thead input[id=simple]").on( 'keyup change', function () {
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
    var table = $('#myTable').DataTable();
    // Add event listeners to the two range filtering inputs
    $('#datepicker-2').change( function() { table.draw(); } );
    $('#datepicker-3').change( function() { table.draw(); } );
});
$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {

        var iFini = $('#datepicker-2').val();
        var iFfin = $('#datepicker-3').val();
        if (typeof(iFini)=="undefined"){
            iFini="";
        }
        if (typeof(iFfin)=="undefined"){
            iFfin="";
        }
        var iStartDateCol =1;
        var iEndDateCol =2;
        iFini=iFini.substring(0,4) + iFini.substring(5,7)+ iFini.substring(8,10);
        iFfin=iFfin.substring(0,4) + iFfin.substring(5,7)+ iFfin.substring(8,10);

        var datofini=aData[iStartDateCol].substring(0,4) + aData[iStartDateCol].substring(5,7)+ aData[iStartDateCol].substring(8,10);
        var datoffin=aData[iEndDateCol].substring(0,4) + aData[iEndDateCol].substring(5,7)+ aData[iEndDateCol].substring(8,10);

        if ( iFini === "" && iFfin === "" )
        {
            return true;
        }
        else if ( iFini <= datofini && iFfin === "")
        {
            return true;
        }
        else if ( iFfin >= datoffin && iFini === "")
        {
            return true;
        }
        else if (iFini <= datofini && iFfin >= datoffin)
        {
            return true;
        }
        return false;
    }
);
</script>   