 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Bissness Unit</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="3"><a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'add','level'=>2),'default',true)?>" class="button add">
                        	<span>Add Business Unit<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Compant" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:5%;text-align:center" id="noClass1">#</th>
									<th style="width:30%;; text-align:center">Business Unit</th>
									<th style="width:15%;; text-align:center" id="noClass2">Action</th>
                                </tr>
                                 <tr id="filterrow">
                                    <td style="width:5%;background-color:#eee"></td>
                                    <th style="width:30%;; text-align:center">Business Unit</th>
                                    <td style="width:15%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->bussnessunit){
								for($i=0;$i<count($this->bussnessunit);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="bunit_id" value="<?php echo $this->bussnessunit[$i]['bunit_id']?>" /></td>
								<td align="center"><?php echo $this->bussnessunit[$i]['bunit_name']?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>2,'bunit_id'=>$this->bussnessunit[$i]['bunit_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
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