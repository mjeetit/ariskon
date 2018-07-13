<div class="grid_12">
	<!-- Example table -->
	<div class="module">
		<h2><span>Document List</span></h2>
		<div class="module-table-body">
			<form action="">
			<table id="myTable" class="tablesorter">
				<thead>
					<tr>
						<th style="width:70%; text-align:center">Document Name</th>
						<th style="width:5% text-align:center">Action</th>
					</tr>
				</thead>
				<tbody>
				 <?php 
				 if($this->Documents){
					for($i=0;$i<count($this->Documents);$i++){
					  $class = ($i%2==0)?'even':'odd';
					?>
					<tr class="<?php echo  $class;?>">
					<td align="center"><?php echo $this->Documents[$i]['document_name']?></td>
					<td align="center"><a href="<?php echo Bootstrap::$baseUrl.'public/DocumentDirectory/'.$this->Documents[$i]['file_name'] ?>" target="_blank"><img src="<?php print Bootstrap::$baseUrl;?>public/admin_images/download-icon.png" align="absmiddle" alt="<?php echo $this->Documents[$i]['type_name'];?>" title="<?php echo $this->Documents[$i]['type_name'];?>" border="0" class="changeStatus" /></a>
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
			<div class="pager" id="pager">
				<form action="">
					<div>
					<a href="" class="button"><span><img src="<?php echo IMAGE_LINK;?>/arrow-180-small.gif" tppabs="http://www.xooom.pl/work/magicadmin/images/arrow-180-small.gif" height="9" width="12" alt="Previous" /> Prev</span></a>
					<input type="text" class="pagedisplay input-short align-center"/>
					 <a href="" class="button"><span>Next <img src="<?php echo IMAGE_LINK;?>/arrow-000-small.gif" height="9" width="12" alt="Next" /></span></a> 
					<select class="pagesize input-short align-center">
						<option value="10" selected="selected">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="40">40</option>
					</select>
					</div>
				</form>
			</div>
			<div style="clear: both"></div>
		 </div> <!-- End .module-table-body -->
	</div> 
	<!-- End .module -->
</div> <!-- End .grid_12 -->