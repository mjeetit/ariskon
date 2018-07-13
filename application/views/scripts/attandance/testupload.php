<div class="grid_12">
<div class="module">
 <h2><span>Uploade Attandance</span></h2>
 <div class="module-body">
   <form name="data_setting" action="" method="post" enctype="multipart/form-data"> 
   <table width="70%" style="border:none">
	<thead>
	  <tr>
		<td align="center" style="border:none">
			<table style=" width:70%">
				<thead>
				<tr>
					<td colspan="2" align="left" style="border:none">
						<a href="<?php echo $this->url(array('controller'=>'Attandance','action'=>'attandancelist'),'default',true)?>" class="button back">
						<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
						</a>
						</td>
					</tr>
					<tr>
					<th colspan="2">Uploade</th>
					</tr>
						<tr class="odd">
							<td style="border:none">Selete Attandance Sheet</td>
							<td style="border:none">
							<input type="file" name="attandancesheet" />
							</td>
						</tr>
	  <tr class="even">
		<td colspan="2" align="center">
			<input type="submit" name="bunittocountry" value="Uploade"  class="submit-green" />
			<input type="submit" name="bunittocountry" value="Uploade With Header"  class="submit-green" />
		</td>
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
   
