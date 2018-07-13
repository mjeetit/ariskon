<div class="grid_12">
                <div class="module">
                     <h2><span>Edit Approval</span></h2>
                     <div class="module-body">
                       <form name="data_setting" action="" method="post"> 
					   <table width="70%" style="border:none">
						<thead>
						  <tr>
							<td align="center" style="border:none">
								<table style=" width:70%">
									<thead>
									<tr>
										<td colspan="2" align="left" style="border:none">
									<a href="<?=$this->url(array('controller'=>'Leave','action'=>'approval'),'default',true)?>" class="button back">
											<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
											</a>
											</td>
										</tr>
										<tr>
										<th colspan="2">Edit</th>
										</tr>
										<tr class="odd">
										  <td>Designation Name <span class="strick">*</span> :</td>
										  <td><?=$this->info['designation_name']?></td>
										</tr>
										<tr class="even">
									  <td style="border:none">Number of Approval :</td>
									<td style="border:none">
									<input type="text" name="approval_no" id="approval_no" class="input-short" value="<?=$this->info['approval_no']?>" />
									</td>
								</tr>
					 <tr>
						<td colspan="2" align="center"><input type="submit" name="updateApproval" value="Update" class="submit-green" /></td>
						</tr>	
				</thead>
			</table>
		</td>
	</tr>
</thead>
</table>
			</form>
		 </div> <!-- End .module-body -->

	</div>  <!-- End .module -->
	<div style="clear:both;"></div>
</div>
					   
