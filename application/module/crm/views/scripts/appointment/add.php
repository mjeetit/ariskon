<div class="grid_12">
  <div class="module">
    <h2><span>Add New CRM</span></h2>
	<div class="module-body">
	  <form name="data_setting" action="" method="post"> 
	    <table width="70%" style="border:none;">
		  <thead>
		    <tr>
			  <td align="center" style="border:none;">
			    <table style=" width:100%;">
				  <thead>
				    <tr>
					  <td align="left" style="border:none">
					    <a href="<?php echo $this->url(array('controller'=>'Appointment','action'=>'index'),'default',true)?>" class="button back">
						  <span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
						</a>
                        <a href="<?php echo $this->url(array('controller'=>'Appointment','action'=>'add'),'default',true)?>" class="button back">
						  <span>Referesh<img src="<?php echo IMAGE_LINK;?>/refresh.png" width="12" height="9" alt="Referesh" /></span>
						</a>
					  </td>
					</tr>

					<tr id="errorTable">
					  <td align="left" width="100%">
					  <table style="width:100%;">
                        <div id="errorNotify" style="display:none; padding-left:5px; padding-top:10px; height:30px; font-size:12px; font-weight:bold; color:#FFFFFF; background-color:#FF0000; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;"></div>
                        <tr class="even">
                          <td align="left">Doctor Name <span class="strick">*</span> :</td>
                          <td align="left">
						  	<select name="doctor_id" id="doctorCode" required aria-required="true" autofocus>
                              <option value="" selected="selected">Please select</option>
                              <?php foreach($this->doctors as $doctor) { //$doctor['svl_number'].' - '.?>
                              <option value="<?=$doctor['doctor_id']?>"><?=$doctor['doctor_name']?></option>
                              <?php } ?>
                            </select>
                          </td>
                          <td align="left" colspan="4">Address <span class="strick">*</span> :</td>
                        </tr>

                        <tr class="odd">
                          <td align="left">Qualification <span class="strick">*</span> :</td>
                          <td align="left" id="qualification"></td>
                          <td align="left" rowspan="3" valign="top" colspan="4" id="address"></td>
                        </tr>

                        <tr class="even">
                          <td align="left">Speciality <span class="strick">*</span> :</td>
                          <td align="left" id="speciality"></td>
                        </tr>

                        <tr class="odd">
                          <td align="left">SVL No /Code No <span class="strick">*</span> :</td>
                          <td align="left" id="svl"></td>
                        </tr>

                        <tr class="even">
                          <td align="left">Tel No (with STD) <span class="strick">*</span> :</td>
                          <td align="left" id="phone"></td>
                          <td align="left">Mobile No <span class="strick">*</span> :</td>
                          <td align="left" id="mobile"></td>
                          <td align="left" colspan="2"></td>
                        </tr>

                        <tr class="odd">
                          <td width="15%" align="left">BE <span class="strick">*</span> :</td>
                          <td width="15%" align="left" id="be"></td>
                          <td width="15%" align="left">HQ <span class="strick">*</span> :</td>
                          <td width="15%" align="left" id="behq"></td>
                          <td width="15%" align="left">Employee code <span class="strick">*</span> :</td>
                          <td width="25%" align="left" id="becode"></td>
                        </tr>

                        <tr class="even">
                          <td align="left">ABM <span class="strick">*</span> :</td>
                          <td align="left" id="abm"></td>
                          <td align="left">HQ <span class="strick">*</span> :</td>
                          <td align="left" id="abmhq"></td>
                          <td align="left">Employee code <span class="strick">*</span> :</td>
                          <td align="left" id="abmcode"></td>
                        </tr>

                        <tr class="odd">
                          <td align="left">RBM <span class="strick">*</span> :</td>
                          <td align="left" id="rbm"></td>
                          <td align="left">HQ <span class="strick">*</span> :</td>
                          <td align="left" id="rbmhq"></td>
                          <td align="left">Employee code <span class="strick">*</span> :</td>
                          <td align="left" id="rbmcode"></td>
                        </tr>
						
						<tr class="even">
                          <td align="left">ZBM <span class="strick">*</span> :</td>
                          <td align="left" id="zbm"></td>
                          <td align="left">HQ <span class="strick">*</span> :</td>
                          <td align="left" id="zbmhq"></td>
                          <td align="left">Employee code <span class="strick">*</span> :</td>
                          <td align="left" id="zbmcode"></td>
                        </tr>

                        <tr class="odd">
                          <td align="left">Nature of Request <span class="strick">*</span> :</td>
                          <td align="left" valign="top">
                          	<select name="expense_type" id="expense_type" required aria-required="true" autofocus>
                              <option value="" selected="selected">Please select</option>
                              <?php foreach($this->expenses as $expense) { ?>
                              <option value="<?=$expense['expense_type']?>"><?=$expense['type_name']?></option>
                              <?php } ?>
                            </select>
                          </td>
                          <td align="left">COST of Activity <span class="strick">*</span> :</td>
                          <td align="left"><input type="text" name="expense_cost" required pattern="[1-9][0-9]{0,5}" title="Only Numer Greater 0!!" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" aria-required="true" class="input-medium" /></td>
                        </tr>

                        <tr class="even">
                          <td align="left">Detail of Activity Planned <span class="strick">*</span> :</td>
                          <td align="left" valign="top" colspan="5"><textarea rows="5" cols="80" name="expense_note" required title= "5 to 10 characters"></textarea></td>
                        </tr>
                      </table>
					  </td>
                    </tr>

					<tr>
					  <td align="left" style="border:none">
					    <table>
						<tr class="tr_bgcolor" style="width:20%">
						<td valign="top" style="width:40%; height:50%;">
						<select name="prdlist" id="boxa" multiple="multiple" style="width:100%; height:150px;">
						<!--<option value="">-- Select Product --</option>  -->  
						<?php foreach($this->products as $i=>$product){?>
						<option value="<?=$product['product_id']; ?>"><?=$product['product_name']; ?>
						</option>
						<?php } ?>
						</select>
						</td> 
						<td style="width:5%; vertical-align:middle;" align="center"> 
							   <input type="button" name="right" class="inputbutton" value=">>>"  onclick="$.addvalue();" ><br /><br />
							   <input type="button" name="left" class="inputbutton" value="<<<"  onclick="$.removevalue();" >
						</td>
						<td style="width:40%; vertical-align:middle; padding-left:30px;"> 
							<select name="productlists[]"  id="boxb" multiple="multiple" style="width:100%; height:150px;" required>
							<?php /*for($i=0;$i<count($countryedit); $i++){ ?>  
								<option  selected="selected" value="<?php echo $countryedit[$i]['country_id']; ?>"><?php  echo  $countryedit[$i]['CName']; ?></option>
							<?php }*/ ?>
							</select>
						  </td>
						  <td style="width:15%;">
						  	<input type="button" name="productaction" id="productaction" value="Show Product Below" onclick="$.listproduct();" />
							<span id="prdError" style="color:#FF0000; font-weight:bold;"></span>
						  </td>
						</tr>
						</table>
					  </td>
					</tr>
					
					<tr>
                      <td id="drpotential" width="100%" valign="top" align="left"></td>
                  </tr>
                   
                    <tr>
                        <td width="100%" align="left"><b>In Case of DD/ Cheque  give following detail:</b></td>
					</tr>
                    
                    <tr>
                    <td width="100%" align="left" valign="top">
                      <table width="100%" cellpadding="2" cellspacing="2" border="0">
                        <tr class="odd">
                          <td width="40%" align="left">DD/Cheque/to be made in favour of (As appeared in Bank Account)</td>
                          <td width="30%" align="left" valign="top"><input type="text" name="favour" id="favour" class="input-medium"/></td>
                          <td width="10%" align="left">Payable at</td>
                          <td width="20%" align="left" valign="top"><input type="text" name="payble" id="payble" class="input-medium"/></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
					
					<tr class="event" id="buttonRow">
					  <td style="border:none"><input type="submit" name="appointmentADD" value="Add CRM"></td>
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
$(document).ready(function() {	
	var windowWidth = $(window).width();
	var setDivWidth = (windowWidth - 55);	
	$("#productPotentialDiv").attr('style','width:'+setDivWidth+'px; overflow: auto; height: auto;');
	
	$.addvalue = function() {
		!$('#boxa option:selected').remove().appendTo('#boxb');
		$.listproduct();
	}
	
	$.removevalue = function() {
		!$('#boxb option:selected').remove().appendTo('#boxa');
		$.listproduct();
	}
	
	$.listproduct = function() {
		var prdValues = $('#boxb').val();
		var prdNames  = $('#boxb').text(); //alert(prdValues);return false;
		if(prdValues.length >=3 && prdValues.length <=7) {
			$('#boxa').css('border-color','#000000');
			$('#boxb').css('border-color','#000000');
			$('#prdError').html(''); //alert(prdValues.length);return false;
			$.ajax({
				type: "post",
				url: "<?=Bootstrap::$baseUrl;?>/Appointment/drpotential?",
				data: "token="+prdValues,
				
				beforeSend : function() {
					$('#drpotential').html("Please wait...");
				},
				success: function(msg){ //alert(msg); return false;
					$('#drpotential').html(msg);
				}
			});
		}
		else {
			$('#drpotential').html('');
			$('#boxa').css('border-color','#FF0000');
			$('#boxb').css('border-color','#FF0000');
			$('#prdError').html('Product selection should be min 3 and max 7 !!');			
		}
	}
	
	$('#doctorCode').change(function() {
		var doctorID = this.value; //alert(this.value);return false;
		if(doctorID > 0) {
			$.ajax({
				url: '<?=Bootstrap::$baseUrl?>/Ajax/getdoctor',
				data: 'token='+doctorID,
				success: function(response) {//alert(response);return false;
					if($.trim(response) != 0) {//alert(response);return false;
						var details = response.split('^'); //alert(details[0]);return false;
						// Check Down Level User
						var designation = '<?=$_SESSION['AdminDesignation']?>';
						if(designation<8) {
							$.checkDownLevel(details);
						}
						
						$("#svl").html(details[0]);
						$("#speciality").html(details[1]);
						$("#qualification").html(details[2]);
						$("#address").html(details[3]+'\n'+details[4]+'\n'+details[5]);
						$("#phone").html(details[6]);
						$("#mobile").html(details[7]);
						$("#be").html(details[8]);
						$("#behq").html(details[9]);
						$("#becode").html(details[10]);
						$("#abm").html(details[11]);
						$("#abmhq").html(details[12]);
						$("#abmcode").html(details[13]);
						$("#rbm").html(details[14]);
						$("#rbmhq").html(details[15]);
						$("#rbmcode").html(details[16]);
						$("#zbm").html(details[17]);
						$("#zbmhq").html(details[18]);
						$("#zbmcode").html(details[19]);
						$("#chcode1").val(details[20]);
						$("#chname1").val(details[21]);
						$("#chphone1").val(details[22]);
						$("#chcode2").val(details[23]);
						$("#chname2").val(details[24]);
						$("#chphone2").val(details[25]);
						$('#buttonRow').show();
					}
					else {//alert(response);return false;
						$("#svl").html('');
						$("#speciality").html('');
						$("#qualification").html('');
						$("#address").html('');
						$("#phone").html('');
						$("#mobile").html('');
						$("#be").html('');
						$("#behq").html('');
						$("#becode").html('');
						$("#abm").html('');
						$("#abmhq").html('');
						$("#abmcode").html('');
						$("#rbm").html('');
						$("#rbmhq").html('');
						$("#rbmcode").html('');
						$("#zbm").html('');
						$("#zbmhq").html('');
						$("#zbmcode").html('');
						$("#chcode1").val('');
						$("#chname1").val('');
						$("#chphone1").val('');
						$("#chcode2").val('');
						$("#chname2").val('');
						$("#chphone2").val('');
					}
				}
			});
		}
		else {
			$("#svl").html('');
			$("#speciality").html('');
			$("#qualification").html('');
			$("#address").html('');
			$("#phone").html('');
			$("#mobile").html('');
			$("#be").html('');
			$("#behq").html('');
			$("#becode").html('');
			$("#abm").html('');
			$("#abmhq").html('');
			$("#abmcode").html('');
			$("#rbm").html('');
			$("#rbmhq").html('');
			$("#rbmcode").html('');
			$("#zbm").html('');
			$("#zbmhq").html('');
			$("#zbmcode").html('');
			$("#chcode1").val('');
			$("#chname1").val('');
			$("#chphone1").val('');
			$("#chcode2").val('');
			$("#chname2").val('');
			$("#chphone2").val('');
		}
	});
	
	$.checkDownLevel = function(details) {
		var designation = '<?=$_SESSION['AdminDesignation']?>'; //alert(designation);return false;
		if(designation==7 && $.trim(details[8])!='') {
			var url = '';
			$.errorNotification(url);
		}
		else if(designation==6 && ($.trim(details[8])!='' || $.trim(details[11])!='')) {
			var url = '';
			$.errorNotification(url);
		}
		else if(designation==5 && ($.trim(details[8])!='' || $.trim(details[11])!='' || $.trim(details[14])!='')) {
			var url = '';
			$.errorNotification(url);
		}
		else if(designation<=4 && ($.trim(details[8])!='' || $.trim(details[11])!='' || $.trim(details[14])!='' || $.trim(details[17])!='')) {
			var url = '';
			$.errorNotification(url);
		}
	}
	
	$.errorNotification = function(url){
 		$('#errorNotify').show();
		$('#errorNotify').html('You have down level user which can add crm!!');
		$('#buttonRow').hide();
		$('#errorTable').css('background-color','#F00');exit;
		/*$.fancybox({
			"width": "40%",
			"height": "40%",
			"autoScale": true,
			"transitionIn": "fade",
			"transitionOut": "fade",
			"type": "iframe",
			"href": url
		});*/ 
	}
	
	$.getValue = function(iden,monthyear,prPrice) {
		var unitid    = $('#unit_'+monthyear+'_'+iden).val();
		var unitValue = (parseFloat(unitid)>0) ? unitid : 0;
		var calculateValue = (prPrice*unitValue);
		$('#value_'+monthyear+'_'+iden).val(calculateValue.toFixed(2));
		$.calculateSumValue(monthyear);
	}
	
	$.calculateSumValue = function(monthyear) {
		var sum = 0;
         //iterate through each product textboxes and add the values
         $("input[name*='value_"+monthyear+"']").each(function () {
             //add only if the value is number
             if (!isNaN(this.value) && this.value.length != 0) {
                 sum += parseFloat(this.value);
             }
         });
         $('#tot_val_'+monthyear).val(sum.toFixed(2));
		 $.calculateTotal('1','4','tot_val_last');
		 $.calculateTotal('4','10','tot_val_expect');
		 $.calculateTotal('1','10','tot_val_crm');
	}
	
	$.calculateTotal = function(start,end,totField) {
		var sumTotal = 0;
         for(var i=start;i<end;i++) {
             var monthVal = $('#month_'+i).val();
			 var totMonth = $('#tot_val_'+monthVal).val();
			 if (!isNaN(totMonth) && totMonth.length != 0) {
                 sumTotal += parseFloat(totMonth);
             }
         }
         $('#'+totField).val(sumTotal.toFixed(2));
	}
});
</script>