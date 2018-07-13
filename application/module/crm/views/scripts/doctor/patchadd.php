<div class="grid_12">
  <div class="module">
    <h2><span>Add / Edit Patch Detail </span></h2>
	<div class="module-body">
	  <form name="data_setting" action="" method="post"> 
	    <table width="70%" style="border:none">
		  <thead>
		    <tr>
			  <td align="center" style="border:none">
			    <table style=" width:100%">
				  <thead>
				    <tr class="odd">
					  <td colspan="2" align="left" style="border:none">
					    <a href="<?php echo $this->url(array('controller'=>'Doctor','action'=>'patchcode'),'default',true)?>" class="button back">
						  <span>Back to Patch Lists<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
					  </td>
					</tr>
					
					<!--Business Unit Drop Down List-->
                    <tr class="even">
                      <td width="15%">Business Unit</td>
                      <td width="85%">
                      	<select name="bunit_id" id="bunit_id" onchange="getNextRecord(this.value,5,'zone_id','');" required>
                      	<option value="">--Select Bussness Unit--</option>
                        <?php 
							$bunits = $this->ObjModel->getBissnessUnit();
							foreach($bunits as $bunit){
						?>
                        <option value="<?php echo $bunit['bunit_id']?>" <?php echo $select;?>><?php echo $bunit['bunit_name']?></option>
						<?php } ?>
						</select>
                      </td>
                    </tr>
                    
                    <!--Zone Drop Down List-->
                    <tr class="odd">
					  <td width="40px">Zone Name</td>
                      <td width="40px">
                      	<select name="zone_id" id="zone_id" onchange="getNextRecord(this.value,6,'region_id','');" required>
                      		<option value="">--Select Zone--</option>
                        </select>
                      </td>
					</tr>
                    
                    <!--Region Drop Down List-->
                    <tr class="even">
                      <td width="40px">Region Name</td>
                      <td width="40px">
                      	<select name="region_id" id="region_id" onchange="getNextRecord(this.value,7,'area_id','');" required>
                      		<option value="">--Select Region--</option>
                        </select>
                      </td>
					</tr>
                    
                    <!--Area Drop Down List-->
                    <tr class="odd">
                      <td width="40px">Area Name</td>
                      <td width="40px">
                      	<select name="area_id" id="area_id" onchange="getNextRecord(this.value,8,'headquater_id','');" required>
                      		<option value="">--Select Area--</option>
                        </select>
                      </td>
					</tr>
                    
                    <!--Headquarter Drop Down List-->
                    <tr class="even">
                      <td width="40px">Headquarter</td>
                      <td width="40px">
                      	<select name="headquater_id" id="headquater_id" onchange="getNextRecord(this.value,9,'city_id','');" required>
                      		<option value="">--Select HeadQuater--</option>
                        </select>
                      </td>
					</tr>
                    
                    <!--City Drop Down List-->
                    <tr class="odd">
                      <td width="40px">City</td>
                      <td width="40px">
                      	<select name="city_id" id="city_id" required>
                      		<option value="">--Select City--</option>
                        </select>
                      </td>
					</tr>
                    
                    <tr class="odd">
                      <td width="40px">Patch Name</td>
                      <td width="40px"><input type="text" name="patch_name" required /></td>
                    </tr>
					
					<tr class="even">
					  <td style="border:none" colspan="2"><input type="submit" name="addPatch" id="addPatch" value="Save"></td>
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
            
<script type="text/javascript">	
	$.getNextRecord = function(id,level,target,selected){
		$.ajax({
			  url: '<?=Bootstrap::$baseUrl?>/Ajax/changestatusrecord',
			  data: 'id='+id+'&level='+level+'&selected='+selected,
			  success: function(data) {
				 $("#"+target).html(data);
			  }
		});
	}
</script>