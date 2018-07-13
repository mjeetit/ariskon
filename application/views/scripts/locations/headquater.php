 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>HeadQuater</span></h2>
                    
                    <div class="module-table-body">
                    	
                        <table id="myTable" class="display">
                        	<thead>
    							<tr>
    							<td colspan="6">
        						   <a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'add','level'=>8),'default',true)?>" class="button add">
                                	<span>Add Headquater<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add City" /></span>
                                    </a>
    					        </td>
    						    </tr>
                                <tr>
    								<th >HeadQuater</th>
    								<th>Area Name</th>
    								<th>Region Name</th>
    								<th>Zone</th>
    								<th>Business Unit</th>
    								<th id="noClass1">Action</th>
                                </tr>
                                <tr id="filterrow">
                                    <th style="width:20%;; text-align:center">HeadQuater</th>
                                    <th style="width:20%;; text-align:center">Area Name</th>
                                    <th style="width:20%;; text-align:center">Region Name</th>
                                    <th style="width:20%;; text-align:center">Zone</th>
                                    <th style="width:20%;; text-align:center">Business Unit</th>
                                    <td style="width:10%;background-color:#eee" ></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->headquater){
								for($i=0;$i<count($this->headquater);$i++){								?>
								<tr>
								<td><?php echo $this->headquater[$i]['headquater_name']?></td>
								<td><?php echo $this->headquater[$i]['area_name']?></td>
								<td><?php echo $this->headquater[$i]['region_name']?></td>
								<td><?php echo $this->headquater[$i]['zone_name']?></td>
								<td><?php echo $this->headquater[$i]['bunit_name']?></td>
								<td><a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'edit','level'=>8,'headquater_id'=>$this->headquater[$i]['headquater_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
									</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td colspan="5">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        
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