 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Message</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="4"> 
							<a href="<?php echo $this->url(array('controller'=>'Notifications','action'=>'addmessages'),'default',true)?>" class="button">
                        	<span>Add Message<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Message" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
									<th style="width:3%;text-align:center" >S.No.</th>
									<th style="width:20%;text-align:center">Message</th>
									<th style="width:20%;text-align:center">Create Date</th>
									<th style="width:20%;text-align:center" id="noClass1">Status</th>
                                </tr>
                                <tr id="filterrow">
				                    <td style="width:3%;background-color:#eee"></td>
									<th style="width:20%;text-align:center">Message</th>
									<th style="width:20%;text-align:center" id="date">Create Date</th>
									<td style="width:20%;background-color:#eee"></td>
                    			</tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->Messagelist){
								for($i=0;$i<count($this->Messagelist);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center" ><?php echo ($i+1);?></td>
								<td align="center"><?php echo substr($this->Messagelist[$i]['description'],0,100).'....&nbsp;&nbsp;&nbsp;<a href="#" onclick="showmessage();">View</a>';?></td>
								<td align="center"><?php echo $this->Messagelist[$i]['notification_date'];?></td>
								<td align="center" id="statusportion<?php echo $this->Messagelist[$i]['notification_id'];?>">
								<?php if($this->Messagelist[$i]['status']==1) {?>
								<img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Active" border="0" 
				onclick="javascript: changeStatus('<?php echo 'notification';?>','<?php print $this->Messagelist[$i]['notification_id']; ?>','status','0','notification_id');" title="Active" 
		
				 class="changeStatus" />
						<?php }else { ?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
				onclick="javascript: changeStatus('<?php echo 'notification';?>','<?php print $this->Messagelist[$i]['notification_id']; ?>','status','1','notification_id');" title="Inactive" 
		
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
   "order": [[ 0, "asc" ]],
   orderCellsTop: true
  });
     // Setup - add a text input to each footer cell
    $('#myTable thead tr#filterrow th').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 $('#myTable thead tr#filterrow th#date').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-1" style="width:60%"  placeholder="Date" />' );
    } );
    // DataTable
    $('#datepicker-1').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });

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