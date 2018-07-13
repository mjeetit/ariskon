<div class="grid_12">
  <!-- Example table -->
  <div class="module">
    <h2><span>Meetings</span></h2>
    <div class="module-table-body"> 
      <form action="" method="post" enctype="multipart/form-data">
        <table width="96%" border="0" cellspacing="1" cellpadding="2">
          <!--Meeting Location Pull Down List for Filter Option-->
          <tr class="even">
            <td align="left" class="bold_text">Meeting Location : </td>
            <td align="left"><select name="data[headquater_id]" required>
                <option value="">-- Select Meeting Location --</option>
                <?php foreach($this->meetinglocations as $meetinglocation){
                    $select = '';
                    if($this->postData['headquater_id']==$meetinglocation['headquater_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$meetinglocation['headquater_id']?>" <?=$select?>><?=$meetinglocation['headquater_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          
          <!--Meeting Detail-->
          <tr class="odd">
            <td align="left" class="bold_text">Meeting Detail : </td>
            <td align="left"><textarea name="data[metting_detail]" rows="5" class="20" required><?=$this->postData['metting_detail']?></textarea></td>
          </tr>
          
          <!--Meeting Date-->
          <tr class="even">
            <td align="left" class="bold_text">Meeting Date : </td>
            <td align="left">
            	<input type="text" name="data[meeting_date]" id="from_date" placeholder="YYYY-MM-DD" size="15" value="<?=$this->postData['meeting_date']?>" required />
            </td>
          </tr>
          
          <!--Meeting Type Pull Down List for Filter Option-->
          <tr class="odd">
            <td align="left" class="bold_text">Meeting Type : </td>
            <td align="left"><select name="data[meetingtype_id]" required>
                <option value="">-- Select Meeting Type --</option>
                <?php foreach($this->meetingtypes as $meetingtype){
                    $select = '';
                    if($this->postData['meetingtype_id']==$meetingtype['type_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$meetingtype['type_id']?>" <?=$select?>><?=$meetingtype['type_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          
          <!--Meeting Start Time and End Time-->
          <tr class="even">
            <td align="left" class="bold_text">Start Time : </td>
            <td align="left">
            	<input name="data[meetingtime_start]" id="starttime" placeholder="HH:MM" style="width: 70px;" type="text" value="<?=$this->postData['meetingtime_start']?>" required> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                End Time : 
                <input name="data[meetingtime_end]" id="endtime" placeholder="HH:MM" style="width: 70px;" type="text" value="<?=$this->postData['meetingtime_end']?>" required>
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