 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Message</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="5"> 
							<a href="<?php echo $this->url(array('controller'=>'Notifications','action'=>'addevent'),'default',true)?>" class="button">
                        	<span>Add Event<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Event" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:3%;; text-align:center">S.No.</th>
									<th style="width:15%;; text-align:center"> Event Name</th>
									<th style="width:25%;; text-align:center">Event Description</th>
									<th style="width:10%;; text-align:center">Event Date</th>
									<th style="width:5%;; text-align:center" id="noClass1">Status</th>
                                </tr>
                                <tr id="filterrow">
				                    <td style="width:3%;background-color:#eee"></td>
									<th style="width:15%;; text-align:center">Event Name</th>
									<th style="width:25%;; text-align:center">Event Description</th>
									<th style="width:10%;; text-align:center">Event Date</th>
									<td style="width:5%;background-color:#eee"></td>
                                </tr>
                    			</tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->EventList){
								for($i=0;$i<count($this->EventList);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><?php echo ($i+1);?></td>
								<td align="center"><?php echo $this->EventList[$i]['event_name'];?></td>
								<td align="center"><?php echo substr($this->EventList[$i]['event_description'],0,100).'....&nbsp;&nbsp;&nbsp;<a href="#">View</a>';?></td>
								<td align="center"><?php echo $this->EventList[$i]['event_date'];?></td>
								<td align="center" id="statusportion<?php echo $this->EventList[$i]['event_id'];?>">
								<?php if($this->EventList[$i]['status']==1) {?>
								<img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Active" border="0" 
		
				onclick="javascript: changeStatus('<?php echo 'events';?>','<?php print $this->EventList[$i]['event_id']; ?>','status','0','event_id');" title="Active" 
		
				 class="changeStatus" />
						<?php }else { ?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
		
				onclick="javascript: changeStatus('<?php echo 'events';?>','<?php print $this->EventList[$i]['event_id']; ?>','status','1','event_id');" title="Inactive" 
		
				 class="changeStatus" />
				   <?php } ?></td>
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
 });
  $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
    });
});
 </script>