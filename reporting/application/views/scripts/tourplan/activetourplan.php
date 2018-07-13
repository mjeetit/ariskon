<div>
  <div id="subnav">
    <div id="subnav1">
      <div class="blue">
        <div id="slatenav">
          <ul>
            <li><a href="<?php echo $this->url(array('controller' => 'Dashboard'),'default',true)?>" title="Home">Home</a></li>
            <?php
     for($i=0;$i<count($this->helpdesk);$i++) {
	 $Selected = CommonFunction::selected($this->helpdesk[$i]['module_action']);
	 ?>
            <li> <a  <?php if($Selected){ echo  'class="current"'; } ?> href="<?php echo $this->url(array('controller' => $this->helpdesk[$i]['module_controller'],'action'=>$this->helpdesk[$i]['module_action']),'default',true)?>"><?php print Bootstrap::$tr->translate($this->helpdesk[$i]['module_name'], 'nl');?></a> </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <div id="linkpath">
    <div class="crumb_navigation">
      <div class="short_nav">Navigation: <span class="current"><a href="<?php echo $this->url(array('controller' => 'Dashboard'),'default',true)?>" title="Home">Home</a></span>&nbsp;&raquo;&nbsp;<span class="current"><a href="<?php echo $this->url(array('controller' => 'Helpdesk','action'=>'index'),'default',true)?>" title="Help Desk">Help Desk</a></span></div>
    </div>
  </div>
  <div class="clear"></div>
  <?php if(isset($_SESSION[SUCCESS_MSG])){ ?>
  <div class="message_success"> <img alt="success"  src="<?php print IMAGE_LINK;?>/icon_success.png" style="margin-right:10px; margin-bottom:0px;" align="left" /><?php echo Bootstrap::showMessage(); ?></div>
  <?php } if(isset($_SESSION[ERROR_MSG])){ ?>
  <div class="message_error"> <img alt="error"  src="<?php print IMAGE_LINK;?>/icon_error.gif" style="margin-right:10px; margin-bottom:0px;" align="left" /><?php echo Bootstrap::showMessage(); ?></div>
  <?php }?>
<div id="centerpage">
  <div class="centerpage">
    <div class="module" align="left">
          <h2><span><?php echo Bootstrap::$tr->translate('Reply Message Details: Ticket No:'.$this->ticketdetail['ticket_no'].'', 'nl');?> </span></h2>
      <div class="module-table-body">
	    <?php $this->objModel->UpdateTicketReadStatus($this->ticketdetail['helpdesk_token']);?>
	  <?php $this->objModel->updateReadstatus($this->ticketdetail);?>
        <table width="60%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="70%" align="center">
              <form  method="post" action=""id="replyform" name="replyform" enctype="multipart/form-data">
                    <input type="hidden" name="question_id" value="<?php echo $this->ticketdetail['question_id'];?>">
                  <input type="hidden" name="parent_id" value="<?php echo $this->ticketdetail['helpdesk_token'];?>">
                  <input type="hidden" name="barcode_id" value="<?php echo $this->ticketdetail['barcode_id'];?>">
                  <input type="hidden" name="user_id" value="<?php echo $this->ticketdetail['user_id'];?>">
                
	           <table  style="width:80%;" border="0" cellspacing="0" cellpadding="0">
                <tr>
			     <td>
			      <table  style="width:100%;" border="0" cellspacing="0" cellpadding="0">
                               <!-- Shipment Detail--> 
								 <tr class="odd">
                                    <td width="15%" class="bold_text">Shipment Details </td>
                                    <td colspan="5"><?php 
                                         echo '<b>Sender&nbsp;:</b>&nbsp;'.$this->parceldetail['company_name']."<br>";
                                         echo '<b>Barcode No:</b>';?>
             							 <a href="javascript:void();" onclick="javascript:window.open('<?php echo $this->url(array('controller' => 'Parceltracking','action'=>'tracking','barcode_id'=>Class_Encryption::encode($this->parceldetail[BARCODE_ID])),'default',true)?>','mywindow','status=0,resizable=1,width=1000,height=700,toolbar=0')"><?php echo $this->parceldetail[SHIPMENT_BARCODE]?></a><br>
              <?php
                                         echo '<b>Receiver&nbsp;:</b>&nbsp;'.$this->parceldetail['rec_name']."<br>";
                                         echo '<b>Forwarder&nbsp;:</b>&nbsp;'.$this->parceldetail['forwarder_name']."<br>";
                                         echo '<b>Weight&nbsp;:</b>&nbsp;'.$this->parceldetail['weight']."<br>";
                                         echo '<b>Create Date&nbsp;:</b>&nbsp;'.$this->parceldetail['create_date']."<br>";
                                         echo '<b>Checkin Date&nbsp;:</b>&nbsp;'.$this->parceldetail['checkin_date']."<br>";
                                   ?></td>
                                </tr>
                               <!--Ticket Detail -->   
								 <tr>
                                     <td class="bold_text">Ticket Information  </td>
                                     <td colspan="5"><?php  echo   '<b>Question: </b>' .$this->ticketdetail['question_type'].'<br>'; echo '<b>Description:</b>'. strip_tags($this->ticketdetail['messages']).''; ?>
                                     </td>
                                 </tr>
					 <?php if(in_array($_SESSION['adminLoginID'],explode(',',$this->ticketdetail['forward_to']))){ ?>	
					    <tr>		 
                               <td colspan="6" >
							   <?php Bootstrap::FckIncludePath();
								$var = new FCKeditor('description');
								$var->BasePath = Bootstrap::$fckpath;
								$var->Height = '300px';
								$var->Weight = '300px';
								$var->Create();
								?>
                                </td></tr>
                                <tr ><td align="center" colspan="6">
                                        <input type="submit" name="internalreply" id="internalreply" class="inputbutton" value="Reply" />&nbsp;&nbsp;
								 </td>
								</tr>
							   
						<?php }else{ ?>	   
							     <tr>
                                     <th>Logo</th>
                                     <th>Step No.</th>
                                     <th>Step Name</th>
                                     <th>Step Status</th>
                                     <th>Reply Remarks</th>
                                 </tr>

                            <?php
                               foreach($this->previussteps as $row=>$reply){
                                   $allstep[] =$reply['step'];
                                   $class = ($row%2==0)?'class="odd"':'';
                                   ?>
                                 <tr <?php echo $class;?>>
                                     <td class="bold_text"><img src="<?php echo ADMIN_LOGO_LINK.'/'. $reply['logo'];?>" width="60px" height="40px" align="left" /></td>
                                     <td class="bold_text"><?php echo $reply['steps'];?></td>
                                     <td><?php echo $reply['step_name'];?></td>
                                    
                                      <td>
                                         <?php echo ($reply['current_step_status']=='1')?'Yes':'NO';?>
                                     </td>
                                     <td><?php echo strip_tags($reply['messages']);
                                    if(!empty($reply['uploded_file'])){ ?>
                                          <a href="<?php echo HELP_DESK.'/'.$reply['uploded_file'] ?>" target="_blank"><img src="<?php print ADMIN_IMAGE_LINK; ?>/fileicon.png" align="absmiddle" alt="Print Attachment" title="<?php echo $reply['uploded_file'] ?>" border="0" class="changeStatus" /></a>
                                         <?php } ?>
                                     </td>
                                 </tr>
                            <?php
                              $laststep = $reply['step'];
                              $stepstatus = $reply['step_status'];
                              }
                              $laststep = ($laststep>0)?$laststep:'0';
                              ?>
                                <?php if((($_SESSION['adminLevelID']==1 || $_SESSION['adminLevelID']==4 || $_SESSION['adminLevelID']==6) && $this->nextsteps['step_auth']=='0') || ($_SESSION['adminLevelID']==5 && $this->nextsteps['step_auth']=='1') && !empty($this->nextsteps)){?>
                                 <tr>
                                     <td class="bold_text"><img src="<?php echo ADMIN_LOGO_LINK.'/'. $_SESSION['administratorLogos'];?>" width="85px" height="50px" align="left" /></td>
                                     <td class="bold_text"><?php echo $this->nextsteps['steps'];?><input type="hidden" name="step" value="<?php echo $this->nextsteps['step'];?>"></td>
                                     <td><?php echo $this->nextsteps['step_name'];?></td>
                                     <td> 
                                         <input type="radio" name="step_status" value="1" checked="checked">Yes&nbsp;
                                         <input type="radio" name="step_status" value="0">No
                                     </td>
                                      <td><?php if($this->nextsteps['documents_uploade']==1){?>
                                          <input type="file" name="file_name">
                                            <input type="hidden" name="documents_uploade" value="<?php echo $this->nextsteps['documents_uploade'];?>">
                                          <?php } ?>
                                      </td>
                                 </tr>
							 <?php if($_SESSION['adminLevelID']==1 || $_SESSION['adminLevelID']==4 || $_SESSION['adminLevelID']==6) { ?>	 
								<tr>
								<td class="bold_text" colspan="2">Email to Forwarder</td>
								<td><input type="radio" value="0" name="forwarder_notify" checked="checked" onclick="showforwarderemail(this.value);" />&nbsp;No
								&nbsp;<input type="radio" value="1" name="forwarder_notify"  onclick="showforwarderemail(this.value);"/>&nbsp;Yes</td>
								<td colspan="2"><input type="text"  name="forwarder_email" id="forwarder_email" placeholder="Enter Forwarder Email" style="display:none" /></td>
								</tr> 
							<?php }?>	
                                 <tr class="tr_bgcolor">
							<td colspan="6" >
						   <?php Bootstrap::FckIncludePath();
							$var = new FCKeditor('description');
							$var->BasePath = Bootstrap::$fckpath;
							$var->Height = '300px';
							$var->Weight = '300px';
							$var->Create();
							?>
                                </td></tr>
                                <tr ><td align="center" colspan="6">
                                        <input type="submit" name="reply" id="reply" class="inputbutton" value="Reply" />&nbsp;&nbsp;
							<?php if($_SESSION['adminLoginID']!=$this->ticketdetail['forward_to'] && $_SESSION['adminLevelID']!=5){?>
							<select name="status" id="status">
                    			 <?php foreach($this->helpdeskStatuses as $helpdeskStatus) { ?>
				 					<option value="<?=$helpdeskStatus['helpdesk_status_id']?>" <?php if($helpdeskStatus['helpdesk_status_id']==$this->ticketdetail['is_status']){ echo 'selected="selected"'; } ?>><?=$helpdeskStatus['helpdesk_status_name']?></option>
				 <?php } ?>
                           </select>
			   <?php }else{ ?>
			   <input type="hidden" name="status" value="<?php echo $this->ticketdetail['is_status'];?>" />
			   <?php } ?>			
			     </td>
				</tr>
                              <?php }  ?>
							  <?php if(empty($this->previussteps) && empty($this->nextsteps)){?>
							    <tr><td colspan="5" align="center">Ticket is Underprocess</td></tr>
							  <?php } }?> 
                              </table>
                           </td>
                         </tr>
                   </table>
	    </form>
            </td>
            <td width="30%" align="center">
                <table  style="width:100%;" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <th colspan="4">Instructions</th>
                    </tr>
                  <?php 
				        $this->objModel->GData['question_id'] = $this->ticketdetail['question_id'];
				        $instructions = $this->objModel->showsteps();
                        foreach ($instructions as $row=>$instruction){
                            if(@in_array($instruction['step'],$allstep)){
                                $class = 'class="freight"';
                            }else{
                            $class = ($row%2==0)?'class="odd"':''; 
                            }
                            ?>
                            <tr <?php echo $class;?>>
                                <td class="bold_text"><?php echo $instruction['steps']?></td>
                                <td><?php echo $instruction['step_name']?></td>
                                <td><?php echo $instruction['instruction']?></td>
                                <td><?php echo ($instruction['step_auth']=='0')?'Operator':'Customer';?></td>
                            </tr>
                    <?php } ?>
                </table>
            </td>
          </tr>
            </table>


      </div>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">

function setStatus(){
	     var status = $("#status").val();
           //  alert(status);
	     $.ajax({
               Type:"POST",
               url : "<?php echo $this->url(array('controller'=>'Helpdesk','action'=>'helpdeskstatus')); ?>" ,
	       data: "status="+status,
               success : function(msg){ //alert(msg);\
                    if(status !=0){
			      msg = "Status has been changed successfully  !!";
		             popup_msg(msg);
		              window.location.reload();
                    }
               }
           });
	}

function showforwarderemail(boxvalue){
    if(boxvalue=='1'){
	   $("#forwarder_email").show();
	}else{
	   $("#forwarder_email").hide();
	}
}	
</script>