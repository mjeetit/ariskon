<div class="grid_12">
  <div class="module">
    <h2><span>Update Detail </span></h2>
	<div class="module-body">
	  <form name="editform" id="editform" action="" method="post"> 
	    <table width="70%" style="border:none">
		  <thead>
		    <tr>
			  <td align="center" style="border:none">
			    <table style=" width:100%">
				  <thead>
				    <tr class="odd">
					  <td colspan="8" align="left" style="border:none">
					    <a href="<?=$this->url(array('controller'=>'Measurement','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr class="even">
					  <td align="left" width="20%" class="bold_text">Measurement Type <span class="strick">*</span> :</td>
					  <td align="left" width="80%"><input type="text" name="data[type_name]" id="type_name" required aria-required="true" class="input-medium" value="<?=$this->info['type_name']?>" autofocus/></td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left" class="bold_text">Status <span class="strick">*</span> :</td>
					  <td align="left">
                      	<input type="radio" name="data[isActive]" id="statusOn" value="1" checked="checked" />&nbsp;ON &nbsp;&nbsp;
                        <input type="radio" name="data[isActive]" id="statusOff" value="0" />&nbsp;OFF
                      </td>
					</tr>
					
					<tr class="event">
					  <td colspan="2"><input type="submit" name="updform" value="Update Detail" class="submit-green" /></td>
					</tr>
				  </thead>
				</table>
			  </td>
			</tr>
		  </thead>
		</table>
	  </form>
	</div> <!-- End .module-body //$.fillIformation = function(value) {-->
  </div>  <!-- End .module -->
  <div style="clear:both;"></div>
</div>