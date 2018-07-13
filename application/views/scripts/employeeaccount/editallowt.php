<div class="grid_12">
                <div class="module">
                     <h2><span>Edit Allot</span></h2>
                     <div class="module-body">
                        <form name="form_company" action="" method="post"> 
						<table width="70%" style="border:none">
							<thead>
							<tr> 
							<td colspan="2"> 
							<a href="<?php echo $this->url(array('controller'=>'Employeeaccount','action'=>'viewallowt'),'default',true)?>" class="button">
                        	<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
					</td>
							</tr>
							<tr>
							<td align="center" style="border:none">
							 <table style="width:70%">
							   <tr><th colspan="2">Edit Detail</th></tr> 
								<tr>
								 <?php echo $this->empAccForm;?>
								</tr>
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
showmonthtextbox('<?php echo $this->acceseriesdata['refundable']?>');
changeStatusBusiness('<?php echo $this->acceseriesdata['bunit_id']?>','<?php echo $this->acceseriesdata['department_id']?>');
changeStatusDepartment('<?php echo $this->acceseriesdata['department_id']?>','<?php echo $this->acceseriesdata['designation_id']?>');
getUsersByDesignation('<?php echo $this->acceseriesdata['designation_id']?>','<?php echo $this->acceseriesdata['user_id']?>');
$(function() {
		$("#allowt_date").datepicker({
			showOn: "button",
			buttonImage: "<?php echo Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
	});
</script>