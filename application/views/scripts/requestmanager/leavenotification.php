 <div class="full_w">
                <div class="h_title">Leave Request Notification</div>
				<div class="entry">
                    <div class="sep"></div>
                		<a href="<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'leavenotification'),'default',true)?>" class="button refresh">Refresh</a>
				  <div class="sep1"></div>
                </div>
               <form method="post" action=""> 
                <table width="100%">
                    <thead>
						<tr>
						<th width="10%">Requested By</th>
						<th width="10%">Applied Date</th>
						<th width="10%">Leave From</th>
						<th width="10%">Leave To</th>
						<th width="10%">Leave Days</th>
						<th width="10%">Approved By</th>
						<th width="10%">Rejected By</th>
						<th width="10%">Your Status</th>
						<th width="20%">Action</th>
						</tr>
						
						<?php if(count($this->leavelists) > 0) { foreach($this->leavelists as $leavelist) { ?>
						<tr>
						<td align="left"><?=$leavelist['Name']?></td>
						<td align="center"><?=$leavelist['Request']?></td>
						<td align="center"><?=$leavelist['From']?></td>
						<td align="center"><?=$leavelist['To']?></td>
						<td align="center"><?=$leavelist['Days']?></td>
						<td align="center">
							<?php
								if(count($leavelist['Approval'])) {
									foreach($leavelist['Approval'] as $app) {
										echo $app['Name'].",<br>";
									}
								}
								else {
									echo "--";
								}
							?>
						</td>
						<td align="center">
							<?php
								if(count($leavelist['Approval'])) {
									foreach($leavelist['Approval'] as $app) {
										echo $app['Name'].",<br>";
									}
								}
								else {
									echo "--";
								}
							?>
						</td>
						<td align="center"><?=$leavelist['Status']?></td>
						<td align="left">
						  <?php if(empty($leavelist['Status'])) {?>
						  <a href="javascript:void();" onclick="$.setStatus(<?=$leavelist['Token']?>,5)" title="Approved this request">Approved</a>
						  <strong>|</strong> <a onclick="fancyboxRemark('<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'rejectrequest','token'=>$leavelist['Token']),'default',true)?>')" title="Reject request">Reject</a><!--<a href="javascript:void();" onclick="$.setStatus(<?=$leavelist['Token']?>,4)" title="Reject request">Reject</a>--> <strong>|</strong> 
						  <?php } ?>
						  <a href="<?=$this->url(array('controller'=>'Requestmanager','action'=>'notificationreply','token'=>$leavelist['Token']),'default',true)?>">View</a>
						  <strong>|</strong> <a onclick="fancyboxType('<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'leavetype','token'=>$leavelist['Token']),'default',true)?>')" title="Reject request">Leave Type</a>
						</td>
						</tr>
						<?php }} else{ ?>
						<tr>
						<td align="center" colspan="7">No leave notification found till now !!</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>
	
	<script type="text/javascript" language="javascript">
	function fancyboxRemark(url){
	 	$.fancybox({
			"width": "80%",
			"height": "100%",
			"autoScale": true,
			"transitionIn": "fade",
			"transitionOut": "fade",
			"type": "iframe",
			"href": url
		}); 
	}
	
	function fancyboxType(url){
	 	$.fancybox({
			"width": "50%",
			"height": "50%",
			"autoScale": true,
			"transitionIn": "fade",
			"transitionOut": "fade",
			"type": "iframe",
			"href": url
		}); 
	}
	
	$.setStatus = function(value,type) {
		$.ajax({
			type : "POST",
			url  : "<?=Bootstrap::$baseUrl?>Requestmanager/setrequeststatus?token="+value+"&type="+type,
			
			beforeSend: function(){
				//
			},
			success: function(response) {//alert(response);return false;
				document.location.reload(true);
			}
		});
	}
	</script>