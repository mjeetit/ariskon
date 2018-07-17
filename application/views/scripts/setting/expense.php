<div class="grid_12">  
		<!-- Example table -->
		<div class="module">  
			<h2><span>Expense Settings</span></h2>
			<div class="module-table-body"> 
				<table id="myTable" class="tablesorter">
					<thead>
					<tr>
					<td colspan="5">
						<a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'addexpsetting','Mode'=>'Add'),'default',true)?>" class="button add">
                        	<span>Add Setting<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add Setting" /></span>
                        </a></td>
					</tr>
					<tr> 
					<td>
					 <?php  echo $this->pages; ?>
				  </td>
				 </tr>
			   </thead>
			</table>
			<div style="clear: both"></div>
		 </div>  <!-- End .module-table-body -->
	</div>  
	<!-- End .module -->
</div>  <!-- End .grid_12 --> 