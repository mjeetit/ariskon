<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class="grid_12">
	<!-- Example table -->
	<div class="module">
		<h2><span>Sales List</span></h2>
		<div class="module-table-body">
			<form action="" method="get">
				<table id="myTable" class="tablesorter">
					<thead>
						<tr> 
							<td colspan="11"> 
								<a href="<?php echo $this->url(array('controller'=>'Secondary-Sale','action'=>'add'),'default',true)?>" class="button">
								<span>Add New <img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add New"/>
								</span>
								</a>
							</td>
						</tr>
						<!-- START : Search Form -->
						<tr>
						  <td colspan="11">
							<table width="96%" border="0" cellspacing="1" cellpadding="2">
							  <tr height="26">
								<th align="center" colspan="5">Search Form</th>
							  </tr>
							  <tr class="even">
								<!--Headquarter Pull Down List for Filter Option-->
								<td align="left" class="bold_text">
								  <?php if($_SESSION['AdminDesignation']<8) {?>Headquarter :
								  <select name="token2" class="input-medium">
									<option value="">-- Select Headquarter --</option>
									<?php foreach($this->headquarters as $headquarter){
										$select = '';
										if($this->Filterdata['token2']==Class_Encryption::encode($headquarter['headquater_id'])){
											$select = 'selected="selected"';
										}
									?>
									<option value="<?=Class_Encryption::encode($headquarter['headquater_id'])?>" <?=$select?>><?=$headquarter['headquater_name']?></option>
									<?php } ?>
								  </select>
								  <?php } ?>
								</td>
								<!--From Date for Filter Option-->
								<td width="25%" class="bold_text" align="left">
									From : <input class="input-medium" type="text" name="from_date" id="from_date" value="<?php echo $this->Filterdata['from_date']?>" />
								</td>
								<td width="25%" class="bold_text" align="left">
									To : <input class="input-medium" type="text" name="to_date" id="to_date" value="<?php echo $this->Filterdata['to_date']?>" />
								</td>
								<!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->

								<td align="left" class="bold_text">

								<?php if($_SESSION['AdminDesignation']<5) { ?>ZBM : 

								  <select name="token6" class="input-medium" onchange="getchild(this.value,'rbm_id','6')">

									<option value="">-- Select ZBM --</option>

									<?php foreach($this->zbmDetails as $zbmDetail){

										$select = '';

										if($this->Filterdata['token6']==Class_Encryption::encode($zbmDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($zbmDetail['user_id'])?>" <?=$select?>><?=$zbmDetail['employee_code'].' - '.$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>

									<?php } ?>

								  </select>

								  <?php } ?>

								</td>
							  </tr>
							  
							  <tr class="odd">
								<!--Regional Business Manager (RBM) Pull Down List for Filter Option-->

								<td align="left" class="bold_text">

								  <?php if($_SESSION['AdminDesignation']<6) { ?>RBM : 

								  <select id="rbm_id" class="input-medium" name="token5" onchange="getchild(this.value,'abm_id','7')">

									<option value="">-- Select RBM --</option>

									<?php foreach($this->rbmDetails as $rbmDetail){

										$select = '';

										if($this->Filterdata['token5']==Class_Encryption::encode($rbmDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($rbmDetail['user_id'])?>" <?=$select?>><?=$rbmDetail['employee_code'].' - '.$rbmDetail['first_name'].' '.$rbmDetail['last_name']?></option>

									<?php } ?>

								  </select>

								  <?php } ?>

								</td>
								<!--Area Business Manager (ABM) Pull Down List for Filter Option-->

								<td align="left" class="bold_text">

								  <?php if($_SESSION['AdminDesignation']<7) { ?>ABM : 

								  <select id="abm_id" class="input-medium" name="token4" onchange="getchild(this.value,'be_id','8')">

									<option value="">-- Select ABM --</option>

									<?php foreach($this->abmDetails as $abmDetail){

										$select = '';

										if($this->Filterdata['token4']==Class_Encryption::encode($abmDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($abmDetail['user_id'])?>" <?=$select?>><?=$abmDetail['employee_code'].' - '.$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>

									<?php } ?>

								  </select>

								  <?php } ?>

								</td>
								<!--Business Executive (BE) Pull Down List for Filter Option-->

								<td align="left" class="bold_text">

								  <?php if($_SESSION['AdminDesignation']<8) { ?>BE : 

								  <select class="input-medium" id="be_id" name="token3">

									<option value="">-- Select BE --</option>

									<?php foreach($this->beDetails as $beDetail){

										$select = '';

										if($this->Filterdata['token3']==Class_Encryption::encode($beDetail['user_id'])){

											$select = 'selected="selected"';

										}

									?>

									<option value="<?=Class_Encryption::encode($beDetail['user_id'])?>" <?=$select?>><?=$beDetail['employee_code'].' - '.$beDetail['first_name'].' '.$beDetail['last_name']?></option>

									<?php } ?>

								  </select>

								  <?php } ?>

								</td>
								<td  colspan="2"><input type="submit" name="filter" class="submit-green" value="Search" />&nbsp;&nbsp;<input type="submit" name="Export" class="submit-green" value="ExportSALES" /></td>			
							  </tr>
							</table>
						  </td>
						</tr>
                        <!-- END : Search Form -->
						<tr>
							<th style="width:10%;text-align:center" class="head">Name</th>
							<th style="width:8%;text-align:center" class="head">Department</th>
							<th style="width:8%;text-align:center" class="head">Designation</th>
							<th style="width:8%;text-align:center" class="head">Employee Code</th>
							<th style="width:8%;text-align:center" class="head">Headquanter</th>
							<th style="width:8%;text-align:center" class="head">Stockist</th>
							<th style="width:8%;text-align:center">From</th>
							<th style="width:8%;text-align:center">To</th>
							<th style="width:10%;text-align:center">Date</th>
							<th style="width:10%;text-align:center" class="head">Amount</th>
							<!--<th style="width:10%;text-align:center" class="head">Status</th>-->
							<?php	if ($_SESSION['AdminLevelID'] == 1) {
							?>
							<th style="width:10%;text-align:center" id="noClass3">Action</th>
							<?php } ?>
						</tr>
						<tr id="filterrow">
							<th style="width:10%;text-align:center">Name</th>
							<th style="width:8%;text-align:center">Department</th>
							<th style="width:8%;text-align:center">Designation</th>
							<th style="width:8%;text-align:center">Employee Code</th>
							<th style="width:8%;text-align:center">Headquanter</th>
							<th style="width:8%;text-align:center">Stockist</th>
							<th style="width:8%;text-align:center" id="date1">From</th>
							<th style="width:8%;text-align:center" id="date2">To</th>
							<th style="width:10%;text-align:center" id="date3">Date</th>
							<th style="width:10%;text-align:center">Amount</th>
							<!--<th style="width:10%;text-align:center">Status</th>-->
							<?php	if ($_SESSION['AdminLevelID'] == 1) {
							?>
							<td style="width:12%;background-color:#eee"></td>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php 
							$saleslist = $this->sales;
							if($saleslist)
							{
								for($i=0;$i<count($saleslist);$i++)
								{
								  $class = ($i%2==0)?'even':'odd';
						?>
									<tr class="<?php echo  $class;?>">
										<td align="center"><?php echo $saleslist[$i]['Emp_Name']?></td>
										<td align="center"><?php echo $saleslist[$i]['Department'];?></td>
										<td align="center"><?php echo $saleslist[$i]['Designation'];?></td>
										<td align="center"><?php echo $saleslist[$i]['Emp_Code'];?></td>
										<td align="center"><?php echo $saleslist[$i]['headquater_name'];?></td>
										<td align="center"><?php echo $saleslist[$i]['stockist_name'];?></td>
										<td align="center"><?php echo $saleslist[$i]['date_from'];?></td>
										<td align="center"><?php echo $saleslist[$i]['date_to'];?></td>
										<td align="center"><?php echo $saleslist[$i]['insert_date'];?></td>
										<td align="center" > <?php echo $saleslist[$i]['amount'];?></td>
										<!--<td align="center"><?php 
										if($saleslist[$i]['status']){
											echo "<span style='color:green' >Approved</span>";
										}else
										{	echo "<span>Pending</span>";}
										?></td>-->
										<?php	if ($_SESSION['AdminLevelID'] == 1) { 
										?>
										<td align="center">
											<!--<?php if(!$saleslist[$i]['status']){
											?>
											<a href="<?php echo $this->url(array('controller'=>'Secondary-Sale','action'=>'approve','id'=>Class_Encryption::encode($saleslist[$i]['id'])),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/tick-circle.gif" title="Approve" /></a>&nbsp;|
											<?php	} ?>-->
											<a><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" onclick="deleteSale('<?php echo Class_Encryption::encode($saleslist[$i]['id'])?>')" /></a>&nbsp;
										</td>
										<?php } ?>
									</tr>
						<?php 	}} else{ ?>
								<tr>
								<td align="center" colspan="6">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                </table>
            </form> 
            <div style="clear: both"></div>
        </div> <!-- End .module-table-body -->
    </div><!-- End .module -->
</div> <!-- End .grid_12 -->
<script type="text/javascript">
 $(function(){
 $('#from_date').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
	$('#to_date').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
 var table = $('#myTable').DataTable({
   pageLength: 30,
   "order": [[ 0, "desc" ]],
   orderCellsTop: true
  });
     // Setup - add a text input to each footer cell
    $('#myTable thead tr#filterrow th').each( function () {
        var title = $('#myTable thead .head').eq( $(this).index() ).text();
        $(this).html( '<input type="text" id=simple placeholder="Search '+title+'" />' );
    } );
	$('#myTable thead tr#filterrow th#date1').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html('<input type="text" id="datepicker-1" class="min" style="width:60%"  placeholder="From" />');
    } );
	$('#myTable thead tr#filterrow th#date2').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html('<input type="text" id="datepicker-2" class="max" style="width:60%"  placeholder="To" />');
    } );
	 $('#myTable thead tr#filterrow th#date3').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html('<input type="text" id="datepicker-3" style="width:60%"  placeholder="Date" />');
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
	$('#datepicker-2').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
	$('#datepicker-3').datepicker( {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        showOn: "button",
        buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
        buttonImageOnly: true
    });
	// Apply the filter
    $("#myTable thead input[id=datepicker-3]").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );
	$("#myTable thead input[id=simple]").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );
	 var table = $('#myTable').DataTable();
    // Add event listeners to the two range filtering inputs
	$('#datepicker-1').change( function() { table.draw(); } );
    $('#datepicker-2').change( function() { table.draw(); } );
});
$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var iFini = $('#datepicker-1').val();
        var iFfin = $('#datepicker-2').val();
        if (typeof(iFini)=="undefined"){
            iFini="";
        }
        if (typeof(iFfin)=="undefined"){
            iFfin="";
        }
        var iStartDateCol =5;
        var iEndDateCol =6;
        iFini=iFini.substring(0,4) + iFini.substring(5,7)+ iFini.substring(8,10);
        iFfin=iFfin.substring(0,4) + iFfin.substring(5,7)+ iFfin.substring(8,10);

        var datofini=aData[iStartDateCol].substring(0,4) + aData[iStartDateCol].substring(5,7)+ aData[iStartDateCol].substring(8,10);
        var datoffin=aData[iEndDateCol].substring(0,4) + aData[iEndDateCol].substring(5,7)+ aData[iEndDateCol].substring(8,10);

        if ( iFini === "" && iFfin === "" )
        {
            return true;
        }
        else if ( iFini <= datofini && iFfin === "")
        {
            return true;
        }
        else if ( iFfin >= datoffin && iFini === "")
        {
            return true;
        }
        else if (iFini <= datofini && iFfin >= datoffin)
        {
            return true;
        }
        return false;
    }
);
function deleteSale(id)
{
	var r=confirm("Do you really want to delete?");
	if (r==true)
	{
		window.location.href="http://www.reporting.jclifecare.com/Secondary-Sale/delete/id/"+id;
	}
}
 </script>