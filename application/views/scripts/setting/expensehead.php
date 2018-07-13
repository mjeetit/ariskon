 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Expense Head</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="6"> <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addexpense','Mode'=>'Add'),'default',true)?>" class="button add">
                        	<span>Add Expense Head<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="New article" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:5%" id="noClass1">#</th>
									<th style="width:20%">Expense Head Name</th>
									<th style="width:20%">Expense Type</th>
									<th style="width:20%">Including Head</th>
									<th style="width:20%">Repeatition In Month</th>
									<th style="width:20%" id="noClass2">Action</th>
                                </tr>
                                <tr id="filterrow">
                                <td style="width:5%; background-color:#eee"></td>
                                <th style="width:20%">Expense Head Name</th>
                                <th style="width:20%">Expense Type</th>
                                <th style="width:20%">Including Head</th>
                                <th style="width:20%">Repeatition In Month</th>
                                <td style="width:20%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->expensehead){
								for($i=0;$i<count($this->expensehead);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="head_id" value="<?php echo $this->expensehead[$i]['head_id']?>" /></td>
								<td align="center"><?php echo $this->expensehead[$i]['head_name']?></td>
								<td align="center"><?php if($this->expensehead[$i]['expense_type']==1){ echo 'Actual Expense';}elseif($this->expensehead[$i]['expense_type']==2){ echo 'Fixed Expense';}elseif($this->expensehead[$i]['expense_type']==3){ echo 'Mixed Expense';}?></td>
								<td align="center"><?php echo $this->expensehead[$i]['salary_title']?></td>
 								<td align="center"><?php echo ($this->expensehead[$i]['no_of_times']==2)?'One Time':'Multi Times'?></td>
						        <td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addexpense','Mode'=>'Edit','head_id'=>$this->expensehead[$i]['head_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
								</td>
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
 $("#noClass2").removeClass();
 });
 $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
        $("#noClass2").removeClass();
    });
});
 </script>