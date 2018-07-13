 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Department To Business Unit</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="5">
							 <a href="<?php echo $this->url(array('controller'=>'Locationmap','action'=>'d2b'),'default',true)?>" class="button add">
                        	<span>Add Department To B.Unit<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="New article" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
                                    <th style="width:5%" id="noClass1">#</th>
									<th style="width:21%">Department Name</th>
                                    <th style="width:20%">Business Unit</th>
                                    <th style="width:13%" id="noClass2">Action</th>
                                </tr>
                                 <tr id="filterrow">
                                    <td style="width:5%;background-color:#eee"></td>
                                    <th style="width:21%;; text-align:center">Department Name</th>
                                    <th style="width:20%;; text-align:center">Business Unit</th>
                                    <td style="width:13%;background-color:#eee" ></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->departmenttobunit){
								for($i=0;$i<count($this->departmenttobunit);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="bunit_to_company_id" value="<?php echo $this->departmenttobunit[$i]['d2b_id']?>" /></td>
								<td align="center"><?php echo $this->departmenttobunit[$i]['department_name']?></td>
								<td align="center"><?php echo $this->departmenttobunit[$i]['bunit_name']?></td>
								<td align="center"><a href="#"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
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