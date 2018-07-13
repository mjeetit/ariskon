 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Product Measurement Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr> 
								<td colspan="12"> <a href="<?=$this->url(array('controller'=>'Measurement','action'=>'add'),'default',true)?>" class="button">
                        			<span>Add New<img src="<?=IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add New Location Type" /></span></a>
								</td>
							</tr>
							
                                <tr>
									<th style="width:2%; text-align:center">#</td>
									<th style="width:10%; text-align:center"><?=CommonFunction::OrderBy('Measurement Type','type_name')?></th>
                                    <th style="width:8%; text-align:center"><?=CommonFunction::OrderBy('Status','isActive')?></th>
									<th style="width:12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php
							 if(count($this->tableDatas)>0){
								foreach($this->tableDatas as $i=>$rowData){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?=$class?>">
								<td align="center"><input type="checkbox" name="mtoken" value="<?=$rowData['pack_type']?>" /></td>
						<td align="center"><?=$rowData['type_name'];?></td>
						<td align="center" id="user_portion<?=$rowData['pack_type'];?>">
						<?php if($rowData['isActive']=='1'){?>
						   <img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Inactive" border="0" 
				onclick="changeStatusByportion('<?='crm_product_packtypes';?>','<?=$rowData['pack_type']?>','isActive','0','pack_type','user_portion');" title="Active" class="changeStatus" />
						<?php }else{?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
				onclick="changeStatusByportion('<?='crm_product_packtypes';?>','<?=$rowData['pack_type']?>','isActive','1','pack_type','user_portion');" title="Inactive"  class="changeStatus" />
						<?php }?>
						</td>
						<td align="left">
						<a href="<?=$this->url(array('controller'=>'Measurement','action'=>'edit','token'=>Class_Encryption::encode($rowData['pack_type'])),'default',true)?>"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit" /></a>&nbsp;|
						<!--<a onclick="fancyboxopenfor('<?=$this->url(array('controller'=>'Measurement','action'=>'view','token'=>Class_Encryption::encode($rowData['pack_type'])),'default',true)?>')"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a>&nbsp;|-->
					 	<a  onclick="fancyboxopenfordelete('<?=$this->url(array('controller'=>'Measurement','action'=>'delete','token'=>Class_Encryption::encode($rowData['pack_type'])),'default',true)?>')"><img src="<?=Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" /></a>
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
                                <a href="" class="button"><span><img src="<?=IMAGE_LINK;?>/arrow-180-small.gif" tppabs="http://www.xooom.pl/work/magicadmin/images/arrow-180-small.gif" height="9" width="12" alt="Previous" /> Prev</span></a>
                                <input type="text" class="pagedisplay input-short align-center"/>
								 <a href="" class="button"><span>Next <img src="<?=IMAGE_LINK;?>/arrow-000-small.gif" height="9" width="12" alt="Next" /></span></a> 
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