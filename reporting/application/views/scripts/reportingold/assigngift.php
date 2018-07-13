<div class="grid_12">
  <div class="module">
    <h2><span>Assign Gift to BE </span></h2>
	<div class="module-body">
	  <form name="data_setting" action="" method="post"> 
	    <table width="70%" style="border:none">
		  <thead>
		    <tr>
			  <td align="center" style="border:none">
			    <table style=" width:100%">
				  <thead>
				    <tr class="odd">
					  <td colspan="8" align="left" style="border:none">
					    <a href="<?=$this->url(array('controller'=>'Reporting','action'=>'gift'),'default',true)?>" class="button back">
						  <span>Back<img src="<?=IMAGE_LINK?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr class="even">
					  <td align="left" width="20%">Gift Name :</td>
					  <td align="left" width="80%"><?=$this->gift['gift_name']?></td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left">Available Gift Quantity :</td>
					  <td align="left"><?=$this->gift['rest_quantity']?></td>
					</tr>
                    
                    <tr class="even">
					  <td align="left">Validity :</td>
					  <td align="left"><?=$this->gift['valid_from'].' To '.$this->gift['valid_to']?></td>
					</tr>
					
					<tr class="odd">
					  <td align="left">Select BE <span class="strick">*</span> :</td>
					  <td align="left">
                      	<input type="hidden" name="table[gift_id]" value="<?=$this->gift['gift_id']?>"/>
                      	<?php if($_SESSION['AdminDesignation']<8) { ?>
                          <select name="table[user_id]" required autofocus>
                            <option value="">-- Select BE --</option>
                            <?php foreach($this->beDetails as $beDetail){
                                $select = '';
                                if($this->postData['gift_id']==$beDetail['user_id']){
                                    $select = 'selected="selected"';
                                }
                            ?>
                            <option value="<?=$beDetail['user_id']?>" <?=$select?>><?=$beDetail['first_name'].' '.$beDetail['last_name']?></option>
                            <?php } ?>
                          </select>
                          <?php } ?>
                      </td>
					</tr>
					
					<tr class="even">
					  <td align="left">Quantity :</td>
					  <td align="left">
                      	<input type="text" name="table[assigned_quantity]" value="<?=$this->postData['assigned_quantity']?>" id="asqn" required aria-required="true" class="input-medium"  pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" onblur="if(this.value><?=$this->gift['rest_quantity']?>) { $('#asqn').val(0); $('#quanerror').show(); }" />
                        <span id="quanerror" style="display:none; color:#FF0000;">Assigned quantity should not more than <?=$this->gift['rest_quantity']?>!!</span>
                      </td>
					</tr>
					
					<tr class="odd">
					  <td style="border:none" colspan="2"><input type="submit" name="addnewdata" id="addnewdata" value="Save"></td>
					</tr>
				  </thead>
				</table>
			  </td>
			</tr>
		  </thead>
		</table>
	  </form>
	</div>
  </div>
  <div style="clear:both;"></div>
</div>

<script type="text/javascript">
	$(function() {
		$("#from_date").datepicker({
			showOn: "button",
			buttonImage: "<?=Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		$("#to_date").datepicker({
			showOn: "button",
			buttonImage: "<?=Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		
	});
</script>