 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Salary Head</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="5">
							<a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addnew','type'=>'Salaryhead'),'default',true)?>" class="button add"><span>Add Salary Head<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Salary Head" /></span>
                        </a>

				 <!--<a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addnew','type'=>'Detectsalaryhead'),'default',true)?>" class="button add"><span>Add Deduction Salary Head<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Deduction Salary Head" /></span>
                        </a>-->
					</td>
							</tr>
                                <tr>
									<th style="width:5%; text-align:center" id="noClass1">#</td>
									<th style="width:20%; text-align:center">Salary Title</th>
									<th style="width:20%; text-align:center">Type</th>
									<th style="width:20%; text-align:center">Sequence</th>
									<th style="width:20%; text-align:center" id="noClass2">Action</th>
                                </tr>
                                <tr id="filterrow">
                                <td style="width:5%; background-color:#eee"></td>
								<th style="width:20%; text-align:center">Salary Title</th>
								<th style="width:20%; text-align:center">Type</th>
								<th style="width:20%; text-align:center">Sequence</th>
                                <td style="width:20%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->salary){
								for($i=0;$i<count($this->salary);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
							<td align="center"><input type="checkbox" name="salaryhead_id" value="<?php echo $this->salary[$i]['salaryhead_id']?>" /></td>

						<td align="center"><?php echo $this->salary[$i]['salary_title']?></td>
						<td align="center"><?php echo ($this->salary[$i]['salary_type']=='1')?'Earning':'Deduction';?></td>
						<td align="center"><?php echo $this->salary[$i]['sequence']?></td>

						<td align="center">
						<?php if($this->salary[$i]['salaryhead_id']!=1 && $this->salary[$i]['salaryhead_id']!=2 && $this->salary[$i]['salaryhead_id']!=3){?>
						<a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'editnew','type'=>'Salaryhead','salaryhead_id'=>$this->salary[$i]['salaryhead_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
						<?php }?>
						</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="3">No Record Found!...</td>
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
 $(".noIcon").removeClass();
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