 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Chemist Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr> 
							<td colspan="12"> <a href="<?php echo $this->url(array('controller'=>'Chemist','action'=>'add'),'default',true)?>" class="button">
                        	<span>Add New Chemist<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add New User" /></span>
                        </a>
					</td>
							</tr>
							
							<!-- START : Search Form -->
							<tr>
							  <td colspan="12">
							  	<form name="listFrom" id="listForm" action="" method="get" >
								<table width="96%" border="0" cellspacing="1" cellpadding="2">
								  <tr height="26">
									<th align="center" colspan="5">Search Form</th>
								  </tr>
								  
								  <tr class="even">
								    <!--Headquarter Pull Down List for Filter Option-->
									<td width="25%" align="left" class="bold_text">
									  <?php if($_SESSION['AdminDesignation']<8) {?>Headquarter :
									  <select name="token2">
										<option value="">-- Select Headquarter --</option>
										<?php foreach($this->headquarters as $headquarter){
											$select = '';
											if($this->Filterdata['token2']==Class_Encryption::encode($headquarter['headquater_id'])){
							   					$select = 'selected="selected"';
											}
										?>
										<option value="<?=Class_Encryption::encode($headquarter['headquater_id'])?>" <?=$select?>><?=$headquarter['headquater_name']?></option>
										<?php } ?>
									  </select>
									  <?php } ?>
									</td>
									
									<!--From Date for Filter Option-->
									<td width="20%" class="bold_text" align="left">
										From : <input type="text" name="from_date" id="from_date" value="<?php echo $this->Filterdata['from_date']?>" />
									</td>
									<td width="20%" class="bold_text" align="left">
										To : <input type="text" name="to_date" id="to_date" value="<?php echo $this->Filterdata['to_date']?>" />
									</td>
									<td width="15%" class="bold_text" align="center"><input type="submit" name="filter" class="inputbutton" value="Search" /></td>
							      </tr>
								  
								  <tr class="odd">
								    <td align="left" colspan="5">
									  <img src="<?=Bootstrap::$baseUrl;?>public/admin_images/arrow_dwn.gif" alt="" align="absmiddle" border="0" />
									  <a href="javascript:void(1);" onclick="javascript: markAllSelectedRows('listForm'); return false;" class="new_link">Check All</a> / 
									  <a href="javascript:void(1);" onclick="javascript: unMarkSelectedRows('listForm'); return false;" class="new_link">Uncheck All</a>
									</td>
								  </tr>
								</table>
								</form>
							  </td>
							</tr>
                            <!-- END : Search Form -->
							
                                <tr>
									<th style="width:3%; text-align:center">#</td>
									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Chemist Name','CT.chemist_name')?></th>
									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Patch','PT.patch_name')?></th>
									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('City','CTy.city_name')?></th>
									<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Headqurter','HQ.headquater_name')?></th>
									<th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('BU','BT.bunit_name')?></th>
									<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Phone/Mobile','CT.phone')?></th>
									<th style="width:7%; text-align:center"><?=CommonFunction::OrderBy('Email','CT.email')?></th>
									<th style="width:5%; text-align:center">Status</th>
									<th style="width:12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php
							  if(count($this->chemists['Records'])>0){
								foreach($this->chemists['Records'] as $key=>$chemist) {
								  $class = ($key%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="chemist_id" value="<?php echo $chemist['chemist_id']?>" /></td>
						<td align="center"><?php echo $chemist['chemist_name'];?></td>
						<td align="center"><?php echo $chemist['patch'];?></td>
						<td align="center"><?php echo $chemist['cityName'];?></td>
						<td align="center"><?php echo $chemist['hqName'];?></td>
						<td align="center"><?php echo $chemist['buName'];?></td>
						<td align="center"><?php echo $chemist['phone'].'/'.$chemist['mobile'];?></td>
						<td align="center"><?php echo $chemist['email'];?></td>
						<td align="center" id="user_portion<?php echo $chemist['chemist_id'];?>">
						<?php if($chemist['isActive']=='1'){?>
						   <img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Inactive" border="0" 
				onclick="changeStatusByportion('<?php echo 'employee_personaldetail';?>','<?php print $chemist['chemist_id']; ?>','user_status','0','chemist_id','user_portion');" title="Active" class="changeStatus" />
						<?php }else{?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
				onclick="changeStatusByportion('<?php echo 'employee_personaldetail';?>','<?php print $chemist['chemist_id']; ?>','user_status','1','chemist_id','user_portion');" title="Inactive"  class="changeStatus" />
						<?php }?>
						</td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Chemist','action'=>'edit','token'=>$chemist['chemist_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit" /></a>
						<!--&nbsp;| <a onclick="fancyboxopenfor('<?php echo $this->url(array('controller'=>'Chemist','action'=>'view','token'=>$chemist['chemist_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a>
					 	&nbsp;| <a  onclick="fancyboxopenfordelete('<?php echo $this->url(array('controller'=>'Chemist','action'=>'delete','token'=>$chemist['chemist_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" /></a>-->
						</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="6">No Chemist Found!...</td>
								</tr>
								<?php }?>
								
								<!-- Paging Style : 1 -->
								<tr>
								<th colspan="10" style="text-align:left"><?=CommonFunction::PageCounter($this->chemists['Total'], $this->chemists['Offset'], $this->chemists['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>
								</tr>
                            </tbody>
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
	$(function() {
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