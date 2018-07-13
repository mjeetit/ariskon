<?php
define ("INCLUDE_PATH", "../");
//require_once INCLUDE_PATH."lib/configures.php";
header('content-type: application/x-javascript;');
$phpPage = reset(explode("?", basename($_SERVER['HTTP_REFERER'])));
//$basename = 'http://www.ariskon.jclifecare.com/crm';
$basename = 'http://localhost/ariskon/crm';
?>
var hostName = '<?php echo $_SERVER['HTTP_HOST']; ?>';

function changePatchCode(head_id,selected)
{
	//alert(head_id);
	$.ajax({
		url:'<?php echo $basename;?>/Ajax/getallpatchlist',
		data:'head_id='+head_id+'&selected='+selected,
		success:function(data){
		$("#patch_id").html(data);
		}
	});
}

function getNextRecord(id,level,target,selected){
$.ajax({
		  url: '<?php echo $basename;?>/Ajax/changestatusrecord',
		  data: 'id='+id+'&level='+level+'&selected='+selected,
		  success: function(data) {
		      //alert(data);return false;
			 $("#"+target).html(data);
		  }
	 });
}

function changeStatusBusiness(bunit_id,selected){ 
$.ajax({
		  url: '<?php echo $basename;?>/Ajax/changestatus',
		  data: 'Mode=Bunit&bunit_id='+bunit_id+'&selected='+selected,
		  success: function(data) {
		      //alert(data);return false;
			 $("#department_id").html(data);
		  }
	 });
}
function changeStatusDepartment(department_id,selected){ 
$.ajax({
		  url: '<?php echo $basename;?>/Ajax/changestatus',
		  data: 'Mode=Department&department_id='+department_id+'&selected='+selected,
		  success: function(data) {
		     //alert(data);return false;
			 $("#designation_id").html(data);
		  }
	 });
}
function changeStatusdesignation(designation_id,selected){
    var department_id = $("#department_id").val();
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/changestatus',
		  data: 'Mode=Designation&designation_id='+designation_id+'&department_id='+department_id+'&selected='+selected,
		  success: function(data) {
		    // alert(data);return false;
			 $("#parent_id").html(data);
			 $("#report_to").html(data);
		  }
	 });
}
function salarytemplate(user_id){
 var bunit_id = $("#bunit_id").val();
 var department_id = $("#department_id").val();
 var designation_id = $("#designation_id").val();
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/salarytemplate',
		  data: 'Mode=Template&bunit_id='+bunit_id+'&department_id='+department_id+'&designation_id='+designation_id+'&user_id='+user_id,
		  success: function(data) {
		     //alert(data);return false;
			 // $("#afterid").after('');
			  $("#template").html(data);
		  }
	 });
}
function editsalarytemplate(user_id,bunit_id,department_id,designation_id){
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/salarytemplate',
		  data: 'Mode=Template&bunit_id='+bunit_id+'&department_id='+department_id+'&designation_id='+designation_id+'&user_id='+user_id,
		  success: function(data) {
		     //alert(data);return false;
			 // $("#afterid").after('');
			  $("#template").html(data);
		  }
	 });
}
function leavedetail(user_id){
  var department_id = $("#department_id").val();
  var designation_id = $("#designation_id").val();
  var parent_id = $("#parent_id").val();
  var user_type = $("#user_type").val();
  var emp_type = $("#emp_type").val();
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/leavedetail',
		  data: 'Mode=Leave&department_id='+department_id+'&designation_id='+designation_id+'&user_id='+user_id+'&parent_id='+parent_id+'&user_type='+user_type+'&emp_type='+emp_type,
		  success: function(data) {
		     //alert(data);return false;
			 // $("#afterid").after('');
			  $("#leavedetail").html(data);
		  }
	 });
}
function Editleavedetail(user_id,designation_id,parent_id,emp_type){
  var department_id = $("#department_id").val();
  var user_type = $("#user_type").val();
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/leavedetail',
		  data: 'Mode=Leave&department_id='+department_id+'&designation_id='+designation_id+'&user_id='+user_id+'&parent_id='+parent_id+'&user_type='+user_type+'&emp_type='+emp_type,
		  success: function(data) {
		     //alert(data);return false;
			 // $("#afterid").after('');
			  $("#leavedetail").html(data);
		  }
	 });
}
function calculate_interest(interest_type){
   var amount = $("#loan_amount").val();
   var roi = $("#loan_interest").val();
   var noi = $("#no_of_emi").val();
   $.ajax({
		  url: '<?php echo $basename;?>/Ajax/calcinteretst',
		  data: 'amount='+amount+'&roi='+roi+'&noi='+noi+'&interest_type='+interest_type,
		  success: function(data) {
		     //alert(data);return false;
			 // $("#afterid").after('');
			  $("#loan_including_tax").val(data);
		  }
	 });
}
function documnet_add(){
    $.ajax({
	    url: '<?php echo $basename;?>/Ajax/documents',
		success: function(data){
		    $("#document").append(data);
		}
	});
}
function Onchageformsubmit(form){
  $("#"+form).submit();
}

function fancyboxopen(url){
$.fancybox({
		'transitionIn'    : 'elastic',
		'transitionOut'   : 'elastic',
		'type'            : 'iframe',
		'width'           : 150,
		'height'          : 120,
		'autoScale'       : true,
		'scrolling'       : 'no',
		'titlePosition'   : 'inside',
		'showCloseButton' : false,
		'padding'         : 0,
		'href'            : url
	}); 
}    

function showform(id){
   var i; 
   if(id==''){
       id=1;
   }
   for(i=1;i<12;i++){
     if(id==i){
	  if(($("#bunit_id").val()>0 && $("#department_id").val()>0 && $("#designation_id").val()>0)){
	   $("#table"+i).show();
	  }else if(id>1 && ($("#bunit_id").val()<=0 || $("#department_id").val()<=0 || $("#designation_id").val()<=0)){
	    alert('Please Fill Required fild of Previous From');return false;
	  }
	  $("#tablevalue").val(id);
	 }else{
	  if(($("#bunit_id").val()>0 && $("#department_id").val()>0 && $("#designation_id").val()>0)){
	    $("#table"+i).hide();
	  }
	 }
      
   }
}
function disabledOnload(){
   for(i=1;i<12;i++){
     if(i==1){
	   $("#table"+i).show();
	 }else{
	    $("#table"+i).hide();
	 }
	}
}

function showprovident(prov){
   if(prov==1){
     $("#prov_id").show();
	 $("#provident_type").show();
   }else{
     $("#prov_id").hide();
	 $("#provident_type").hide();
   }
}
function changeStatus(table,con_id,change_fild,changeMode,confield){
		$('#statusportion'+con_id).html('<img src="<?php echo $basename;?>/public/admin_images/loader.gif"/>');
		$.ajax({
		  url: '<?php echo $basename;?>/Ajax/activeinactive',
		  data: 'table='+table+'&con_id='+con_id+'&change_fild='+change_fild+'&changeMode='+changeMode+'&confield='+confield,
		  success: function(getValue) { //alert(getValue);
			if($.trim(getValue)==0) {
			  $('#statusportion'+con_id).html('<img src="<?php echo $basename;?>/public/admin_images/icon_inactive.gif" onclick="javascript:changeStatus(\''+table+'\',\''+con_id+'\',\''+change_fild+'\',\''+1+'\',\''+confield+'\') "/>');
			}
			else if($.trim(getValue)==1){
			  $('#statusportion'+con_id).html('<img src="<?php echo $basename;?>/public/admin_images/icon_active.gif" onclick="javascript:changeStatus(\''+table+'\',\''+con_id+'\',\''+change_fild+'\',\''+0+'\',\''+confield+'\') "/>');
			}
		  }
		});
}
function changeStatusByportion(table,con_id,change_fild,changeMode,confield,portion){
		$('#'+portion+con_id).html('<img src="<?php echo $basename;?>/public/admin_images/loader.gif"/>');
		$.ajax({
		  url: '<?php echo $basename;?>/Ajax/activeinactive',
		  data: 'table='+table+'&con_id='+con_id+'&change_fild='+change_fild+'&changeMode='+changeMode+'&confield='+confield,
		  success: function(getValue) { //alert(getValue);
			if($.trim(getValue)==0) {
			  $('#'+portion+con_id).html('<img src="<?php echo $basename;?>/public/admin_images/icon_inactive.gif" onclick="javascript:changeStatusByportion(\''+table+'\',\''+con_id+'\',\''+change_fild+'\',\''+1+'\',\''+confield+'\',\''+portion+'\') "/>');
			}
			else if($.trim(getValue)==1){
			  $('#'+portion+con_id).html('<img src="<?php echo $basename;?>/public/admin_images/icon_active.gif" onclick="javascript:changeStatusByportion(\''+table+'\',\''+con_id+'\',\''+change_fild+'\',\''+0+'\',\''+confield+'\',\''+portion+'\') "/>');
			}
		  }
		});
}
function showHide(shID) 
{
	var d = document.getElementById("div" + shID);
	if (document.getElementById("chk"+ shID).checked) 
	{
		d.style.display ="block";
	}
	else 
	{
		d.style.display = "none";
		d.selectedIndex = 0;
	}
}
function getUsersByDesignation(designation_id,selected){
    var department_id = $("#department_id").val();
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/userbydesignation',
		  data: 'department_id='+department_id+'&designation_id='+designation_id+'&selected='+selected,
		  success: function(data) {
		     //alert(data);return false;
			 $("#user_id").html(data);
		  }
	 });
}
function showmonthtextbox(id){
    if(id==0){
	    $("#refun_tr").show();
	}else{
		$("#refun_tr").hide();
	}
}
function fillexpenseamount(visit_id,user_id){
    $.ajax({
		  url: '<?php echo $basename;?>/Ajax/fillexpenseamount',
		  data: 'user_id='+user_id+'&visit_site='+visit_id,
		  success: function(data) {
		   var splitArr = data.split('^');
		      //alert(data);return false;
			 $("#expense_amount").val(splitArr['0']);
			 $("#mobile_bill").val(splitArr['1']);
		  }
	 });
}	
 function extrahead(head_id,type,place){
	 $.ajax({
		  url: '<?php echo $basename;?>/Ajax/extrahead',
		  data: 'salaryhead='+head_id+'&type='+type,
		  success: function(data) {
		      //alert(data);return false;
			 $("#"+place).append(data);
		  }
	 });
}
function providentcalculate(){
    var basicsalary = $("#amount1").val();
	var providentpercent = $("#provident_pecentage").val();
	//providentpercent = replace.
 }	

function expsetting(exp_type,exp_setting_id){
   $.ajax({
		  url: '<?php echo $basename;?>/Ajax/expensehead',
		  data: 'expense_type='+exp_type+'&exp_setting_id='+exp_setting_id,
		  success: function(data) {
		      //alert(data);return false;
			 $("#expense_table").html(data);
		  }
	 });
}

function getexpensetemplate(user_id){
  var bunit_id = $("#bunit_id").val();
  var department_id = $("#department_id").val();
  var designation_id = $("#designation_id").val();
  var parent_id 	= $("#parent_id").val();
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/expensetemplate',
		  data: 'bunit_id='+bunit_id+'&department_id='+department_id+'&designation_id='+designation_id+'&user_id='+user_id+'&parent_id='+parent_id,
		  success: function(data) {
		     //alert(data);return false;
			 // $("#afterid").after('');
			  $("#expensetable").html(data);
		  }
	 });
}
function getexpensetemplateforedit(user_id,parent_id,designation_id,department_id,bunit_id){
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/expensetemplate',
		  data: 'bunit_id='+bunit_id+'&department_id='+department_id+'&designation_id='+designation_id+'&user_id='+user_id+'&parent_id='+parent_id,
		  success: function(data) {
		     //alert(data);return false;
			 // $("#afterid").after('');
			  $("#expensetable").html(data);
		  }
	 });
}
function getExpenseAmount(head_id){
  $("#fare_amount").hide();
   if($.trim(head_id)=='2' || $.trim(head_id)=='3' || $.trim(head_id)=='4' || $.trim(head_id)=='5' || $.trim(head_id)=='9' || $.trim(head_id)=='11'){
       $("#fare_amount").removeAttr('readonly');
       $("#fare_amount").show();
    }
    $.ajax({
		  url: '<?php echo $basename;?>/Ajax/getuserexpense',
		  data: 'head_id='+head_id,
		  success: function(data) {
		     //alert(data);return false;
			  $("#expense_amount").val(data);
		  }
	 });
         $("#fare_amount").val('');
   	 
}
function getViewExpenseAmount(head_id,id){
    $.ajax({
		  url: '<?php echo $basename;?>/Ajax/getuserexpense',
		  data: 'head_id='+head_id,
		  success: function(data) {
		     //alert(data);return false;
			  $("#expense_amount"+id).val(data);
		  }
	 });
}
function getAuthority(currentvalue,designation_id,total,rest){
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/getnextauthority',
		  data: 'currentvalue='+currentvalue+'&designation_id='+designation_id+'&total='+total+'&rest='+rest,
		  success: function(data) {
		     //alert(data);return false;
			  $("#leaveauthority").append(data);
		  }
	 });
}
function getExpenseAuthority(currentvalue,designation_id,total,rest){
  $.ajax({
		  url: '<?php echo $basename;?>/Ajax/nextexpenseauth',
		  data: 'currentvalue='+currentvalue+'&designation_id='+designation_id+'&total='+total+'&rest='+rest,
		  success: function(data) {
		      //alert(data);return false;
			  $("#expenseauthority").append(data);
		  }
	 });
}
function dynamiccalender(id){
    $("#expense_date"+id).datepicker({
			showOn: "button",
			buttonImage: "<?php echo $basename;?>/javascript/datetimepicker/images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
}
function showhideeditexpense(id,mode){
   if(mode==1){
      $('#previousexptr'+id).show();
	  $('#editbtn'+id).hide();
   }
   if(mode==2){
     $('#previousexptr'+id).hide();
	 $('#editbtn'+id).show();
   }
}
function UpdateExpanse(id){
    var expense_id = $('#expense_id'+id).val();
	var head_id = $('#head_id'+id).val();
	var expense_amount = $('#expense_amount'+id).val();
	var expense_detail = $('#expense_detail'+id).val();
	var expense_date = $('#expense_date'+id).val();
	 var lastmonth   = '<?php echo date('Y-m-d',mktime(0, 0, 0, date("m")-1,1,  date("Y")));?>';
	 var currentdate = '<?php echo date('Y-m-d');?>';
 if(expense_date>currentdate){
    $('#dateerror'+id).html("Date Can not more than <br>current date");
	$('#dateerror'+id).show();
	$('#headerror'+id).hide();
 }else if(expense_date<lastmonth){
    $('#dateerror'+id).html("Expense Date should not<br>less than previous month");
	$('#dateerror'+id).show();
	$('#headerror'+id).hide();
 }else if($('#head_id'+id).val()==''){
    $('#headerror'+id).html("Please Select Expense Head");
	$('#headerror'+id).show();
	$('#dateerror'+id).hide();
 }else{
   $('#dateerror'+id).hide();
   $('#headerror'+id).hide();
   $('#previousexptr'+id).hide();
    $.ajax({
		  url: '<?php echo $basename;?>/Ajax/updateexpence',
		  data: 'expense_id='+expense_id+'&head_id='+head_id+'&expense_amount='+expense_amount+'&expense_detail='+expense_detail+'&expense_date='+expense_date,
		  success: function(data) {
		      //alert(data);return false;
			  location.reload(); 
		  }
	 });
 }
}
function datecheck(id){
 var lastmonth   = '<?php echo date('Y-m-d',mktime(0, 0, 0, date("m"),1,  date("Y")));?>';
 var currentdate = '<?php echo date('Y-m-d');?>';
 var expensedate = $('#expense_date'+id).val();
 
 if(expensedate>currentdate){
    $('#dateerror'+id).html("Date Can not more than <br>current date");
	$('#dateerror'+id).show();
	$('#headerror'+id).hide();
 }else if($('#head_id'+id).val()==''){
    $('#headerror'+id).html("Please Select Expense Head");
	$('#headerror'+id).show();
	$('#dateerror'+id).hide();
 }else if(expensedate==''){
    $('#dateerror'+id).html("Date Can Not Empty");
    $('#dateerror'+id).show();
    $('#headerror'+id).hide();
 }else{
   $('#expenseform'+id).submit();
   $('#dateerror'+id).hide();
   $('#headerror'+id).hide();
 }
}
function getLowerDesination(department_id,key){
	$.ajax({
		  url: '<?php echo $basename;?>/Ajax/messagelocation',
		  data: 'Level=1&department_id='+department_id+'&key='+key,
		  success: function(data) {
		    // alert(data);return false;
			 $("#designation_id").html(data);
		  }
	 });
}
			 	
