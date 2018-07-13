 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Leave Type Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="7"> <a href="<?=$this->url(array('controller'=>'Leave','action'=>'typeadd'),'default',true)?>" class="button add">
                        	<span>Add New Type<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add New Type" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:5%;text-align:center" id="noClass1">#</th>
									<th style="width:15%;text-align:center">Add New Type</th>
									<th style="width:15%;text-align:center">Leave Description</th>
									<th style="width:15%;text-align:center">Credit Period</th>
									<th style="width:15%;text-align:center">Carry Forward to Next Year</th>
									<th style="width:15%;text-align:center" id="noClass2">Status</th>
									<th style="width:5%;text-align:center" id="noClass3">Action</th>
                                </tr>
                                <tr id="filterrow">
				                    <td style="width:5%;background-color:#eee"></td>
				                    <th style="width:15%;text-align:center">Add New Type</th>
									<th style="width:15%;text-align:center">Leave Description</th>
									<th style="width:15%;text-align:center">Credit Period</th>
									<th style="width:15%;text-align:center">Carry Forward to Next Year</th>
									<td style="width:15%;background-color:#eee"></td>
									<td style="width:5%;background-color:#eee"></td>
                    			</tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->types){
								for($i=0;$i<count($this->types);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="leaveType" id="leaveType" value="<?=$this->types[$i]['typeID']?>" /></td>
								<td align="center"><a href="<?=$this->url(array('controller'=>'Leave','action'=>'typeedit','token'=>$this->types[$i]['typeID']),'default',true)?>"><?=$this->types[$i]['typeName']?></a></td>
								<td align="center"><?=$this->types[$i]['typeDesc']?></td>
								<td align="center"><?=$this->types[$i]['creditPeriod']?></td>
								<td align="center"><?=($this->types[$i]['carryForward']=='1') ? 'YES' : 'NO'?></td>
								<td align="center"><?=($this->types[$i]['status']=='1') ? 'Active' : 'In-active'?></td>
								<td align="center"><a href="<?=$this->url(array('controller'=>'Leave','action'=>'typeedit','token'=>$this->types[$i]['typeID']),'default',true)?>"><img src="<?=Bootstrap::$baseUrl?>public/admin_images/pencil.gif" /></a></td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="4">No Record Found!...</td>
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
    $("#noClass3").removeClass();
 });
  $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
        $("#noClass2").removeClass();
        $("#noClass3").removeClass();
    });
});
 </script>