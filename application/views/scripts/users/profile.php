 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Profile</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
						  
                        <table id="myTable" class="tablesorter">
                        	<thead>
							 
                                <tr>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Name', 'UT.first_name');?></th>
									<th style="width:8%; text-align:center"><?php echo CommonFunction::OrderBy('Employee Code', 'UT.employee_code');?></th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Email', 'UT.personal_email');?></th>
									<th style="width:10%; text-align:center"><?php echo CommonFunction::OrderBy('Contact', 'UT.contact_number');?></th>
									<th style="width:7%; text-align:center"><?php echo CommonFunction::OrderBy('DOJ', 'UT.doj');?></th>
									<th style="width:12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php  //echo "<pre>";print_r($this->UserDetail);die;
							 $users = $this->UserDetail;
							 if($users){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
						<td align="center"><?php echo $this->UserDetail['first_name'].' '.$this->UserDetail['last_name']?></td>
						<td align="center"><?php echo $this->UserDetail['employee_code'];?></td>
						<td align="center"><?php echo $this->UserDetail['personal_email'];?></td>
						<td align="center"><?php echo $this->UserDetail['contact_number'];?></td>
						<td align="center"><?php echo $this->UserDetail['doj'];?></td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Users','action'=>'editprofile'),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit Profile" /></a>&nbsp;|
						</td>
								</tr>
								<?php } else{ ?>
								<tr>
								<td align="center" colspan="6">No Record Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
							<tr>
							<th colspan="13" style="text-align:left"><?php echo CommonFunction::PageCounter($this->Users['Total'], $this->Users['Offset'], $this->Users['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext');?></th>
							</tr>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
                        <?php /*?></div>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
 
        </div>
        <div class="clear"></div>
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

function fancyboxopenforpriv(url){
 $.fancybox({
        "width": "40%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
function fancyboxopenfordelete(url){
 $.fancybox({
        "width": "40%",
        "height": "40%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
</script>	