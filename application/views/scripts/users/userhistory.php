 <div class="grid_12">
    <!-- Example table -->
    <div class="module">
    	<h2><span>Employee History</span></h2>
        
        <div class="module-table-body">
        	<form action="">
                <table id="myTable" class="tablesorter">
            	   <thead>
				        <tr> 
				            <td colspan="12"> 
                                <a href="<?php echo $this->url(array('controller'=>'Users','action'=>'user'),'default',true)?>" class="button">
            	                   <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                                </a>
                            </td>
						</tr>
                        <tr>
							<th style="width:10%; text-align:center">Name</th>
							<th style="width:8%; text-align:center">Department</th>
							<th style="width:8%; text-align:center">Designation</th>
							<th style="width:8%; text-align:center">Employee Code</th>
                        	<th style="width:10%; text-align:center">Email</th>
							<th style="width:7%; text-align:center">DOB</th>
							<th style="width:5%; text-align:center">Modify I.P</th>
							<th style="width:5%; text-align:center">Modify Date</th>
                        </tr>
                        <tr id="filterrow">
                           	<th style="width:10%; text-align:center">Name</th>
							<th style="width:8%; text-align:center">Department</th>
							<th style="width:8%; text-align:center">Designation</th>
							<th style="width:8%; text-align:center">Employee Code</th>
							<th style="width:10%; text-align:center">Email</th>
							<th style="width:7%; text-align:center">DOB</th>
							<th style="width:5%; text-align:center">Modify I.P</th>
							<th style="width:5%; text-align:center">Modify Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
				        if($this->history){
						
                            for($i=0;$i<count($this->history);$i++){
								  $class = ($i%2==0)?'even':'odd';
							?>
						<tr class="<?php echo $class;?>">
						    <td align="center">
                                <?php echo $this->history[$i]['first_name'].' '.$this->Users[$i]['last_name']?>
                            </td>

    						<td align="center">
                                <?php echo $this->history[$i]['department_name'];?>
                            </td>

    						<td align="center">
                                <?php echo $this->history[$i]['designation_name'];?>
                            </td>

    						<td align="center">
                                <?php echo $this->history[$i]['employee_code'];?>
                            </td>

    						<td align="center">
                                <?php echo $this->history[$i]['email'];?>
                            </td>

                            <td align="center">
                                <?php echo $this->history[$i]['dob'];?>
                            </td>
						    
                            <td align="center">
                                <?php echo $this->history[$i]['modify_ip'];?>
                            </td>

                            <td align="center">
                                <?php echo $this->history[$i]['modify_date'];?>
                            </td>
						</tr>
    						<?php 
                            }
                        }else{ ?>
    						
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
</div><!-- End .grid_12 -->
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