 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Salary History</span></h2>
                    
                    <div class="module-table-body">
                     <form action="" method="get">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="11">
                                                            <input type="submit" name="ExportSummary" value="Download Pay summary" class="submit-green">
<!--							<a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'salaryhistory','Mode'=>'Paysummary'),'default',true)?>" class="button add">
                        	   <span>Download Pay Summary<img src="<?php echo IMAGE_LINK;?>/download.png"  width="12" height="9" alt="Download Pay Summary" /></span>
                           </a>-->&nbsp;&nbsp;&nbsp;&nbsp;
<!--						   <a href="<?php echo $this->url(array('controller'=>'Salary','action'=>'salaryhistory','Mode'=>'DetailPaySummary'),'default',true)?>" class="button add">
                        	   <span>Download Detail Pay<img src="<?php echo IMAGE_LINK;?>/download.png"  width="12" height="9" alt="Download Detail Pay" /></span>
                           </a>-->
                             <input type="submit" name="ExportDetail" value="Download Pay Detail" class="submit-green">
							</td>
							</tr>
							<tr><td colspan="11">
									
							<table>
							<?php if($_SESSION['AdminLoginID']==1){?>
							<tr>
							 <td>Select Employee &nbsp;<?php  echo $this->htmlSelect('user_id',CommonFunction::getAssociative($this->filteruserList,'user_id','name'),$this->filter['user_id'],array('class'=>'input-medium'));?></td>
							 <td>Select Department &nbsp;<?php  echo $this->htmlSelect('department_id',CommonFunction::getAssociative($this->filterdapartment,'department_id','department_name'),$this->filter['department_id'],array('class'=>'input-medium'));?></td>
							 <td>Select Designation &nbsp;<?php  echo $this->htmlSelect('designation_id',CommonFunction::getAssociative($this->filterdesignation,'designation_id','designation_name'),$this->filter['designation_id'],array('class'=>'input-medium'));?></td>
                                                         <td>Select Bank &nbsp;<?php  echo $this->htmlSelect('Bank',array(1=>'AXIS BANKK',2=>'ICICI BANK'),$this->filter['Bank'],array('class'=>'input-medium'));?></td>
							</tr>
							<?php }?>
							<tr>
							<td>From Date&nbsp;<input name="from_date" id="from_date" value="<?php echo $this->filter['from_date']?>"  class="input-short" /></td>
							 <td>To Date&nbsp;<input name="to_date" id="to_date" value="<?php echo $this->filter['to_date']?>"  class="input-short" /></td>
							 <td><input type="submit" name="search" class="submit-green" value="Search" /></td>
							 <td><input type="submit" name="Export" class="submit-green" value="Export" /></td>
							</tr>
							<!--<tr>
							<td colspan="4" align="left">
							<select name="print" onchange="javascript:if(this.value=='Print'){ this.form.submit();}">
							  <option value="">Select</option>
							  <option value="Print">Print</option>
							</select>
							</td>
							</tr>-->
						 </table>
						</td>
							</tr>
                                <tr>
									<th style="width:5%" id="noClass1"><input type="checkbox" name="checkall" style="width:10px" /></td>
									<th style="width:10%">Employee Name</th>
									<th style="width:10%">Designation</th>
									<th style="width:10%">Salary Month</th>
									<th style="width:10%">Release Date</th>
									<th style="width:10%">Paid Days</th>
									<th style="width:10%">Earning Total</th>
									<th style="width:10%">Detection Total</th>
									<th style="width:10%">Net Salary</th>
									<th style="width:10%" id="noClass2">Action</th>
                                </tr>
                                <tr id="filterrow">
                                    <td style="width:5%;background-color:#eee"></td>
                                    <th style="width:10%">Employee Name</th>
									<th style="width:10%">Designation</th>
									<th style="width:10%" id="month">Month</th>
									<th style="width:10%" id="date">Date</th>
									<th style="width:10%">Paid Days</th>
									<th style="width:10%">Earning Total</th>
									<th style="width:10%">Detection Total</th>
									<th style="width:10%">Net Salary</th>
									<td style="width:5%;background-color:#eee"></td>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 $salaryhistory = $this->salaryhistory['Records'];
							 if(!empty($salaryhistory)){
								for($i=0;$i<count($salaryhistory);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td width="10px" <?php echo $color;?>><input type="checkbox" name="user[]" style="width:10px" value="<?php echo $salaryhistory[$i]['user_id'].'@'.$salaryhistory[$i]['salary_date'].'@'.$salaryhistory[$i]['release_date'];?>" /></td>
								<td align="left"><?php echo $salaryhistory[$i]['name'];?></td>
								<td align="center"><?php echo $salaryhistory[$i]['designation_name'];?></td>
								<td align="center"><?php echo date('M-Y',strtotime($salaryhistory[$i]['salary_date']));?></td>
								<td align="center"><?php echo $salaryhistory[$i]['release_date'];?></td>
								<td align="center"><?php echo $salaryhistory[$i]['paid_days'];?></td>
								<td align="center"><?php echo $salaryhistory[$i]['earning_amount'];?></td>
								<td align="center"><?php echo $salaryhistory[$i]['deduction_amount'];?></td>
								<td align="center"><?php echo $salaryhistory[$i]['net_amount'];?></td>
								<td align="center"><a href="<?php echo Bootstrap::$baseUrl.'public/salaryslip/'.$salaryhistory[$i]['salary_slip_file'] ?>" target="_blank"><img src="<?php print Bootstrap::$baseUrl;?>public/admin_images/print.png" align="absmiddle" alt="Print Invoice" title="Print Invoice" border="0" class="changeStatus" /></a>

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
 
        </div>
        <div class="clear"></div>
    </div>
<script type="text/javascript">
$(function() {
		$("#from_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		$("#to_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		
	});
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
        $(this).html( '<input type="text" id="datepicker-1" style="width:80%"  placeholder="Search Date" />' );
    } );

     $('#myTable thead tr#filterrow th#month').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id="datepicker-2" style="width:80%" placeholder="Search Month" />' );
    } );
 
    // DataTable
    $( "#datepicker-1" ).datepicker({
        dateFormat:"yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true,
    });
    $( "#datepicker-2" ).datepicker({
        dateFormat:"M-yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
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