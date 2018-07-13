	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">   
                	<h2><span>App Users</span></h2>
                    
                    <div class="module-table-body"> 
					
                    	<table  id="myTable" class="display">
				<thead>		
						<tr>
						<th>Emp. Name</th>
						<th>Emp. Code</th>
						<th>Designation</th>
						<th>Headquater</th>
						<th>IEMI Number</th>
						<th>Install Date</th>
						<th>Last Sync</th>
						<th>IEMI Check</th>
						<th>App Lock</th>
						<th>Action</th>
						
						</tr>
						<tr id="filterrow">
						<th>Emp. Name</th>
						<th>Emp. Code</th>
						<th>Designation</th>
						<th>Headquater</th>
						<th>IEMI Number</th>
						<td style="background-color:#eee"></td>
						<td style="background-color:#eee"></td>
						<td style="background-color:#eee"></td>
						<td style="background-color:#eee"></td>
						<td style="background-color:#eee"></td>
						
						</tr>
			</thead>	
					 <tbody>		
						<?php if(!empty($this->appusers)){ 
						foreach($this->appusers as $i=>$appuser){
						// $class = ($i%2==0)?'even':'odd'; ?>
						 <tr>
						  <td><?php echo $appuser['first_name'].' '.$appuser['last_name']?></td>
						 <td><?php echo $appuser['employee_code']?></td>
						  <td><?php echo $appuser['designation_code']?></td>
						  <td><?php echo $appuser['headquater_name']?></td>
						  <td><?php echo $appuser['emei_number']?></td>
						 <td><?php echo $appuser['install_date']?></td>
						 <td><?php echo $appuser['last_sync_date']?></td>
						 <td align="center"  id="statusportion<?php echo $appuser['user_id'];?>"><?php if($appuser['emei_check']=='1'){?>
						   <img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Off" border="0" 
				onclick="changeStatus('<?php echo 'app_userdetails';?>','<?php print $appuser['user_id']; ?>','emei_check','0','user_id');" title="ON" class="changeStatus" />
						<?php }else{?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="On" border="0" 
				onclick="changeStatus('<?php echo 'app_userdetails';?>','<?php print $appuser['user_id']; ?>','emei_check','1','user_id');" title="OFF"  class="changeStatus" />
						<?php }?></td>
						 <td align="center"  id="lockportion<?php echo $appuser['user_id'];?>"><?php if($appuser['reporing_lock']=='0'){?>
						   <img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Off" border="0" 
				onclick="changeStatusByportion('<?php echo 'app_userdetails';?>','<?php print $appuser['user_id']; ?>','reporing_lock','1','user_id','lockportion');" title="Unlock" class="changeStatus" />
						<?php }else{?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="On" border="0" 
				onclick="changeStatusByportion('<?php echo 'app_userdetails';?>','<?php print $appuser['user_id']; ?>','reporing_lock','0','user_id','lockportion');" title="Lock"  class="changeStatus" />
						<?php }?></td>
						<td><a href="<?php echo $this->url(array('controller'=>'Appsettings','action'=>'setting','user_id'=>$appuser['user_id']),'default',true)?>"><img title="Edit Settings" src="http://www.reporting.jclifecare.com/public/admin_images/pencil.gif"></a></td>
						 </tr>
						 <?php } }?>
                            </tbody>
						</td></tr>	
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
 });

</script>						
