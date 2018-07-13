 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>User List</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
							</tr>
                                <tr>
									<th style="width:3%; text-align:center" id="noClass1">#</td>
									<th style="width:10%; text-align:center">Name</th>
									<th style="width:10%; text-align:center">Department</th>
									<th style="width:10%; text-align:center">Designation</th>
									<th style="width:8%; text-align:center">Employee Code</th>
									<th style="width:10%; text-align:center">Email</th>
									<th style="width:7%; text-align:center" >DOB<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>
									<th style="width:5%; text-align:center" id="noClass2">Login Status</th>
									<th style="width:5%;text-align:center" id="noClass3">Admin Status</th>
									<th style="width:7%;text-align:center" id="noClass4">Action</th>
                                </tr>
                                <tr id="filterrow">
                                    <td style="width:5%;background-color:#eee"></td>
                                    <th style="width:10%; text-align:center">Name</th>
                                    <th style="width:10%; text-align:center">Department</th>
                                    <th style="width:10%; text-align:center">Designation</th>
                                    <th style="width:8%; text-align:center">Employee Code</th>
                                    <th style="width:10%; text-align:center">Email</th>
                                    <th style="width:7%; text-align:center" id="date">DOB</th>
                                    <td style="width:5%;background-color:#eee"></td>
                                    <td style="width:5%;background-color:#eee"></td>
                                    <td style="width:5%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->Users){
								for($i=0;$i<count($this->Users);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="user_id" value="<?php echo $this->Users[$i]['user_id']?>" /></td>
						<td align="center"><?php echo $this->Users[$i]['first_name'].' '.$this->Users[$i]['last_name']?></td>
						<td align="center"><?php echo $this->Users[$i]['department_name'];?></td>
						<td align="center"><?php echo $this->Users[$i]['designation_name'];?></td>
						<td align="center"><?php echo $this->Users[$i]['employee_code'];?></td>
						<td align="center"><?php echo $this->Users[$i]['email'];?></td>
						<td align="center"><?php echo $this->Users[$i]['dob'];?></td>
						<td align="center">
						<?php if($this->Users[$i]['login_status']=='1'){?>
						   <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/icon_active.gif" title="Active" />
						<?php }else{?>
						  <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/icon_inactive.gif" title="Inactive" />
						<?php }?>
						</td>
						<td align="center">
						<?php if($this->Users[$i]['user_status']=='1'){?>
						   <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/icon_active.gif" title="Active" />
						<?php }else{?>
						  <img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/icon_inactive.gif" title="Inactive" />
						<?php }?>
						</td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Users','action'=>'edituser','user_id'=>$this->Users[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit User" /></a>&nbsp;|
						<a onclick="fancyboxopenfor('<?php echo $this->url(array('controller'=>'Users','action'=>'view','user_id'=>$this->Users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a>&nbsp;|
					 <a  onclick="fancyboxopenforpriv('<?php echo $this->url(array('controller'=>'Users','action'=>'privillage','user_id'=>$this->Users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/privillage.png" title="Privillages" /></a>&nbsp;|
					 <a  onclick="fancyboxopenfordelete('<?php echo $this->url(array('controller'=>'Users','action'=>'delete','user_id'=>$this->Users[$i]['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" /></a>
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
function fancyboxopenfor(url){
 $.fancybox({
        "width": "70%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}

function fancyboxopenforpriv(url){
 $.fancybox({
        "width": "40%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
function fancyboxopenfordelete(url){
 $.fancybox({
        "width": "40%",
        "height": "40%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
</script>
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
 $('#myTable thead tr#filterrow th#date').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-1" style="width:60%"  placeholder="DOB" />' );
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
    $("#noClass2").removeClass();
    $("#noClass3").removeClass();
    $("#noClass4").removeClass();
 });
  $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
        $("#noClass2").removeClass();
        $("#noClass3").removeClass();
        $("#noClass4").removeClass();
    });
});
 </script>	