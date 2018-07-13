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
					    <a href="<?php echo $this->url(array('controller'=>'Locationtype','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<tr class="even">
					  <td align="left" width="20%">Location Type Name <span class="strick">*</span> :</td>
					  <td align="left" width="80%"><input type="text" name="type_name" id="type_name" required aria-required="true" class="input-medium" autofocus/></td>
					</tr>
                    
                    <tr class="odd">
					  <td align="left">Location Type Code <span class="strick">*</span> :</td>
					  <td align="left"><input type="text" name="type_code" id="type_code" required aria-required="true" class="input-medium" autofocus/></td>
					</tr>
                    
                    <tr class="even">
					  <td align="left">Status <span class="strick">*</span> :</td>
					  <td align="left">
                      	<input type="radio" name="status" id="statusOn" value="1" checked="checked" />&nbsp;ON &nbsp;&nbsp;
                        <input type="radio" name="status" id="statusOff" value="0" />&nbsp;OFF
                      </td>
					</tr>
					
					<tr class="event">
					  <td style="border:none" colspan="2"><input type="submit" name="locationtypeAdd" id="locationtypeAdd" value="Save"></td>
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