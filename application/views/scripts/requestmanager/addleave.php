<div class="grid_12">
                <div class="module">
                     <h2><span>New Request</span></h2>
                     <div class="module-body">
                       <form name="data_setting" id="data_setting" action="" method="post">
					   <table width="70%" style="border:none">
						<thead>
						  <tr>
							<td align="center" style="border:none">
								<table style=" width:70%">
									<thead>
									
									<tr><th colspan="2">Add Request</th></tr>
									<?=$this->requestForm?></thead>
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
<script type="text/javascript" language="javascript">
	$(function() {
		$("#leave_from").datepicker({
			showOn: "button",
			buttonImage: "<?=Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		$("#leave_to").datepicker({
			showOn: "button",
			buttonImage: "<?=Bootstrap::$baseUrl?>javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
	});
	
	$.checkLeave = function(value) { 
		var textValue = $("#leaveDays_"+value).val();
		var restValue = $("#rest_"+value).val();
		
		if((restValue-textValue)<0) {
			$('#leaveDays_'+value).css('border', '1px solid #FF0000');
			$('#leaveDays_'+value).css('color','#FF0000');
			$('#err_'+value).css('color','#FF0000');
			if(restValue<0){
				$('#err_'+value).html('Your leaves already in Negative that is !'+restValue);
			}
			else{
				$('#err_'+value).html('Please not enter leave days more than '+restValue);
			}
			//$('#SendRequest').hide();//attr('readOnly',true);
			//$("#leaveDays_"+value).val('');
		}
		else {
			$('#leaveDays_'+value).css('border', '1px solid #DDDDDD');
			$('#leaveDays_'+value).css('color', '#000000');
			$('#err_'+value).html('');
			$('#SendRequest').show();//attr('readOnly',false);
			//$("#leaveDays_"+value).val('');
		}
                if(value==1 && textValue>3){
                    $('#leaveDays_'+value).css('border', '1px solid #FF0000');
                    $('#leaveDays_'+value).css('color','#FF0000');
                    $('#err_'+value).css('color','#FF0000');
                    $('#err_'+value).html('Please not enter leave days more than 3');
                    $('#SendRequest').hide();//attr('readOnly',true);
                    $("#leaveDays_"+value).val('');
                }
                if(value==1 && textValue>0){
                    $("#leaveDays_2").val('');
                }
                if(value==2 && textValue>0){
                   $("#leaveDays_1").val('');
                }
	}
	function checkDatevalidate(){
	 var dateback   = '<?php echo date('Y-m-d',mktime(0, 0, 0, date("m"),date("d")-30,  date("Y")));?>';
	 var leave_from =  $("#leave_from").val();
	 var leave_to   =  $("#leave_to").val();
	 
	 if(leave_from<dateback){
	    $('#errfrom').html('From date can not more thaan 15 days back');
	 }else if(leave_from>leave_to){
	    $('#errto').html('To date always greater than from date');
	 }else if($("#leaveDays_1").val()<=0 && $("#leaveDays_2").val()<=0 && $("#leaveDays_3").val()<=0){
           alert('Please enter Value in Any type leave');return false;
        }else if($("#leaveDays_1").val()>3){
            $('#leaveDays_1').css('border', '1px solid #FF0000');
            $('#leaveDays_1').css('color','#FF0000');
            $('#err_1').css('color','#FF0000');
            $('#err_1').html('Please not enter leave days more than '+restValue);
            $('#SendRequest').hide();//attr('readOnly',true);
            $("#leaveDays_1").val('');
        }else if($("#leaveDays_1").val()>0 && $("#leaveDays_2").val()>0){
           $('#err_1').html('Please Fill only one type of leave at a time');
        }else{
	   $('#errfrom').html('');
	   $('#errfrom').html('');
	   $('#data_setting').submit()
	 }
	}
function autofileleave(value){
   var textValue = $("#leaveDays_"+value).val();
    var leave_from =  $("#leave_from").val();
	 var leave_to   =  $("#leave_to").val();
	 var fromdateArr = leave_from.split('-');
	 var todateArr   = leave_to.split('-'); 
	 var Date1 = new Date (fromdateArr[0], fromdateArr[1], fromdateArr[2]);
	 var Date2 = new Date (todateArr[0], todateArr[1], todateArr[2]);
	 var Days = Math.ceil(Math.abs((Date2.getTime() - Date1.getTime())/(1000*60*60*24)))+1;
	 
	 var halftime   =  $("#halfday_time").val();
	 var halfleave = 0;
	 if(halftime==1){
	    halfleave = 0.5;
	 }
	 Days = Days - halfleave;
	 if(value==1 && Days>3){
	   $("#leaveDays_"+value).val(3);
	 }else if(!isNaN(Days)){
	   $("#leaveDays_"+value).val(Days); 
	 }else{
	   $("#leaveDays_1").val('');
	   $("#leaveDays_2").val('');
	   $("#leaveDays_3").val('');
	 }
	 if(value==1 && $("#leaveDays_"+value).val()>0){
		$("#leaveDays_2").val('');
		$("#leaveDays_3").val('');
	}
	if(value==2 && $("#leaveDays_"+value).val()>0){
	   $("#leaveDays_1").val('');
	   $("#leaveDays_3").val('');
	}
	if(value==3 && $("#leaveDays_"+value).val()>0){
	   $("#leaveDays_1").val('');
	   $("#leaveDays_2").val('');
	}
   }	
</script>					   
