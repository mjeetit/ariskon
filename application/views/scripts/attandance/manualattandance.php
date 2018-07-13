<div class="grid_12">
<div class="module">
 <h2><span>Manual Attandance</span></h2>
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
					<th colspan="2">Manual Attandence</th>
					</tr>
					<tr class="odd">
							<td style="border:none">Employee Code</td>
							<td style="border:none">
							<input type="text" name="employee_code"  class="input-medium"/>
							</td>
						</tr>
					<tr class="even">
							<td style="border:none">Month/Year</td>
							<td style="border:none">
							<input type="text" name="month"  class="input-short"/>/<input type="text" name="year"  class="input-short"/>
							</td>
						</tr>
                                        <?php
                                        //$days  = cal_days_in_month(CAL_GREGORIAN,date('d'),date('Y'));
                                        for($i=1;$i<=31;$i++){?>
					<tr class="odd">
							<td style="border:none">Day <?php echo $i?></td>
							<td style="border:none">
							Present<input type="radio" name="day<?php echo $i ?>[]" value="P" />&nbsp;
                                                        Leave Without Pay<input type="radio" name="day<?php echo $i ?>[]" value="A" />&nbsp;
                                                        Leave<input type="radio" name="day<?php echo $i ?>[]" value="L" />&nbsp;
                                                        Half Leave<input type="radio" name="day<?php echo $i ?>[]" value="H" />&nbsp;
                                                        Half LWP<input type="radio" name="day<?php echo $i ?>[]" value="HL" />&nbsp;
							</td>
						</tr>
                                        <?php } ?>
                                         <tr class="odd">
							<td style="border:none">Calculate Salary On Days</td>
							<td style="border:none"><input type="text" name="total_salary_days"  class="input-short" />&nbsp;</td>
						</tr>
					
	  <tr class="even">
		<td colspan="2" align="center">
			<input type="submit" name="attandance" value="Update"  class="submit-green" />
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
   
