 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Salary List</span></h2>
                    
                    <div class="module-table-body">
                    <form method="post" action="" name="salary" id="salary"> 
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="4">
							 <a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'addarear'),'default',true)?>" class="button add">
				<span>Add Arear<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="New article" /></span>
			</a>
							</td>
							<td colspan="2">
							 <a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'manualsalary'),'default',true)?>" class="button add">
				<span>Manual Salary<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="New article" /></span>
			</a>
							</td>
							<td colspan="2">
							 <select name="generate_slip" onchange="if(this.value==1){ this.form.submit();}">
							 <option "">--Select with Action--</option>
							 <option value="1">--Generate Salary--</option>
							 </select>
							</td>
							</tr>
                                <tr>
									<th style="width:5%" id="noClass1"><input type="checkbox" name="checkall" style="width:10px" /></td>
									<th style="width:10%">Employee Name</th>
									<th style="width:10%">Employee Code</th>
									<th style="width:10%">Month Of Salary</th>
<!--									<th style="width:10%">Paid Days</th>
									<th style="width:10%">Leave Days</th>
-->									<th style="width:10%">Earning Amount</th>
									<th style="width:10%">Detection Amount</th>
									<th style="width:10%">Net Salary</th>
									<th style="width:10%" id="noClass2">Action</th>
                                </tr>
                                <tr id="filterrow">
                                	<td style="width:5%;background-color:#eee"></td>
                                	<th style="width:10%">Employee Name</th>
									<th style="width:10%">Employee Code</th>
									<th style="width:10%" id="month">Month Of Salary</th>
<!--									<th style="width:10%">Paid Days</th>
									<th style="width:10%">Leave Days</th>
-->									<th style="width:10%">Earning Amount</th>
									<th style="width:10%">Detection Amount</th>
									<th style="width:10%">Net Salary</th>
                                	<td style="width:5%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->salarylist){
								for($i=0;$i<count($this->salarylist);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td width="10px" <?php echo $color;?>><input type="checkbox" name="user_id[<?php echo $this->salarylist[$i]['user_id'];?>]" style="width:10px" value="<?php echo $this->salarylist[$i]['salary_date'];?>" /></td>
								<td align="left"><?php echo $this->salarylist[$i]['name'];?></td>
								<td align="left"><?php echo $this->salarylist[$i]['employee_code'];?></td>
								<td align="center"><?php echo $this->salarylist[$i]['date'];?></td>
								<!--<td align="center"><?php echo '30';?></td>
								<td align="center"><?php echo '0';?></td>-->
								<td align="center"><?php echo $this->salarylist[$i]['earnings'];?></td>
								<td align="center"><?php echo $this->salarylist[$i]['dedection'];?></td>
								<td align="center"><?php echo round(($this->salarylist[$i]['earnings']-$this->salarylist[$i]['dedection']));?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'editsalary','user_id'=>$this->salarylist[$i]['user_id'],'salary_date'=>$this->salarylist[$i]['salary_date']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
								| <a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'salaryprocessing','Mode'=>'Single','user_id'=>$this->salarylist[$i]['user_id'],'salary_date'=>$this->salarylist[$i]['salary_date'],'Type'=>'Final'),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="Generate Salary Slip" /></a>&nbsp;|
								 <a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'salaryprocessing','Mode'=>'Single','user_id'=>$this->salarylist[$i]['user_id'],'salary_date'=>$this->salarylist[$i]['salary_date'],'Type'=>'Test'),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/test_salary.png" title="Test Salary Slip" /></a>
								 &nbsp;|
								 <a onclick="fancyboxopenforchequeno('<?php echo $this->url(array('controller'=>'Salary','action'=>'chequenumber','user_id'=>$this->salarylist[$i]['user_id'],'salary_date'=>$this->salarylist[$i]['salary_date']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/cheque-icon.png" title="Cheque Number" /></a>
						</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="11">No Record Found!...</td>
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
function fancyboxopenforchequeno(url){
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
 	$('#myTable thead tr#filterrow th#month').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-1" style="width:80%" placeholder="Search Month" />' );
    } );
    // DataTable

     $('#datepicker-1').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'MM yy',
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
 });
  $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
        $("#noClass2").removeClass();
    });
});
 </script>