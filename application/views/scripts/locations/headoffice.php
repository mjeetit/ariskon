 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Offices</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<tbody>
							<tr>
							<td colspan="6">
						  <a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'add','level'=>7),'default',true)?>" id="add1" class="button add">
                        	<span>Add New Office<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Add City" /></span>
                        </a>
					</td>
					</tr>
                                <tr>
									<th style="width:5%;; text-align:center">#</th>
									<th style="width:15%;; text-align:center">Office Name</th>
									<th style="width:15%;; text-align:center">Office Address</th>
									<th style="width:15%;; text-align:center">Office Type</th>
									<th style="width:15%;; text-align:center">City Name</th>
									<th style="width:15%;; text-align:center">Area Name</th>
									<th style="width:15%;; text-align:center">Region Name</th>
									<th style="width:15%;; text-align:center">Zone</th>
									<th style="width:15%;; text-align:center">Business Unit</th>
									<th style="width:5%;; text-align:center">Action</th>
                                </tr>
                            <?php 
						if($this->headoffice){
						for($i=0;$i<count($this->headoffice);$i++){?>
						<tr>
						<td><input type="checkbox" name="headoff_id" value="<?php echo $this->headoffice[$i]['headoff_id']?>" /></td>
						<td align="center"><?php echo $this->headoffice[$i]['office_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['headoffice_address']?></td>
						<td align="center"><?php if($this->headoffice[$i]['office_type']==2){ echo 'Corporate Office';}elseif($this->headoffice[$i]['office_type']==1){ echo 'Branch Office';}elseif($this->headoffice[$i]['office_type']==3){ echo 'HeadQuater';}?></td>
						<td align="center"><?php echo $this->headoffice[$i]['city_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['area_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['region_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['zone_name']?></td>
						<td align="center"><?php echo $this->headoffice[$i]['bunit_name']?></td>
					<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>7,'headoff_id'=>$this->headoffice[$i]['headoff_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
						</td>
						</tr>
						<?php } }else{ ?>
						<tr>
						<td colspan="5" align="center">No Record Found...</td>
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
			
			
			
<script type="text/javascript">
showtable('');
function showtable(id){
   var i; 
   if(id==''){
       id=1;
   }
   for(i=1;i<4;i++){
     if(id==i){
	  $("#table"+i).show();
	  $("#view"+i).hide();
	  $("#add"+i).show();
	 }else{
	  $("#table"+i).hide();
	  $("#view"+i).show();
	  $("#add"+i).hide();
	 }
      
   }
}
</script>	