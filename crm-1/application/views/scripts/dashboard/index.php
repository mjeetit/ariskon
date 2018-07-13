	
	   <!-- Account overview -->
            <div class="grid_4">
                <div class="module">
                        <h2><span>Account overview</span></h2>
                        
                        <div class="module-body">
                        
                        	<p>
                                <strong>User: </strong><?php echo ucfirst($_SESSION['AdminName']);?><br />
                                <strong>Your last visit was on: </strong><?php echo ucfirst($_SESSION['LastLogin']);?><br />
                                <strong>From IP: </strong><?php echo ucfirst($_SESSION['LastLoginIP']);?>
                            </p>
                        	<p>
                                <a href="#">click here</a><br />
                            </p>

                        </div>
                </div>
                <div style="clear:both;"></div>
            </div> <!-- End .grid_5 -->
			   <!-- Categories list -->
		     
            <?php /*?><!-- Event List -->
            <div class="grid_6">
            
                <div class="module">
                     <h2><span>Last 5 Events</span></h2>
                        
                     <div class="module-body">
                      <table width="100%">
                    <thead>
					<?php 
					if(!empty($this->events)){
					 foreach($this->events as $key=>$events){?>
                        <tr>
                            <td> <?php echo ($key+1).' .'.strip_tags(substr($events['description'],0,100));?></td>
                        
                        </tr>
				    <?php } }else{?>
					   <tr>
                            <td>There is No events</td>
                        
                        </tr>  
					<?php } ?>
                    </thead>
                </table>
                     </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>
            
            </div> <!-- End .grid_6 -->
		<!--Notification -->	
				<div class="grid_6">
            
                <div class="module">
                     <h2><span>Last 5 Notification</span></h2>
                        
                     <div class="module-body">
                       <table width="100%">
                    <thead>
					<?php 
					if(!empty($this->notification)){
					 foreach($this->notification as $key=>$notification){?>
                        <tr>
                            <td> <?php echo ($key+1).' .'.strip_tags(substr($notification['description'],0,100));?></td>
                        
                        </tr>
				    <?php } }else{?>
					   <tr>
                            <td>You Have No Notification</td>
                        
                        </tr>  
					<?php } ?>
                    </thead>
                </table>
                     </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>
            
            </div> <!-- End .grid_6 -->
		 <!--Request Notification-->	
	
		
		 <!-- End .grid_6 -->	
	  <?php if($_SESSION['AdminLoginID']==1){?>	   
            <div class="grid_6">
                <div class="module">
                     <h2><span>Last 5 Users</span></h2>
                     <div class="module-body">
                      <table width="100%">
                    <thead>
					<tr>
					<td align="center"><b>User Name</b></td>
					<td align="center"><b>Department</b></td>
					<td align="center"><b>Designation</b></td>
					<td align="center"><b>Employee Code</b></td>
					<td align="center"><b>Action</b></td>
					</tr>
					<?php
					if(!empty($this->Users)){
					foreach($this->Users as $User){?>
                        <tr>
						<td align="center"><?php echo $User['name'];?></td>
						<td align="center"><?php echo $User['department_name'];?></td>
						<td align="center"><?php echo $User['designation_name'];?></td>
						<td align="center"><?php echo $User['employee_code'];?></td>
						<td align="center"><a onclick="fancyboxopenfor('<?php echo $this->url(array('controller'=>'Users','action'=>'view','user_id'=>$Users['user_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a>
						</td>
                        
                        </tr>
				    <?php } }else{?>
					<tr>
					<td colspan="6" align="center">No Record Found!..........</td>
					</tr>
					<?php }?>		
                    </thead>
                </table>
                     </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>
			</div> <!-- End .grid_6 -->
        <?php } ?>			
		<!--Salary Slip-->	
	   <?php if($_SESSION['AdminLoginID']!=1){?>
			<div class="grid_6">
                <div class="module">
                     <h2><span>Last 5 Salary Slip</span></h2>
                        
                     <div class="module-body">
                      <table width="100%">
                    <thead>
					<tr>
					<td align="center"><b>Release Date</b></td>
					<td align="center"><b>Paid Days</b></td>
					<td align="center"><b>Earning Amount</b></td>
					<td align="center"><b>Deduction</b></td>
					<td align="center"><b>Net Amount</b></td>
					<td align="center"><b>Action</b></td>
					</tr>
					<?php
					if(!empty($this->salaryslip)){
					foreach($this->salaryslip as $slip){?>
                        <tr>
						<td align="center"><?php echo $slip['release_date'];?></td>
						<td align="center"><?php echo $slip['paid_days'];?></td>
						<td align="center"><?php echo $slip['earning_amount'];?></td>
						<td align="center"><?php echo $slip['deduction_amount'];?></td>
						<td align="center"><?php echo $slip['net_amount'];?></td>
						<td align="center"><a href="<?php echo Bootstrap::$baseUrl.'public/salaryslip/'.$slip['salary_slip_file'] ?>" target="_blank"><img src="<?php print Bootstrap::$baseUrl;?>public/admin_images/print.png" align="absmiddle" alt="Print Invoice" title="Print Invoice" border="0" class="changeStatus" /></a>
						</td>
                        
                        </tr>
				    <?php } }else{?>
					<tr>
					<td colspan="6" align="center">No Record Found!..........</td>
					</tr>
					<?php }?>		
                    </thead>
                </table>
                     </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>
            
            </div> <!-- End .grid_6 -->
		 <?php }?>
		 
		<div class="grid_6">
            
                <div class="module">
                     <h2><span>Last 5 Request</span></h2>
                        
                     <div class="module-body">
                       <table width="100%">
                    <thead>
					<?php 
					if(!empty($this->requests)){ ?>
					   <th align="center"><b>User Name</b></th>
					   <th align="center"><b>Department</b></th>
					   <th align="center"><b>Designation</b></th>
					   <th align="center"><b>Leave From</b></th>
					   <th align="center"><b>leave To</b></th>
					   <th align="center"><b>Action</b></th>
					<?php foreach($this->requests as $key=>$request){?>
                        <tr>
                            <td> <?php echo $request['first_name'].' '.$request['last_name'];?></td>
							 <td> <?php echo $request['department_name'];?></td>
							 <td> <?php echo $request['designation_name'];?></td>
							 <td> <?php echo $request['leave_from'];?></td>
							 <td> <?php echo $request['leave_to'];?></td>
							 <td> <a href="<?php echo $this->url(array('controller'=>'Requestmanager','action'=>'approve','UserID'=>$request['user_id'],'request_id'=>$request['request_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/arrow.gif" /></a></td>
                        
                        </tr>
				    <?php } }else{?>
					   <tr>
                            <td>You Have No Notification</td>
                        
                        </tr>  
					<?php } ?>
                    </thead>
                </table>
                     </div>
                </div> <!-- module -->
                <div style="clear:both;"></div>
            
            </div><?php */?>
		
<script type="text/javascript">
function fancyboxopenfor(url){
 $.fancybox({
        "width": "70%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
</script>	