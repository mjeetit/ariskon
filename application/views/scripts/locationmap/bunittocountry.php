<div class="grid_12">
                <div class="module">
                     <h2><span>New <?php echo ucfirst($this->back);?></span></h2>
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
											<a href="<?php echo $this->url(array('controller'=>'Locationmap','action'=>$this->backNew),'default',true)?>" class="button back">
											<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
											</a>
											</td>
										</tr>
										<tr>
										<th colspan="2">Add <?php echo ucfirst($this->back);?></th>
										</tr>
											<tr class="even">
												  <td style="border:none">Business Unit</td>
														<td style="border:none">
															<select name="bunit_id">
															 <option value="">--Select--</option>
															 <?php $bunits =  $this->ObjModel->getBissnessUnit();
															foreach($bunits as  $bunit){?>
															<option value="<?php echo  $bunit['bunit_id']; ?>"><?php echo  $bunit['bunit_name']; ?></option>
															<?php } ?>
															</select>
														</td>
											</tr>
							 <tr class="odd">
								  <td style="border:none" valign="top">Country</td>
								  <td style="border:none">
									<select name="country_id">
									 <option value="">--Select--</option>
									 <?php $countries =  $this->ObjModel->gerCountryList();
									foreach($countries as  $countrie){?>
									<option value="<?php echo  $countrie['country_id']; ?>"><?php echo  $countrie['country_name']; ?></option>
									<?php } ?>
									</select>
						  </td>
					</tr>
					 <tr>
						<td colspan="2" align="center"><input type="submit" name="bunittocountry" value="Add"  class="submit-green" /></td>
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
					   
