		<div class="full_w">
                <div class="h_title">Leave Request</div>
				<div class="entry">
                    <div class="sep"></div>
                		<a href="<?=$this->url(array('controller'=>'Requestmanager','action'=>'leavenotification'),'default',true)?>" class="button back">Back to List</a>
                        <a href="<?=$this->url(array('controller'=>'Requestmanager','action'=>'notificationreply','token'=>$this->info['ID']),'default',true)?>" class="button refresh">Refresh</a>
				  <div class="sep1"></div>
                </div>
                
                <form name="leaveRequestForm" id="leaveRequestForm" action="" method="post"> 
                <table width="100%">
                  <thead><?php echo $this->replyForm;?></thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>
	
	<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$('#replydiv').hide();
	});
	</script>