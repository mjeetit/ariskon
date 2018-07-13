 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Leave Requested</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
                                <tr>
									<th style="width:10%;text-align:center">Name</th>
									<th style="width:10%;text-align:center">Employee Code</th>
									<th style="width:10%;text-align:center">Department</th>
									<th style="width:10%;text-align:center">Designation</th>
									<th style="width:10%;text-align:center">Leave From</th>
									<th style="width:10%;text-align:center">Leave To</th>
									<th style="width:10%;text-align:center">Request Date</th>
									<th style="width:10%;text-align:center">Approved By</th>
									<th style="width:10%;text-align:center">Rejected By</th>
									<th style="width:10%;text-align:center" id="noClass1">Action</th>
                                </tr>
                                <tr id="filterrow">
                                    <th style="width:10%;text-align:center">Name</th>
                                    <th style="width:10%;text-align:center">Employee Code</th>
                                    <th style="width:10%;text-align:center">Department</th>
                                    <th style="width:10%;text-align:center">Designation</th>
                                    <th style="width:10%;text-align:center" id="date1">Leave From</th>
                                    <th style="width:10%;text-align:center" id="date2">Leave To</th>
									<th style="width:10%;text-align:center" id="date3">Request Date</th>
                                    <th style="width:10%;text-align:center">Approved By</th>
                                    <th style="width:10%;text-align:center">Rejected By</th>
                                    <td style="width:10%;background-color:#eee" ></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->leaverequested){
								for($i=0;$i<count($this->leaverequested);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
						<td align="center"><?php echo $this->leaverequested[$i]['first_name'].' '.$this->leaverequested[$i]['last_name']?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['employee_code'];?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['designation_name'];?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['leave_from'];?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['leave_to'];?></td>
						<td align="center"><?php echo $this->leaverequested[$i]['request_date'];?></td>
						<td align="center"><?php echo substr($this->ObjModel->LeaveApprovedByUsers($this->leaverequested[$i]['approved_approval']),0,-1);?></td>
						<td align="center"><?php echo substr($this->ObjModel->LeaveApprovedByUsers($this->leaverequested[$i]['rejected_approval']),0,-1);?></td>
						<td align="center">
                                                    <?php if($this->leaverequested[$i]['final_approval']==1){ echo 'Approved';}else{?>
						<a href="<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'approve','UserID'=>$this->leaverequested[$i]['user_id'],'request_id'=>$this->leaverequested[$i]['request_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/tick-circle.gif" title="Approve" /></a>&nbsp;
						<?php } ?>
                                                </td>
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
 
        </div>
        <div class="clear"></div>
    </div>
<script type="text/javascript">
function fancyboxopenfor(url){
 $.fancybox({
        "width": "70%",
        "height": "100%",
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
        $(this).html( '<input type="text" id="simple" placeholder="Search '+title+'" />' );
    } );
  $('#myTable thead tr#filterrow th#date1').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-1" class="min" style="width:60%"  placeholder="Date" />' );
    } );
   $('#myTable thead tr#filterrow th#date2').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-2" class="max" style="width:60%"  placeholder="Date" />' );
    } );
	$('#myTable thead tr#filterrow th#date3').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-3" class="max" style="width:60%"  placeholder="Request Date" />' );
    } );
    // DataTable
$('#datepicker-1').datepicker( {
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
    $('#datepicker-1').change( function() { table.draw(); } );
    //$('#datepicker-1').keyup( function() { table.draw(); } );

    $('#datepicker-2').change( function() { table.draw(); } );
   // $('#datepicker-2').keyup( function() { table.draw(); } );
});
$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {

        var iFini = $('#datepicker-1').val();
        var iFfin = $('#datepicker-2').val();
        if (typeof(iFini)=="undefined"){
            iFini="";
        }
        if (typeof(iFfin)=="undefined"){
            iFfin="";
        }
        var iStartDateCol =4;
        var iEndDateCol =5;
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