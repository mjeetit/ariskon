<div class="grid_12">
  <!-- Example table -->
  <div class="module">
    <h2><span>Other Activity</span></h2>
    <div class="module-table-body"> 
      <form action="" id="otheractivityForm" method="post" enctype="multipart/form-data">
        <table width="96%" border="0" cellspacing="1" cellpadding="2">
          <!--Meeting Start Time and End Time-->
          <tr class="even">
            <td align="left" class="bold_text">Start Time : </td>
            <td align="left">
            	<input name="data[start_time]" id="starttime" placeholder="HH:MM" style="width: 70px;" type="text" value="<?=$this->postData['start_time']?>" required>
            </td>
          </tr>
          
          <!--Meeting Start Time and End Time-->
          <tr class="odd">
            <td align="left" class="bold_text">End Time : </td>
            <td align="left"><input name="data[end_time]" id="endtime" placeholder="HH:MM" style="width: 70px;" type="text" value="<?=$this->postData['end_time']?>" required>
            </td>
          </tr>
          
          <!--Activity Detail-->
          <tr class="even">
            <td align="left" class="bold_text">Activity Detail : </td>
            <td align="left"><textarea name="data[activity_detail]" rows="5" class="20" required><?=$this->postData['activity_detail']?></textarea></td>
          </tr>
          
          <!--Activity Date-->
          <tr class="odd">
            <td align="left" class="bold_text">Activity Date : </td>
            <td align="left">
            	<input type="text" name="data[activity_date]" id="from_date" placeholder="YYYY-MM-DD" size="15" value="<?=$this->postData['activity_date']?>" required />
            </td>
          </tr>
          
          <!--Activity Type Pull Down List for Filter Option-->
          <tr class="even">
            <td align="left" class="bold_text">Activity Type : </td>
            <td align="left"><select name="data[meetingtype_id]" required>
                <option value="">-- Select Activity Type --</option>
                <?php foreach($this->meetingtypes as $meetingtype){
                    $select = '';
                    if($this->postData['meetingtype_id']==$meetingtype['activity_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$meetingtype['activity_id']?>" <?=$select?>><?=$meetingtype['activity_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          
          <tr class="even">
            <td width="15%"></td>
            <td width="85%" align="left" class="bold_text"><input type="submit" name="addnewdata" class="inputbutton" value="Submit" /></td>			
          </tr>
        </table>
      </form>
      <div style="clear: both"></div>
    </div> <!-- End .module-table-body -->
  </div><!-- End .module -->
</div> <!-- End .grid_12 -->

<script type="text/javascript">	
	$(document).ready(function() {
		if("<?=$_SESSION['AdminLoginID']?>"=='1') {
			$("input").attr('disabled','disabled');
			$("select").attr('disabled','disabled');
			$("textarea").attr('disabled','disabled');
		}
		
		$('#starttime').timepicker();
		$('#endtime').timepicker();
		$("#from_date" ).datepicker({
			showOn: "button",
			buttonImage: "<?=Bootstrap::$baseUrl;?>public/admin_images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
		$("#to_date" ).datepicker({
			showOn: "button",
			buttonImage: "<?=Bootstrap::$baseUrl;?>public/admin_images/calendar.gif",
			buttonImageOnly: true,
			dateFormat: "yy-mm-dd"
		});
	});	
</script>