 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>City</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="8">
						   <a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'add','level'=>9),'default',true)?>" class="button add">
                        	<span>Add City<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add City" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:5%;; text-align:center" id="noClass1">#</th>
									<th style="width:15%;; text-align:center">City</th>
									<th style="width:15%;; text-align:center">HeadQuater</th>
									<th style="width:15%;; text-align:center">Area Name</th>
									<th style="width:20%;; text-align:center">Region Name</th>
									<th style="width:15%;; text-align:center">Zone</th>
									<th style="width:15%;; text-align:center">Business Unit</th>
									<th style="width:5%;; text-align:center" id="noClass2">Action</th>
                                </tr>
								<tr id="filterrow">
									<td style="background-color:#eee"></td>
									<th style="width:15%;; text-align:center">City</th>
									<th style="width:15%;; text-align:center">HeadQuater</th>
									<th style="width:15%;; text-align:center">Area Name</th>
									<th style="width:20%;; text-align:center">Region Name</th>
									<th style="width:15%;; text-align:center">Zone</th>
									<th style="width:15%;; text-align:center">Business Unit</th>
									<td style="background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->city){
								for($i=0;$i<count($this->city);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td><input type="checkbox" name="city_id" value="<?php echo $this->city[$i]['city_id']?>" /></td>
								<td align="center"><?php echo $this->city[$i]['city_name']?></td>
								<td align="center"><?php echo $this->city[$i]['headquater_name']?></td>
								<td align="center"><?php echo $this->city[$i]['area_name']?></td>
								<td align="center"><?php echo $this->city[$i]['region_name']?></td>
								<td align="center"><?php echo $this->city[$i]['zone_name']?></td>
								<td align="center"><?php echo $this->city[$i]['bunit_name']?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'edit','level'=>9,'city_id'=>$this->city[$i]['city_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
									</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="5">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        </form>
                       <!-- <div class="pager" id="pager">
                            <form action="">
                                <div>
                                <a href="" class="button"><span><img src="<?php echo IMAGE_LINK;?>/arrow-180-small.gif" tppabs="http://www.xooom.pl/work/magicadmin/images/arrow-180-small.gif" height="9" width="12" alt="Previous" /> Prev</span></a>
                                <input type="text" class="pagedisplay input-short align-center"/>
								 <a href="" class="button"><span>Next <img src="<?php echo IMAGE_LINK;?>/arrow-000-small.gif" height="9" width="12" alt="Next" /></span></a> 
                                <select class="pagesize input-short align-center">
                                    <option value="10" selected="selected">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                </select>
                                </div>
                            </form>
                        </div> -->
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