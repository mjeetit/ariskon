<div class="grid_12">
  <div class="module">
    <h2><span>Add New </span></h2>
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
					  <td align="left" width="20%">Gift Name <span class="strick">*</span> :</td>
					  <td align="left" width="80%"><input type="text" name="table[gift_name]" id="gift_name" value="<?=$this->postData['gift_name']?>" required aria-required="true" class="input-medium" autofocus/></td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left">Gift Quantity <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="table[quantity]" id="quantity" value="<?=$this->postData['quantity']?>" required aria-required="true" class="input-medium"  pattern="[1-9][0-9]{0,2}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"/></td>
					</tr>
                    
                    <tr class="even">
					  <td align="left">Valid From <span class="strick">*</span> :</td>
					  <td align="left">
                      	<input name="table[valid_from]" id="from_date" value="<?=(isset($this->postData['valid_from'])) ? $this->postData['valid_from'] : date('Y-m-d')?>" class="input-short" />
                      </td>
					</tr>
					
					<tr class="odd">
					  <td align="left">Valid To <span class="strick">*</span> :</td>
					  <td align="left">
                      	<input name="table[valid_to]" id="to_date" value="<?=(isset($this->postData['valid_to'])) ? $this->postData['valid_to'] : date('Y-m-d',strtotime('+1 month'))?>"  class="input-short" />
                      </td>
					</tr>
					
					<tr class="event">
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