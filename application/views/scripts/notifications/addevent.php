<div class="grid_12">
                <div class="module">
                     <h2><span>New Event</span></h2>
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
											<a href="<?php echo $this->url(array('controller'=>'Notifications','action'=>'event'),'default',true)?>" class="button back">
											<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
											</a>
											</td>
										</tr>
										<tr>
										<th colspan="2">Add  Event</th>
						    </tr>
							 <?php echo $this->eventFrom;?>
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
<script type="text/javascript">

$(function() {

		$("#event_date").datepicker({

			showOn: "button",

			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",

			buttonImageOnly: true,

			dateFormat: "yy-mm-dd"

		});

	});

</script>