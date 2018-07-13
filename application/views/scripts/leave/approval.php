 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Leave Approval Management</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
                                <tr>
									 <th style="width:15%;text-align:center">Designation</th>
									 <th  style="width:20%;text-align:center">Number of Approvals</th>
									<th  style="width:10%;text-align:center" id="noClass1">Action</th>
                                </tr>
                                <tr id="filterrow">
                                    <th style="width:15%;text-align:center">Designation</th>
                                    <th  style="width:20%;text-align:center">Number of Approvals</th>
                                    <td style="width:5%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->leaveapprovals){
								foreach($this->leaveapprovals as $i=>$info) {
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
									<td align="center"><?=$info['designation_name']?></td>
									<td align="center"><?=($info['approval_no']>0) ? $info['approval_no'] : '--'?></td>
									<td align="center"><a href="<?=$this->url(array('controller'=>'Leave','action'=>'approvaledit','token'=>$info['designation_id']),'default',true)?>"><img src="<?=Bootstrap::$baseUrl?>public/admin_images/pencil.gif" /></a></td>		
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