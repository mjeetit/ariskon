 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Product Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr> 
								<td colspan="12"> <a href="<?php echo $this->url(array('controller'=>'Product','action'=>'add'),'default',true)?>" class="button">
                        			<span>Add New Product<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add New User" /></span></a>
								</td>
							</tr>
							
                                <tr>
									<th style="width:3%; text-align:center">#</td>
									<th style="width:10%; text-align:center">Product Name</th>
									<th style="width:8%; text-align:center">Pack</th>
									<th style="width:8%; text-align:center">MRP Incl. VAT</th>
									<th style="width:8%; text-align:center">Stockiest Price</th>
									<th style="width:10%; text-align:center">Retailer Price</th>
									<th style="width:8%; text-align:center">VAT</th>
                                    <th style="width:8%; text-align:center">Status</th>
									<th style="width:12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php
							 if(count($this->products)>0){
								for($i=0;$i<count($this->products);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="product_id" value="<?php echo $this->products[$i]['product_id']?>" /></td>
						<td align="center"><?php echo $this->products[$i]['product_name'];?></td>
						<td align="center"><?php echo $this->products[$i]['typeName'];?></td>
						<td align="center"><?=number_format($this->products[$i]['mrp_incl_vat'],2)?></td>
						<td align="center"><?=number_format($this->products[$i]['stockist_excl_vat'],2)?></td>
						<td align="center"><?=number_format($this->products[$i]['retailer_excl_vat'],2)?></td>
						<td align="center"><?=number_format($this->products[$i]['vat_charged'],2)?></td>
						<td align="center" id="user_portion<?php echo $this->products[$i]['product_id'];?>">
						<?php if($this->products[$i]['isActive']=='1'){?>
						   <img src="<?php print IMAGE_LINK; ?>/icon_active.gif" align="absmiddle" alt="Inactive" border="0" 
				onclick="changeStatusByportion('<?php echo 'employee_personaldetail';?>','<?php print $this->products[$i]['product_id']; ?>','user_status','0','product_id','user_portion');" title="Active" class="changeStatus" />
						<?php }else{?>
						<img src="<?php print IMAGE_LINK; ?>/icon_inactive.gif" align="absmiddle" alt="Active" border="0" 
				onclick="changeStatusByportion('<?php echo 'employee_personaldetail';?>','<?php print $this->products[$i]['product_id']; ?>','user_status','1','product_id','user_portion');" title="Inactive"  class="changeStatus" />
						<?php }?>
						</td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Product','action'=>'edit','token'=>$this->products[$i]['product_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit" /></a>&nbsp;|
						<a onclick="fancyboxopenfor('<?php echo $this->url(array('controller'=>'Product','action'=>'view','token'=>$this->products[$i]['product_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/salaryslip.png" title="View Detail" /></a>&nbsp;|
					 	<a  onclick="fancyboxopenfordelete('<?php echo $this->url(array('controller'=>'Product','action'=>'delete','token'=>$this->products[$i]['product_id']),'default',true)?>')"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/delete_image.png" title="Delete" /></a>
						</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="6">No Product Found!...</td>
								</tr>
								<?php }?>
                            </tbody>
                        </table>
                        </form>
                        <div class="pager" id="pager">
                            <form action="">
                                <div>
                                <a href="" class="button"><span><img src="<?php echo IMAGE_LINK;?>/arrow-180-small.gif" tppabs="http://www.xooom.pl/work/magicadmin/images/arrow-180-small.gif" height="9" width="12" alt="Previous" /> Prev</span></a>
                                <input type="text" class="pagedisplay input-short align-center"/>
								 <a href="" class="button"><span>Next <img src="<?php echo IMAGE_LINK;?>/arrow-000-small.gif" height="9" width="12" alt="Next" /></span></a> 
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