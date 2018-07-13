 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Leave Distribution Management</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
                                <tr>
									 <th style="width:15%;text-align:center">Designation</th>
									 <?php foreach($this->leaveInfo as $leavename) {?>
									 <th  style="width:10%;text-align:center"><?=$leavename['typeName'].'|Probation'?></th>
									 <?php } ?>
									<th  style="width:5%;text-align:center" id="noClass1">Action</th>
                                </tr>
                                <tr id="filterrow">
                                    <th style="width:15%;text-align:center">Designation</th>
                                    <?php foreach($this->leaveInfo as $leavename) {?>
                                     <th  style="width:10%;text-align:center"><?=$leavename['typeName']?></th>
                                     <?php } ?>
                                     <td style="width:5%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->details){
								foreach($this->details as $i=>$info) {
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								 <td align="center"><a href="<?=$this->url(array('controller'=>'Leave','action'=>'distributionedit','token'=>$info['ID']),'default',true)?>"><?=$info['Name']?></a></td>
                            <?php foreach($this->leaveInfo as $leave) { ?>
                            <td align="center"><?=(array_key_exists($leave['typeID'],$info['Leave'])) ? $info['Leave'][$leave['typeID']].' | '.$info['Leave']['Prob'][$leave['typeID']] : '--|--'?></td>
                            <?php } ?>
                            <td align="center"><a href="<?=$this->url(array('controller'=>'Leave','action'=>'distributionedit','token'=>$info['ID']),'default',true)?>"><img src="<?=Bootstrap::$baseUrl?>public/admin_images/pencil.gif" /></a></td>
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