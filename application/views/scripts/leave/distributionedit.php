<div class="grid_12">
                <div class="module">
                     <h2><span>Update Approval Detail</span></h2>
                     <div class="module-body">
                     <form name="editApprovalForm" id="editApprovalForm" action="" method="post"> 
					   <table width="70%" style="border:none">
						<thead>
						  <tr>
							<td align="center" style="border:none">
								<table style=" width:70%">
									<thead>
									<tr>
										<td colspan="2" align="left" style="border:none">
									<a href="<?=$this->url(array('controller'=>'Leave','action'=>'distribution'),'default',true)?>" class="button back">
											<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
											</a>
											</td>
										</tr>
										<tr>
										<th colspan="2">Edit</th>
										</tr>
										<tr class="odd">
										  <td>Designation Name <span class="strick">*</span> :</td>
										  <td><?=$this->info['Name']?></td>
										</tr>
								<?php foreach($this->leaveInfo as $leave) { $no=(array_key_exists($leave['typeID'],$this->info['Leave'])) ? $this->info['Leave'][$leave['typeID']] : '';//print_r($this->info);die;
								?>
									 <tr class="even">
				
									  <td align="right"><?=$leave['typeName']?></td>
				
									  <td align="left"><input type="text" name="type_<?=$leave['typeID']?>" id="approval_no" class="input-short" value="<?=$no?>" />/Probation<input type="text" name="prob_type_<?=$leave['typeID']?>" id="prob_approval_no" class="input-short" value="<?=$this->info['Leave']['Prob'][$leave['typeID']]?>" /></td>
									   
									</tr>
				
									<?php } ?>
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
					   
