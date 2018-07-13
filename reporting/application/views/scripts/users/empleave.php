 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Emplopyee Leave List</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							
                                <tr>
									<th style="width:3%; text-align:center"><input type="checkbox" name="checked" id="checked" /></td>
									<th style="width:10%; text-align:center">Employee Code</th>
									<th style="width:8%; text-align:center">Department</th>
									<th style="width:8%; text-align:center">Designation</th>
									<?php foreach($this->empleaves[1] as $types){?>
									  <th style="width:10%; text-align:center"><?php echo $types['typeName']?></th>
									<?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->empleaves[0]){
								for($i=0;$i<count($this->empleaves[0]);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><input type="checkbox" name="user_id[]" value="<?php echo $this->empleaves[0][$i]['user_id']?>" /></td>
						<td align="center"><?php echo $this->empleaves[0][$i]['employee_code'];?></td>
						<td align="center"><?php echo $this->empleaves[0][$i]['department_name'];?></td>
						<td align="center"><?php echo $this->empleaves[0][$i]['designation_name'];?></td>
						<?php foreach($this->empleaves[1] as $types){ ?>
								<td align="center"><input type="text" name="leave[<?php echo $this->empleaves[0][$i]['user_id']?>][<?php echo $types['typeID']?>]" value="<?php echo $this->empleaves[0][$i]['Leave'][$types['typeID']]?>" class="input-short" /></td>
						<?php } ?>
						
								</tr>
							
								<?php } ?>
								<tr><td colspan="7" align="right"><input type="submit" name="Update" value="Update" class="submit-green" /></td></tr>	
								<?php } else{ ?>
								<tr>
								<td align="center" colspan="7">No Record Found!...</td>
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