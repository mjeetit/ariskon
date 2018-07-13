 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Gift Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr> 
								<td colspan="12"> <a href="<?=$this->url(array('controller'=>'Reporting','action'=>'addgift'),'default',true)?>" class="button">
                        			<span>Add New Gift<img src="<?=IMAGE_LINK?>/plus-small.gif"  width="12" height="9" alt="Add New Location Type" /></span></a>
								</td>
							</tr>
							
                                <tr>
									<th style="width:3%; text-align:center">#</td>
									<th style="width:15%; text-align:center">Gift Name</th>
									<th style="width:15%; text-align:center">Added Quantity</th>
                                    <th style="width:15%; text-align:center">Rest Quantity</th>
                                    <th style="width:15%; text-align:center">Assigned Quantity</th>
									<th style="width:15%; text-align:center">Valid From</th>
									<th style="width:15%; text-align:center">Valid To</th>
                                    <!--<th style="width:8%; text-align:center">Status</th>-->
									<th style="width:7%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php
							 if(count($this->tableDatas)>0){
								foreach($this->tableDatas as $i=>$rowData){
								  $class = ($i%2==0)?'even':'odd';
								  $assigned = ($rowData['quantity']-$rowData['rest_quantity']);
								?>
								<tr class="<?=$class?>">
								<td align="center"><input type="checkbox" name="product_id" value="<?=$rowData['gift_id']?>" /></td>
						<td align="center"><?=$rowData['gift_name']?></td>
						<td align="center"><?=$rowData['quantity']?></td>
                        <td align="center"><?=$rowData['rest_quantity']?></td>
                        <td align="center">
							<?php if($assigned>0) { ?>
                            <a href="<?=$this->url(array('controller'=>'Reporting','action'=>'assignedgift','token'=>$rowData['gift_id']),'default',true)?>"><?=$assigned?></a>
                            <?php } else { echo $assigned; } ?>
                        </td>
						<td align="center"><?=$rowData['valid_from']?></td>
						<td align="center"><?=$rowData['valid_to']?></td>
						<!--<td align="center" id="user_portion<?=$rowData['gift_id']?>">
						<?php if($rowData['isActive']=='1'){?>
						   <img src="<?=IMAGE_LINK?>/icon_active.gif" align="absmiddle" alt="Inactive" border="0" 
				onclick="changeStatusByportion('<?='employee_personaldetail'?>','<?=$rowData['gift_id']?>','user_status','0','product_id','user_portion');" title="Active" class="changeStatus" />
						<?php }else{?>
						<img src="<?=IMAGE_LINK?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
				onclick="changeStatusByportion('<?='employee_personaldetail'?>','<?=$rowData['gift_id']?>','user_status','1','product_id','user_portion');" title="Inactive"  class="changeStatus" />
						<?php }?>
						</td>-->
						<td align="left">
						<a href="<?=$this->url(array('controller'=>'Reporting','action'=>'assigngift','token'=>$rowData['gift_id']),'default',true)?>"><img src="<?=Bootstrap::$baseUrl?>public/admin_images/pencil.gif" title="Assign to BE" alt="Assign to BE" /></a>
						<!--&nbsp;|&nbsp; <a href="<?=$this->url(array('controller'=>'Reporting','action'=>'editgift','token'=>$rowData['gift_id']),'default',true)?>"><img src="<?=Bootstrap::$baseUrl?>public/admin_images/pencil.gif" title="Edit" /></a>
                        &nbsp;|&nbsp; <a onclick="fancyboxopenfor('<?=$this->url(array('controller'=>'Reporting','action'=>'viewgift','token'=>$rowData['gift_id']),'default',true)?>')"><img src="<?=Bootstrap::$baseUrl?>public/admin_images/salaryslip.png" title="View Detail" /></a>
					 	&nbsp;|&nbsp; <a onclick="fancyboxopenfordelete('<?=$this->url(array('controller'=>'Reporting','action'=>'deletegift','token'=>$rowData['gift_id']),'default',true)?>')"><img src="<?=Bootstrap::$baseUrl?>public/admin_images/delete_image.png" title="Delete" /></a>-->
						</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="6">No Data Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        </form>
                        <div class="pager" id="pager">
                            <form action="">
                                <div>
                                <a href="" class="button"><span><img src="<?=IMAGE_LINK?>/arrow-180-small.gif" tppabs="http://www.xooom.pl/work/magicadmin/images/arrow-180-small.gif" height="9" width="12" alt="Previous" /> Prev</span></a>
                                <input type="text" class="pagedisplay input-short align-center"/>
								 <a href="" class="button"><span>Next <img src="<?=IMAGE_LINK?>/arrow-000-small.gif" height="9" width="12" alt="Next" /></span></a> 
                                <select class="pagesize input-short align-center">
                                    <option value="10" selected="selected">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                </select>
                                </div>
                            </form>
							</div>
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