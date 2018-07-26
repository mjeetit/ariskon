 <div class="grid_12">
    <!-- Example table -->
    <div class="module">
    	<h2><span>Employee Alloted List</span></h2>       
            <div class="module-table-body">
            	<form action="">
                    <table id="myTable" class="tablesorter">
                    	<thead>
							<tr> 
							    <td colspan="9"> 
						            <a href="<?php echo $this->url(array('controller'=>'Employeeaccount','action'=>'allowetaccesseries'),'default',true)?>" class="button">
                        	           <span>New Allotment<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="New Allowet" /></span>
                                    </a>
					            </td>
							</tr>
                            <tr>
							    <th style="width:5%;text-align:center" id="noClass1">#</td>
                                <th style="width:10%;text-align:center">Name</th>
                                <th style="width:10%;text-align:center">Department</th>
                                <th style="width:10%;text-align:center">Designation</th>
                                <th style="width:10%;text-align:center">Item Value</th>
                                <th style="width:5%;text-align:center" id="noClass2">Action</th>
                            </tr>
                            <tr id="filterrow">
                                <td style="width:5%;background-color:#eee"></td>
                                <th style="width:10%;text-align:center">Name</th>
                                <th style="width:10%;text-align:center">Department</th>
                                <th style="width:10%;text-align:center">Designation</th>
                                <th style="width:10%;text-align:center">Item Value</th>
                                <td style="width:5%;background-color:#eee"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        	    if($this->AccUsers){

								for($i=0;$i<count($this->AccUsers);$i++){
								    $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
						            <td align="center">
                                        <input type="checkbox" name="user_id" value="<?php echo $this->AccUsers[$i]['user_id']?>" />
                                    </td>
						            <td align="center"><?php echo $this->AccUsers[$i]['first_name'].' '.$this->Users[$i]['last_name']?>
                                    </td>
						            <td align="center"><?php echo $this->AccUsers[$i]['department_name'];?>
                                    </td>
						            <td align="center"><?php echo $this->AccUsers[$i]['designation_name'];?>
                                        
                                    </td>
						            <td align="center"><?php echo $this->AccUsers[$i]['Amount'];?></td>
						            <td align="center">
						                <a href="<?php echo $this->url(array('controller'=>'Employeeaccount','action'=>'viewallowt','user_id'=>$this->AccUsers[$i]['user_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/view_detail.png" title="View Allowt" /></a>
					                </td>
								</tr>
							<?php }} else{ ?>
								<tr>
							     	<td align="center" colspan="9">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                    </form>
                    <div style="clear: both"></div>
                </div> <!-- End .module-table-body -->
            </div> 
		</div> <!-- End .grid_12 -->
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
function fancyboxopenfor(url){
    $.fancybox({
        "width": "100%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}

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