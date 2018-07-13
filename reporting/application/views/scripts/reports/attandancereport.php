<div class="grid_12">
	<!-- Example table -->
	<div class="module">
		<h2><span>Attandance List</span></h2>
		<div class="module-table-body">
			<form action="" method="post" name="attandanceForm" id="attandanceForm">
			
			<table id="myTable" class="tablesorter">
				<thead>
				<?php if($_SESSION['AdminLoginID']!=1){?>
							<tr>
							<td colspan="2">
							
							<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'attandancereport','Mode'=>'All'))?>" class="button">
							<span>All Report<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="All Report" /></span></a>
							&nbsp;
							<a href="<?php echo $this->url(array('controller'=>'Reports','action'=>'attandancereport','Mode'=>'self'))?>" class="button">
							<span>Self Report<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="button" /></span></a></td>
							</tr>
				<?php } ?>
				</tr>
					<tr>
						<th style="width:15%; text-align:center">Employee Code</th>
						<th style="width:15% text-align:center">Department</th>
						<th style="width:15% text-align:center">Designation</th>
						<th style="width:7% text-align:center">Month</th>
						<th style="width:13% text-align:center">Present Day</th>
						<th style="width:13% text-align:center">Absent Day</th>
						<th style="width:13% text-align:center">Leave Day</th>
						<th style="width:13% text-align:center">Half Day</th>
						<th style="width:13% text-align:center">Leave Without Pay</th>
					</tr>
				</thead>
				<tbody>
				 <?php 
				 if($this->attandnceRep){
					for($i=0;$i<count($this->attandnceRep);$i++){
					  $class = ($i%2==0)?'even':'odd';
					  $totalcount = @array_count_values($this->attandnceRep[$i]);
					?>
					<tr class="<?php echo  $class;?>">
					<td align="center"><?php echo $this->attandnceRep[$i]['employee_code']?></td>
					<td align="center"><?php echo $this->attandnceRep[$i]['department_name']?></td>
					<td align="center"><?php echo $this->attandnceRep[$i]['designation_name']?></td>
					<td align="center"><?php echo date('F Y',strtotime($this->attandnceRep[$i]['year'].'-'.$this->attandnceRep[$i]['month'].'-1'));?></td>
					<td align="center"><?php echo $totalcount['P']?></td>
					<td align="center"><?php echo $totalcount['A']?></td>
					<td align="center"><?php echo $totalcount['L']?></td>
					<td align="center"><?php echo $totalcount['HL']?></td>
					<td align="center"><?php echo $totalcount['H']?></td>
					
					</tr>
					<?php }} else{ ?>
					<tr>
					<td align="center" colspan="9">No Record Found!...</td>
					</tr>
					<?php }?>
				</tbody>
			</table>
			</form>
			<div class="pager" id="pager">
			<?php echo CommonFunction::PageCounter(100, 1, 50, "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext');?>
			</div>
			<div style="clear: both"></div>
		 </div> <!-- End .module-table-body -->
	</div> 
	<!-- End .module -->
</div> <!-- End .grid_12 -->
