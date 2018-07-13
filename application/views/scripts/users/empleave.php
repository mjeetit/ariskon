 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Emplopyee Leave List</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
                        <table id="myTable" class="tablesorter">
                        	<thead>
                                <tr>
									<th style="width:3%;text-align:center" id="noClass1">#</td>
									<th style="width:10%;text-align:center">Employee Code</th>
									<th style="width:8%;text-align:center">Department</th>
									<th style="width:8%;text-align:center">Designation</th>
									<?php $class=2;
                                    foreach($this->empleaves[1] as $types){?>
									  <th style="width:10%; text-align:center" id="noClass<?php echo $class;?>"><?php echo $types['typeName']?></th>
									<?php $class++;} ?>
                                </tr>
                                <tr id="filterrow">
                                    <td style="width:5%;background-color:#eee"></td>
                                    <th style="width:10%;text-align:center">Employee Code</th>
                                    <th style="width:8%;text-align:center">Department</th>
                                    <th style="width:8%;text-align:center">Designation</th>
                                    <?php foreach($this->empleaves[1] as $types){?>
                                      <td style="width:5%;background-color:#eee"></td>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->empleaves[0]){
								for($i=0;$i<count($this->empleaves[0]);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="user_id[]" value="<?php echo $this->empleaves[0][$i]['user_id']?>" /></td>
						<td align="center"><?php echo $this->empleaves[0][$i]['employee_code'];?></td>
						<td align="center"><?php echo $this->empleaves[0][$i]['department_name'];?></td>
						<td align="center"><?php echo $this->empleaves[0][$i]['designation_name'];?></td>
						<?php foreach($this->empleaves[1] as $types){ ?>
								<td align="center"><input type="text" name="leave[<?php echo $this->empleaves[0][$i]['user_id']?>][<?php echo $types['typeID']?>]" value="<?php echo $this->empleaves[0][$i]['Leave'][$types['typeID']]?>" class="input-short" /></td>
						<?php } ?>
						
								</tr>
							
								<?php } ?>
								<!--<tr><td colspan="7" align="right"><input type="submit" name="Update" value="Update" class="submit-green" /></td></tr>-->
								<?php } else{ ?>
								<tr>
								<td align="center" colspan="7">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        <div align="center"> <input type="submit" name="Update" value="Update" class="submit-green" /></div>
                        </form>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
                        <?php /*?></div>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
 
        </div>
        <div class="clear"></div>
    </div><?php */?>
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