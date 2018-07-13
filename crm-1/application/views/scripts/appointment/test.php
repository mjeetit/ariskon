<table style="width:100%; margin-top:20px;" border="2" cellpadding="2" cellspacing="2">
				  <thead>
                  <form name="listFrom" id="listForm" action="" method="get">
                  </form>
					<tr>
					  <td align="left" width="100%">
					  <table style=" width:100%">
                        <tr class="even">
                          <td align="left" class="bold_text"><?=strtoupper("CRM No")?><span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['appointData']['appointment_code'])?></td>
                          <td align="left" class="bold_text"><?=strtoupper("Doctor Name")?><span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['appointData']['doctorName'])?></td>
                          <td align="left" class="bold_text">Address <span class="strick">*</span> :</td>
                          <td align="left"><?=$this->appoints['appointData']['Patch'].", ".$this->appoints['appointData']['City']?></td>
                        </tr>
                       	<tr class="even">
                          <td class="bold_text">Headquarter</td>
                       	  <td><?=$this->appoints['appointData']['hqName']?></td>
                       	</tr>
                        <tr class="odd">
                          <td align="left" class="bold_text"><?=strtoupper("Qualification")?> <span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['doctorInfo']['qualification'])?></td>
                          <td align="left" rowspan="3" valign="top" colspan="4">
						  <?=strtoupper($this->appoints['doctorInfo']['address1']).'<br>'.strtoupper($this->appoints['doctorInfo']['address2']).'<br>'.$this->appoints['doctorInfo']['postcode']?>
						  </td>
                        </tr>
                        
                        <tr class="even">
                          <td align="left" class="bold_text"><?=strtoupper("Speciality")?><span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['doctorInfo']['speciality'])?></td>
                        </tr>
                        
                        <tr class="odd">
                          <td align="left" class="bold_text"><?=strtoupper("SVL No /Code No")?><span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['doctorInfo']['svl_number'])?></td>
                        </tr>
                        
                        <tr class="even">
                          <td align="left" class="bold_text"><?=strtoupper("Tel No (with STD)")?> <span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['doctorInfo']['phone'])?></td>
                          <td align="left" class="bold_text"><?=strtoupper("Mobile No")?><span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['doctorInfo']['mobile'])?></td>
                          <td align="left" colspan="2"></td>
                        </tr>
                        
                        <tr class="odd">
                          <td width="15%" align="left" class="bold_text">BE <span class="strick">*</span> :</td>
                          <td width="15%" align="left"><?=strtoupper($this->appoints['appointData']['beName'])?></td>
                          <td width="15%" align="left" class="bold_text">HQ <span class="strick">*</span> :</td>
                          <td width="15%" align="left"><?=strtoupper($this->appoints['appointData']['beHQName']); ?></td>
                          <td width="15%" align="left" class="bold_text"><?=strtoupper("Employee code")?><span class="strick">*</span> :</td>
                          <td width="25%" align="left"><?=strtoupper($this->appoints['appointData']['beCode']); ?></td>
                        </tr>
                        
                        <tr class="even">
                          <td align="left" class="bold_text">ABM <span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['appointData']['abmName'])?></td>
                          <td align="left" class="bold_text">HQ <span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['appointData']['abmHQName'])?></td>
                          <td align="left" class="bold_text"><?=strtoupper("Employee code")?> <span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['appointData']['abmCode'])?></td>
                        </tr>
                        
                        <tr class="odd">
                          <td align="left" class="bold_text">RBM <span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['appointData']['rsmName'])?></td>
                          <td align="left" class="bold_text">HQ <span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['appointData']['rsmHQName'])?></td>
                          <td align="left" class="bold_text"><?=strtoupper("Employee code")?><span class="strick">*</span> :</td>
                          <td align="left"><?=strtoupper($this->appoints['appointData']['rsmCode'])?></td>
                        </tr>
                        
                        <tr class="even">
                          <td align="left" class="bold_text"><?=strtoupper("Nature of Request")?> <span class="strick">*</span> :</td>
                          <td align="left" valign="top"><?=strtoupper($this->appoints['appointData']['expenseName'])?></td>
                          <td align="left" class="bold_text"><?=strtoupper("COST of Activity")?><span class="strick">*</span> :</td>
                          <td align="left"><?=$this->appoints['appointData']['expense_cost']?></td>
                        </tr>
                        
                        <tr class="odd">
                          <td align="left" class="bold_text"><?=strtoupper("Detail of Activity Planned")?><span class="strick">*</span> :</td>
                          <td align="left" valign="top" colspan="5"><?=strtoupper($this->appoints['appointData']['expense_note'])?></td>
                        </tr>
                      </table>
					  </td>
                    </tr>
					
					<!-- BE : Potential of Dr for JCL Products -->
					<tr>
                      <td width="100%" valign="top" align="left">
					   <div id="productPotentialDiv1" style="width:1165px; overflow-x:auto;overflow-y:none;!important">
                        <h2><span><?=strtoupper("Potential of Dr for JCL Products")?></span></h2>
						<table cellpadding="2" cellspacing="2" border="0" style="width:<?=$this->tableWidth?>px">
                          <tr class="odd">
						  	<td align="center" valign="middle" rowspan="3" width="10%" class="bold_text"><?=strtoupper("Products")?></td>
							<td colspan="6" align="center" class="bold_text"><?=strtoupper("Current 3 Months")?></td>
							<td colspan="12" align="center" class="bold_text"><?=strtoupper("Expected 6 Months")?></td>
						  </tr>
						  
						  <tr class="even">
						    <?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center" colspan="2"><b><?=date("M Y",strtotime($potential['month']))?></b></td>
							<?php } ?>
						  </tr>
						  
						  <tr class="odd">
						  	<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center" width="5%"><?=strtoupper("Unit")?></td>
							<td align="center" width="5%"><?=strtoupper("Value")?></td>
							<?php } ?>
						  </tr>
						  
						  <?php //echo "<pre>";print_r($this->appoints['crmproducts']);die;
							foreach($this->products as $i=>$product)
							{
								$trClass = ($i%2==0) ? 'odd' : 'even';
								if(in_array($product['product_id'],$this->appoints['crmproducts'])) {
						  ?>

						  <tr class="<?=$trClass?>">
                          	<td align="left" valign="top" class="bold_text"><?=$product['product_name']?></td>
							<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center">
								<?php if (@array_key_exists($product['product_id'], $this->appoints['potentialData'][$potential['month']])) { echo number_format($this->appoints['potentialData'][$potential['month']][$product['product_id']]['unit'],0); } else { echo '--'; }?>
							</td>
							<td align="center">
								<?php if (@array_key_exists($product['product_id'], $this->appoints['potentialData'][$potential['month']])) { echo $this->appoints['potentialData'][$potential['month']][$product['product_id']]['value']; } else { echo '--'; }?></td>
							<?php } ?>
                          </tr>
						  <?php } } ?>
						  
						  <tr class="odd">
						  	<td align="right" class="bold_text">Total</td>
							<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="right" colspan="2"><?=$potential['month_total_value']?></td>
							<?php } ?>
						  </tr>						
                        </table>
					   </div>
                      </td>
                    </tr>
					
					<?php 
						$isEditCRM = 0;
						if($_SESSION['AdminDesignation']==7 && $this->appoints['appointData']['abm_id']==$_SESSION['AdminLoginID']) {
							$isEditCRM = 1;
						}
						else if($_SESSION['AdminDesignation']==6 && $this->appoints['appointData']['abm_id']==0 && $this->appoints['appointData']['rbm_id']==$_SESSION['AdminLoginID']) {
							$isEditCRM = 1;
						}
						else if($_SESSION['AdminDesignation'] == 5 && $this->appoints['appointData']['abm_id']==0 && $this->appoints['appointData']['rbm_id']==0 && $this->appoints['appointData']['zbm_id'] == $_SESSION['AdminLoginID']) {
							$isEditCRM = 1;
						}
						else {
							$isEditCRM = 0;
						}
						if($isEditCRM==1) {
					?>
					<tr>
                        <td align="right" class="bold_text">
						  <input type="radio" name="editpotential" value="1" id="approvedbe" onclick="$.checkapprovalmethod('1')" /><?=strtoupper("Approved BE Data")?> &nbsp;&nbsp;
						  <input type="radio" name="editpotential" value="2" id="ownpotentail" onclick="$.checkapprovalmethod('2')" /><?=strtoupper("Provide Your Data")?>
						</td>
					</tr>
					
					<!-- Approved Same Data which has provided by BE -->
					<tr>
                      <td width="100%" valign="top" align="left">
					   <div id="samebedata" style="display:none;width:1165px; overflow-x:auto;overflow-y:none;!important">
                        <h2><span><?=strtoupper("Potential of Dr for JCL Products : Approved Same Data which has provided by BE")?></span></h2>
						<table cellpadding="2" cellspacing="2" border="0" style="width:<?=$this->tableWidth?>px">
                          <tr class="odd">
						  	<td align="center" valign="middle" rowspan="3" width="10%" class="bold_text"><?=strtoupper("Products")?></td>
							<td colspan="6" align="center" class="bold_text"><?=strtoupper("Current 3 Months")?></td>
							<td colspan="12" align="center" class="bold_text"><?=strtoupper("Expected 6 Months")?></td>
						  </tr>
						  
						  <tr class="even">
						    <?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center" colspan="2" class="bold_text"><?=date("M Y",strtotime($potential['month']))?></td>
							<input type="hidden" name="same_month[]" value="<?=$potential['month']?>"/>
							<?php } ?>
						  </tr>
						  
						  <tr class="odd">
						  	<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center" width="5%"><?=strtoupper("Unit")?></td>
							<td align="center" width="5%"><?=strtoupper("Value")?></td>
							<?php } ?>
						  </tr>
						  
						  <?php //echo "<pre>";print_r($this->appoints['crmproducts']);die;
							foreach($this->products as $i=>$product)
							{
								$trClass = ($i%2==0) ? 'odd' : 'even';
								if(in_array($product['product_id'],$this->appoints['crmproducts'])) {
						  ?>

						  <tr class="<?=$trClass?>">
                          	<td align="left" valign="top" class="bold_text"><?=$product['product_name']?></td>
							<input type="hidden" name="same_token1[]" value="<?=Class_Encryption::encode($product['product_id'])?>" />
							<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center">
								<input type="text" name="same_unit_<?=$potential['month']?>[]" id="same_unit_<?=$potential['month']?>_<?=$i?>" value="<?php if (@array_key_exists($product['product_id'], $this->appoints['potentialData'][$potential['month']])) { echo number_format($this->appoints['potentialData'][$potential['month']][$product['product_id']]['unit'],0); }?>" readonly="readonly" class="input-medium" />
							</td>
							<td align="center">
								<input type="text" name="same_value_<?=$potential['month']?>[]" id="same_value_<?=$potential['month']?>_<?=$i?>" value="<?php if (@array_key_exists($product['product_id'], $this->appoints['potentialData'][$potential['month']])) { echo $this->appoints['potentialData'][$potential['month']][$product['product_id']]['value']; }?>" readonly="readonly" class="input-medium" />
							</td>
							<?php } ?>
                          </tr>
						  <?php } } ?>
						  
						  <tr class="odd">
						  	<td align="right" class="bold_text">Total</td>
							<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="right" colspan="2"><input type="text" name="same_tot_val_<?=$potential['month']?>" id="tot_val_<?=$potential['month']?>" value="<?=$potential['month_total_value']?>" readonly="readonly" class="input-medium" /></td>
							<?php } ?>
						  </tr>
						  
						  <tr class="odd">
						  	<td align="left" colspan="19"><input type="submit" name="approvalsame" id="approvalsame" value="Approved BE Data" /></td>
						  </tr>					
                        </table>
					   </div>
                      </td>
                    </tr>
					
					<!-- Provide Own Data : Potential of Dr for JCL Products -->
					<tr>
                      <td width="100%" valign="top" align="left">
					   <div id="owndata" style="display:none;width:1165px; overflow-x:auto;overflow-y:none;!important">
                        <h2><span><?=strtoupper("Potential of Dr for JCL Products : Provide Own Data Against BE Data")?></span></h2>
						<table cellpadding="2" cellspacing="2" border="0" style="width:<?=$this->tableWidth?>px">
                          <tr class="odd">
						  	<td align="center" valign="middle" rowspan="3" width="10%" class="bold_text">Products</td>
							<td colspan="6" align="center" class="bold_text">Current 3 Months</td>
							<td colspan="12" align="center" class="bold_text">Expected 6 Months</td>
						  </tr>
						  
						  <tr class="even">
						    <?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center" colspan="2" class="bold_text"><?=date("M Y",strtotime($potential['month']))?></td>
							<input type="hidden" name="month[]" value="<?=$potential['month']?>"/>
							<?php } ?>
						  </tr>
						  
						  <tr class="odd">
						  	<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center" width="5%">Unit</td>
							<td align="center" width="5%">Value</td>
							<?php } ?>
						  </tr>
						  
						  <?php //echo "<pre>";print_r($this->appoints['crmproducts']);die;
							foreach($this->products as $i=>$product)
							{
								$trClass = ($i%2==0) ? 'odd' : 'even';
								if(in_array($product['product_id'],$this->appoints['crmproducts'])) {
						  ?>

						  <tr class="<?=$trClass?>">
                          	<td align="left" valign="top" class="bold_text"><?=$product['product_name']?></td>
							<input type="hidden" name="token1[]" value="<?=Class_Encryption::encode($product['product_id'])?>" />
							<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="center">
								<input type="text" name="unit_<?=$potential['month']?>[]" id="unit_<?=$potential['month']?>_<?=$i?>" class="input-medium" onblur="$.getValue('<?=$i?>','<?=$potential['month']?>','<?=(isset($this->productPrice[$product['product_id']])) ? $this->productPrice[$product['product_id']] : 0?>')" />
							</td>
							<td align="center">
								<input type="text" name="value_<?=$potential['month']?>[]" id="value_<?=$potential['month']?>_<?=$i?>" readonly="readonly" class="input-medium" />
							</td>
							<?php } ?>
                          </tr>
						  <?php } } ?>
						  
						  <tr class="even">
						  	<td align="right" class="bold_text">Total</td>
							<?php foreach($this->appoints['potentails'] as $potential) { ?>
							<td align="right" colspan="2"><input type="text" name="tot_val_<?=$potential['month']?>" id="tot_val_<?=$potential['month']?>" readonly="readonly" class="input-medium"/></td>
							<?php } ?>
						  </tr>
						  
						  <tr class="odd">
						  	<td align="left" colspan="19"><input type="submit" name="approval" id="approval" value="Update and Approved" /></td>
						  </tr>
                        </table>
					   </div>
                      </td>
                    </tr>
					<?php } ?>
					
					<!-- Chemist Information -->
					<tr>
					  <td width="100%" valign="top">
					    <table width="100%">
						  <tr class="even">
                          	<td width="10%" align="left" class="bold_text"><?=strtoupper("Chemist-1")?> :</td>
						  	<td width="25%" align="left"></td>
                          	<td width="10%" align="left" class="bold_text"><?=strtoupper("Contact Person")?> :</td>
						  	<td width="25%" align="left"></td>
                          	<td width="10%" align="left" class="bold_text"><?=strtoupper("Tel No.")?>:</td>
						  	<td width="20%" align="left"></td>
                          </tr>
                        
                          <tr class="odd">
						  	<td align="left" class="bold_text"><?=strtoupper("Chemist-2")?> :</td>
						  	<td align="left"></td>
						  	<td align="left" class="bold_text"><?=strtoupper("Contact Person ")?>:</td>
						  	<td align="left"></td>
						  	<td align="left" class="bold_text"><?=strtoupper("Tel No.")?>:</td>
						  	<td align="left"></td>
                          </tr>
						</table>
					  </td>
					</tr>
                   
                    <!-- DD/ Cheque Header -->
					<tr>
                        <td width="100%" align="left"><b><?=strtoupper("In Case of DD/ Cheque  give following detail")?>:</b></td>
					</tr>
                    
                    <!-- DD/ Cheque Information -->
					<tr>
                    <td width="100%" align="left" valign="top">
                      <table width="100%" cellpadding="2" cellspacing="2" border="0">
                        <tr class="odd">
                          <td width="20%" align="left" class="bold_text">DD/Cheque/to be made in favour of</td>
                          <td width="15%" align="left" valign="top"><?=$this->appoints['appointData']['favour']?></td>
                          <td width="10%" align="left" class="bold_text">Payable at</td>
                          <td width="55%" align="left" valign="top"><?=$this->appoints['appointData']['payble']?></td>
                        </tr>
                        
                        <?php $approvalArray = array(0=>'No Status Set',1=>'Approved',2=>'Rejected'); $fontArray = array(0=>'#000000;',1=>'#009900;',2=>'#FF0000;');?>
						
						<!--ABM : Approval and Comment Section-->
						<?php //if($_SESSION['AdminDesignation'] == 7 && ((isset($this->userhierarchy['ABM']['Key']) && $this->userhierarchy['ABM']['Key']>0)) ?>
						<?php if($_SESSION['AdminDesignation'] == 7 && $this->appoints['appointData']['abm_id'] == $_SESSION['AdminLoginID']) { 
						 	$abmRadio = '<input type="radio" name="abm_approval" value="1" /> Approved &nbsp;&nbsp; <input type="radio" name="abm_approval" value="2" /> Rejected';
							$abmComment = '<input type="text" name="abm_comment" id="zbm_comment" class="input-medium" pattern=".{25,500}" required title="Character length should be min 25 and max 500" />';
							$abmStatusDate = "";
							$abmButton = 0;
							if($_SESSION['AdminDesignation']==7 && $this->appoints['appointData']['abm_approval']==0) {
								$abmButton = 1;
							}
							else {
								$abmRadio = $approvalArray[$this->appoints['appointData']['abm_approval']];
								$abmStatusDate = ($this->appoints['appointData']['abm_comment_date'] != '0000-00-00 00:00:00') ? date('d, M Y',strtotime($this->appoints['appointData']['abm_comment_date'])) : '';
								$abmComment = $this->appoints['appointData']['abm_comment'];
								$abmButton = 0;
							}
						
						?>
                        <tr class="odd">
                          <td align="left" class="bold_text">ABM Approval Status</td>
                          <td align="left" <?php if(empty($abmStatusDate)) { ?>colspan="3"<?php } ?>><?=$abmRadio?></td>
						  <?php if(!empty($abmStatusDate)) { ?><td align="left" colspan="2" class="bold_text">ABM Status Date : &nbsp;&nbsp; <?=$abmStatusDate?></td><?php } ?>
                        </tr>
						
						<tr class="odd">
                          <td align="left" class="bold_text">ABM Comment</td>
                          <td align="left" valign="top" colspan="3"><?=$abmComment?></td>
                        </tr>
						
						<!--ABM : Update Button-->
						<?php if($abmButton==1) {?>
						<tr class="even">
                          <td align="left"></td>
                          <td align="left" colspan="3"><input type="submit" name="abmApproval" value="Updated By ABM" /></td>
                        </tr>
						<?php } } else if($_SESSION['AdminDesignation']<7 OR $_SESSION['AdminLoginID']==281) { 
							$abmRadio = '';
							$abmComment = '';
							$abmStatusDate = '';
							if($this->appoints['appointData']['abm_id']==0) {
								$abmRadio = 'No any ABM, so not needed approval !!';
								$abmComment = "No any ABM, so not needed approval !!";
							}
							else if($this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']==0) {
								$abmRadio = 'ABM approval needed !!';
								$abmComment = "ABM approval needed !!";
							}
							else {
								$abmRadio = $approvalArray[$this->appoints['appointData']['abm_approval']];
								$abmStatusDate = ($this->appoints['appointData']['abm_comment_date'] != '0000-00-00 00:00:00') ? date('d, M Y',strtotime($this->appoints['appointData']['abm_comment_date'])) : '';
								$abmComment = $this->appoints['appointData']['abm_comment'];
								$abmButton = 0;
							}
						?>
						
						<tr class="odd">
                          <td align="left" class="bold_text">ABM Approval Status</td>
                          <td align="left" <?php if(empty($abmStatusDate)) { ?>colspan="3"<?php } ?>><?=$abmRadio?></td>
						  <?php if(!empty($abmStatusDate)) { ?><td align="left" colspan="2" class="bold_text">ABM Status Date : &nbsp;&nbsp; <?=$abmStatusDate?></td><?php } ?>
                        </tr>
						
						<tr class="odd">
                          <td align="left" class="bold_text">ABM Comment</td>
                          <td align="left" valign="top" colspan="3"><?=$abmComment?></td>
                        </tr>
						<?php } ?>
						
						<!--RBM : Approval and Comment Section-->
						<?php //if($_SESSION['AdminDesignation'] == 6 && ((isset($this->userhierarchy['RBM']['Key']) && $this->userhierarchy['RBM']['Key']>0)) ?>
						<?php if($_SESSION['AdminDesignation'] == 6 && $this->appoints['appointData']['rbm_id'] == $_SESSION['AdminLoginID']) { 
						 	$rbmRadio = '<input type="radio" name="rbm_approval" value="1" /> Approved &nbsp;&nbsp; <input type="radio" name="rbm_approval" value="2" /> Rejected';
							$rbmComment = '<input type="text" name="rbm_comment" id="rbm_comment" class="input-medium" pattern=".{25,500}" required title="Character length should be min 25 and max 500" />';
							$rbmStatusDate = "";
							$rbmButton = 0;
							if($_SESSION['AdminDesignation']==6 && $this->appoints['appointData']['rbm_approval']==0 && $this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']==0) {
								$rbmRadio = 'Wait for ABM Approval !!';
								$rbmComment = "Wait for ABM Approval !!";
								$rbmButton = 0;
							}
							else if($_SESSION['AdminDesignation']==6 && $this->appoints['appointData']['rbm_approval']==0 && $this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']>0) {
								$rbmButton = 1;
							}
							else if($_SESSION['AdminDesignation']==6 && $this->appoints['appointData']['rbm_approval']==0 && $this->appoints['appointData']['abm_id']==0) {
								$rbmButton = 1;
							}
							else {
								$rbmRadio = $approvalArray[$this->appoints['appointData']['rbm_approval']];
								$rbmStatusDate = ($this->appoints['appointData']['rbm_comment_date'] != '0000-00-00 00:00:00') ? date('d, M Y',strtotime($this->appoints['appointData']['rbm_comment_date'])) : '';
								$rbmComment = $this->appoints['appointData']['rbm_comment'];
								$rbmButton = 0;
							}
						
						?>
                        <tr class="odd">
                          <td align="left" class="bold_text">RBM Approval Status</td>
                          <td align="left" <?php if(empty($rbmStatusDate)) { ?>colspan="3"<?php } ?>><?=$rbmRadio?></td>
						  <?php if(!empty($rbmStatusDate)) { ?><td align="left" colspan="2" class="bold_text">RBM Status Date : &nbsp;&nbsp; <?=$rbmStatusDate?></td><?php } ?>
                        </tr>
						
						<tr class="odd">
                          <td align="left" class="bold_text">RBM Comment</td>
                          <td align="left" valign="top" colspan="3"><?=$rbmComment?></td>
                        </tr>
						
						<!--RBM : Update Button-->
						<?php if($rbmButton==1) {?>
						<tr class="even">
                          <td align="left"></td>
                          <td align="left" colspan="3"><input type="submit" name="rbmApproval" value="Updated By RBM" /></td>
                        </tr>
						<?php } } else if($_SESSION['AdminDesignation']<6 OR $_SESSION['AdminLoginID']==281) { 
							$rbmRadio = '';
							$rbmComment = '';
							$rbmStatusDate = '';
							if($this->appoints['appointData']['rbm_id']==0) {
								$rbmRadio = 'No any RBM, so not needed approval !!';
								$rbmComment = "No any RBM, so not needed approval !!";
							}
							else if($this->appoints['appointData']['rbm_id']>0 && $this->appoints['appointData']['rbm_approval']==0) {
								$rbmRadio = 'RBM approval needed !!';
								$rbmComment = "RBM approval needed !!";
							}
							else {
								$rbmRadio = $approvalArray[$this->appoints['appointData']['rbm_approval']];
								$rbmStatusDate = ($this->appoints['appointData']['rbm_comment_date'] != '0000-00-00 00:00:00') ? date('d, M Y',strtotime($this->appoints['appointData']['rbm_comment_date'])) : '';
								$rbmComment = $this->appoints['appointData']['rbm_comment'];
								$rbmButton = 0;
							}
						?>
						
						<tr class="odd">
                          <td align="left" class="bold_text">RBM Approval Status</td>
                          <td align="left" <?php if(empty($rbmStatusDate)) { ?>colspan="3"<?php } ?>><?=$rbmRadio?></td>
						  <?php if(!empty($rbmStatusDate)) { ?><td align="left" colspan="2" class="bold_text">RBM Status Date : &nbsp;&nbsp; <?=$rbmStatusDate?></td><?php } ?>
                        </tr>
						
						<tr class="odd">
                          <td align="left" class="bold_text">RBM Comment</td>
                          <td align="left" valign="top" colspan="3"><?=$rbmComment?></td>
                        </tr>
						<?php } ?>
						
						<!--ZBM : Approval and Comment Section-->
						<?php //if($_SESSION['AdminDesignation'] == 5 && ((isset($this->userhierarchy['ZBM']['Key']) && $this->userhierarchy['ZBM']['Key']>0))  ?>
						<?php if($_SESSION['AdminDesignation'] == 5 && $this->appoints['appointData']['zbm_id'] == $_SESSION['AdminLoginID']) { 
						 	$zbmRadio = '<input type="radio" name="zbm_approval" value="1" /> Approved &nbsp;&nbsp; <input type="radio" name="zbm_approval" value="2" /> Rejected';
							$zbmComment = '<input type="text" name="zbm_comment" id="zbm_comment" class="input-medium" pattern=".{25,500}" required title="Character length should be min 25 and max 500" />';
							$zbmStatusDate = "";
							$zbmButton = 0;
							if($_SESSION['AdminDesignation']==5 && $this->appoints['appointData']['zbm_approval']==0 && $this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']==0) {
								$zbmRadio = 'Wait for ABM Approval !!';
								$zbmComment = "Wait for ABM Approval !!";
								$zbmButton = 0;
							}
							else if($_SESSION['AdminDesignation']==5 && $this->appoints['appointData']['zbm_approval']==0 && $this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']>0 && $this->appoints['appointData']['rbm_id']==0) {
								$zbmButton = 1;
							}
							else if($_SESSION['AdminDesignation']==5 && $this->appoints['appointData']['zbm_approval']==0 && $this->appoints['appointData']['rbm_id']>0 && $this->appoints['appointData']['rbm_approval']==0) {
								$zbmRadio = 'Wait for RBM Approval !!';
								$zbmComment = "Wait for RBM Approval !!";
								$zbmButton = 0;
							}
							else if($_SESSION['AdminDesignation']==5 && $this->appoints['appointData']['zbm_approval']==0 && $this->appoints['appointData']['rbm_id']>0 && $this->appoints['appointData']['rbm_approval']>0) {
								$zbmButton = 1;
							}
							else if($_SESSION['AdminDesignation']==5 && $this->appoints['appointData']['zbm_approval']==0 && $this->appoints['appointData']['rbm_id']==0 && $this->appoints['appointData']['abm_id']==0) {
								$zbmButton = 1;
							}
							else {
								$zbmRadio = $approvalArray[$this->appoints['appointData']['zbm_approval']];
								$zbmStatusDate = ($this->appoints['appointData']['zbm_comment_date'] != '0000-00-00 00:00:00') ? date('d, M Y',strtotime($this->appoints['appointData']['zbm_comment_date'])) : '';
								$zbmComment = $this->appoints['appointData']['zbm_comment'];
								$zbmButton = 0;
							}						
						?>
                        <tr class="odd">
                          <td align="left" class="bold_text">ZBM Approval Status</td>
                          <td align="left" <?php if(empty($zbmStatusDate)) { ?>colspan="3"<?php } ?>><?=$zbmRadio?></td>
						  <?php if(!empty($zbmStatusDate)) { ?><td align="left" colspan="2" class="bold_text">ZBM Status Date : &nbsp;&nbsp; <?=$zbmStatusDate?></td><?php } ?>
                        </tr>
						
						<tr class="odd">
                          <td align="left" class="bold_text">ZBM Comment</td>
                          <td align="left" valign="top" colspan="3"><?=$zbmComment?></td>
                        </tr>
						
						<!--ZBM : Update Button-->
						<?php if($zbmButton==1) {?>
						<tr class="even">
                          <td align="left"></td>
                          <td align="left" colspan="3"><input type="submit" name="zbmApproval" value="Updated By ZBM" /></td>
                        </tr>
						<?php } } else if($_SESSION['AdminDesignation']<5 OR $_SESSION['AdminLoginID']==281) { 
							$zbmRadio = '';
							$zbmComment = '';
							$zbmStatusDate = '';
							if($this->appoints['appointData']['zbm_id']==0) {
								$zbmRadio = 'No any ZBM, so not needed ZBM approval !!';
								$zbmComment = "No any ZBM, so not needed ZBM approval !!";
							}
							else if($this->appoints['appointData']['zbm_id']>0 && $this->appoints['appointData']['zbm_approval']==0) {
								$zbmRadio = 'ZBM approval needed !!';
								$zbmComment = "ZBM approval needed !!";
							}
							else {
								$zbmRadio = $approvalArray[$this->appoints['appointData']['zbm_approval']];
								$zbmStatusDate = ($this->appoints['appointData']['zbm_comment_date'] != '0000-00-00 00:00:00') ? date('d, M Y',strtotime($this->appoints['appointData']['zbm_comment_date'])) : '';
								$zbmComment = $this->appoints['appointData']['zbm_comment'];
								$zbmButton = 0;
							}
						?>
						
						<tr class="odd">
                          <td align="left" class="bold_text">ZBM Approval Status</td>
                          <td align="left" <?php if(empty($zbmStatusDate)) { ?>colspan="3"<?php } ?>><?=$zbmRadio?></td>
						  <?php if(!empty($zbmStatusDate)) { ?><td align="left" colspan="2" class="bold_text">ZBM Status Date : &nbsp;&nbsp; <?=$zbmStatusDate?></td><?php } ?>
                        </tr>
						
						<tr class="odd">
                          <td align="left" class="bold_text">ZBM Comment</td>
                          <td align="left" valign="top" colspan="3"><?=$zbmComment?></td>
                        </tr>
						<?php } ?>
                        <!--GM approval status -->
						<?php 
						if($_SESSION['AdminLoginID']==281) { 
							$GMRadio = '<input type="radio" name="gm_audit_status" value="1" /> Approved &nbsp;&nbsp; <input type="radio" name="gm_audit_status" value="2" /> Rejected';
							$GMComment = '<input type="text" name="gm_audit_comment" id="gm_audit_comment" class="input-medium" pattern=".{25,500}" required title="Character length should be min 25 and max 500" />';
							$GMStatusDate = "";
							$GMButton = 0;
							if($this->appoints['appointData']['gm_audit_status']==0 && $this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']==0) {
								$GMRadio = 'ABM Approval needed to start process by HO!!';
								$GMComment = "ABM Approval needed to start process by HO!!";
								$GMButton = 0;
							}
							else if($this->appoints['appointData']['gm_audit_status']==0 && $this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']>0 && $this->appoints['appointData']['rbm_id']==0 && $this->appoints['appointData']['zbm_id']==0) {
								$GMButton = 1;
							}
							else if($this->appoints['appointData']['gm_audit_status']==0 && $this->appoints['appointData']['rbm_id']>0 && $this->appoints['appointData']['rbm_approval']==0) {
								$GMRadio = 'RBM Approval needed to start process by HO!!';
								$GMComment = "RBM Approval needed to start process by HO!!";
								$GMButton = 0;
							}
							else if($this->appoints['appointData']['gm_audit_status']==0 && $this->appoints['appointData']['rbm_id']>0 && $this->appoints['appointData']['rbm_approval']>0 && $this->appoints['appointData']['zbm_id']==0) {
								$GMButton = 1;
							}
							else if($this->appoints['appointData']['gm_audit_status']==0 && $this->appoints['appointData']['zbm_id']>0 && $this->appoints['appointData']['zbm_approval']==0) {
								$GMRadio = 'ZBM Approval needed to start process by HO!!';
								$GMComment = "ZBM Approval needed to start process by HO!!";
								$GMButton = 0;
							}
							else if($this->appoints['appointData']['gm_audit_status']==0 && $this->appoints['appointData']['zbm_id']>0 && $this->appoints['appointData']['zbm_approval']>0) {
								$GMButton = 1;
							}
							else if($this->appoints['appointData']['gm_audit_status']==0 && $this->appoints['appointData']['zbm_id']==0 && $this->appoints['appointData']['rbm_id']==0 && $this->appoints['appointData']['abm_id']==0) {
								$GMButton = 1;
							}
							else {
								$GMRadio = $approvalArray[$this->appoints['appointData']['gm_audit_status']];
								$GMStatusDate = ($this->appoints['appointData']['business_audit_date'] != '0000-00-00 00:00:00') ? date('d, M Y',strtotime($this->appoints['appointData']['business_audit_date'])) : '';
								$GMComment = $this->appoints['appointData']['business_audit_comment'];
								$GMButton = 0;
							}
						
						?>
                        <tr class="odd">
                          <td align="left" class="bold_text">GM approval status</td>
                          <td align="left" <?php if(empty($GMStatusDate)) { ?>colspan="3"<?php } ?>><?=$GMRadio?></td>
						  <?php if(!empty($GMStatusDate)) { ?><td align="left" colspan="2" class="bold_text">GM Status Date : &nbsp;&nbsp; <?=$GMStatusDate?></td><?php } ?>
                        </tr>
						
						<tr class="odd">
                          <td align="left" class="bold_text">GM comment</td>
                          <td align="left" valign="top" colspan="3"><?=$GMComment?></td>
                        </tr>
						
						<!--GM : Update Button-->
						<?php if($hoButton==1) {?>
						<tr class="even">
                          <td align="left"></td>
                          <td align="left" colspan="3"><input type="submit" name="GMApproval" value="Updated By GM" /></td>
                        </tr>
						<?php } }?>
						<!--//GM approval status -->
                        <!--Admin/HO : Section Start-->
						<?php 
						if($_SESSION['AdminLoginID']==1) { 
							$hoRadio = '<input type="radio" name="business_audit_status" value="1" /> Approved &nbsp;&nbsp; <input type="radio" name="business_audit_status" value="2" /> Rejected';
							$hoComment = '<input type="text" name="business_audit_comment" id="business_audit_comment" class="input-medium" pattern=".{25,500}" required title="Character length should be min 25 and max 500" />';
							$hoStatusDate = "";
							$hoButton = 0;
							if($this->appoints['appointData']['business_audit_status']==0 && $this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']==0) {
								$hoRadio = 'ABM Approval needed to start process by HO!!';
								$hoComment = "ABM Approval needed to start process by HO!!";
								$hoButton = 0;
							}
							else if($this->appoints['appointData']['business_audit_status']==0 && $this->appoints['appointData']['abm_id']>0 && $this->appoints['appointData']['abm_approval']>0 && $this->appoints['appointData']['rbm_id']==0 && $this->appoints['appointData']['zbm_id']==0) {
								$hoButton = 1;
							}
							else if($this->appoints['appointData']['business_audit_status']==0 && $this->appoints['appointData']['rbm_id']>0 && $this->appoints['appointData']['rbm_approval']==0) {
								$hoRadio = 'RBM Approval needed to start process by HO!!';
								$hoComment = "RBM Approval needed to start process by HO!!";
								$hoButton = 0;
							}
							else if($this->appoints['appointData']['business_audit_status']==0 && $this->appoints['appointData']['rbm_id']>0 && $this->appoints['appointData']['rbm_approval']>0 && $this->appoints['appointData']['zbm_id']==0) {
								$hoButton = 1;
							}
							else if($this->appoints['appointData']['business_audit_status']==0 && $this->appoints['appointData']['zbm_id']>0 && $this->appoints['appointData']['zbm_approval']==0) {
								$hoRadio = 'ZBM Approval needed to start process by HO!!';
								$hoComment = "ZBM Approval needed to start process by HO!!";
								$hoButton = 0;
							}
							else if($this->appoints['appointData']['business_audit_status']==0 && $this->appoints['appointData']['zbm_id']>0 && $this->appoints['appointData']['zbm_approval']>0) {
								$hoButton = 1;
							}
							else if($this->appoints['appointData']['business_audit_status']==0 && $this->appoints['appointData']['zbm_id']==0 && $this->appoints['appointData']['rbm_id']==0 && $this->appoints['appointData']['abm_id']==0) {
								$hoButton = 1;
							}
							else {
								$hoRadio = $approvalArray[$this->appoints['appointData']['business_audit_status']];
								$hoStatusDate = ($this->appoints['appointData']['business_audit_date'] != '0000-00-00 00:00:00') ? date('d, M Y',strtotime($this->appoints['appointData']['business_audit_date'])) : '';
								$hoComment = $this->appoints['appointData']['business_audit_comment'];
								$hoButton = 0;
							}
						
						?>
                        <tr class="odd">
                          <td align="left" class="bold_text">Business Audit by HO</td>
                          <td align="left" <?php if(empty($hoStatusDate)) { ?>colspan="3"<?php } ?>><?=$hoRadio?></td>
						  <?php if(!empty($hoStatusDate)) { ?><td align="left" colspan="2" class="bold_text">HO Status Date : &nbsp;&nbsp; <?=$hoStatusDate?></td><?php } ?>
                        </tr>
						
						<tr class="odd">
                          <td align="left" class="bold_text">Comment of HO</td>
                          <td align="left" valign="top" colspan="3"><?=$hoComment?></td>
                        </tr>
						
						<!--Admin/HO : Update Button-->
						<?php if($hoButton==1) {?>
						<tr class="even">
                          <td align="left"></td>
                          <td align="left" colspan="3"><input type="submit" name="hoApproval" value="Updated By HO" /></td>
                        </tr>
						<?php } ?>
                        
						<?php if($this->appoints['appointData']['business_audit_status']==1) {?>
						<tr class="even">
                          <td align="left">Date of Disbursement</td><!--<input type="date" name="disburse_date" id="disburse_date" class="input-medium"/>-->
                          <td align="left" valign="top"><input type="text" name="disburse_date" id="disburse_date" class="input-medium"/></td>
                          <td align="left">Mode</td>
                           <td align="left" valign="top"><select name="mode" id="mode"><option value="draft">Draft</option><option value="cheque">Cheque</option></select></td>
                        </tr>
						

						<tr class="odd">
                          <td align="left">DD/Cheque Number</td>
                          <td align="left" valign="top"><input type="text" name="dd_chq_no" id="dd_chq_no" required aria-required="true" class="input-medium"/></td>
                          <td align="left">Amount</td>
                           <td align="left" valign="top"><input type="number" name="amount" id="amount" pattern="[0-9]+([\.|,][0-9]+)?" step="0.01" title="Amount should be a number with up to 2 decimal places." required aria-required="true" class="input-medium"/></td>
                        </tr>
						
						<tr class="even">
                          <td align="left" colspan="4"><strong>Transaction Details</strong></td>
                        </tr>
						
						<tr class="odd">
                          <td align="left"><strong>Transaction Amount</strong></td>
                          <td align="left" valign="top"><strong>Transaction Mode</strong></td>
                          <td align="left"><strong>DD / Cheque Number</strong></td>
                           <td align="left" valign="top"><strong>Date of Disbursement</strong></td>
                        </tr>
						
						<?php if(count($this->appoints['transactions'])>0) {
								$totalAmount = 0;
								foreach($this->appoints['transactions'] as $key=>$transaction) {
									$class = ($key%2==0)?'even':'odd';									
						?>
						<tr class="<?=$class?>">
                          <td align="left"><?=$transaction['amount']?></td>
                          <td align="left" valign="top"><?=ucfirst($transaction['mode'])?></td>
                          <td align="left"><?=$transaction['dd_chq_no']?></td>
                           <td align="left" valign="top"><?=$transaction['disburse_date']?></td>
                        </tr>
						<?php $totalAmount += $transaction['amount']; } ?>
						<tr class="even">
                          <td align="left" colspan="4"><strong>Total Amount : <?=number_format($totalAmount,2)?></strong></td>
                        </tr>
						<?php } else { ?>
						<tr class="even">
                          <td align="left" colspan="4" style="color:#FF0000;"><strong>No Transaction added yet!!</strong></td>
                        </tr>
						<?php } ?>
                        
                        <tr class="even">
                          <td align="left">Remark/Note</td>
                          <td align="left" valign="top" colspan="3"><textarea rows="5" cols="80" name="remarks" required aria-required="true"><?=$this->appoints['appointData']['remarks']?></textarea></td>
                        </tr>
						<?php } } ?>						
                      </table>
                    </td>
                  </tr>
				  </thead>
				</table>
				
<script type="text/javascript">
    $(document).ready(function () {
        //$("input").attr('disabled','disabled');
        $("select").attr('disabled','disabled');
    });
</script>             
			  