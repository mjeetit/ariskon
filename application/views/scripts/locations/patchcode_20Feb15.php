 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Patch Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post" enctype="multipart/form-data">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr class="odd">
							  <td colspan="2">
						       <a href="<?=$this->url(array('controller'=>'Locations','action'=>'add','level'=>9),'default',true)?>" class="button add">
                        	     <span>Add New<img src="<?=IMAGE_LINK?>/plus-small.gif"  width="12" height="9" alt="Add patch" /></span>
                               </a>
					          &nbsp;
                              <b>Headquarter</b>
                                <select name="headtoken" id="headtoken" style="width:140px;">
                                  <option value="">--Select Headquarter--</option>
                                  <?php foreach($this->headquarters as $key=>$headquarter) { ?>
                                  <option value="<?=$headquarter['headquater_id']?>" <?php if($this->postData['headtoken']==$headquarter['headquater_id']) {?>selected="selected"<?php } ?>><?=$headquarter['headquater_name']?></option>
                                  <?php } ?>
                                </select>
                              </td>
                              <td colspan="8">
                              	<b>Excel File :</b> <input type="file" name="patchsheet" />
								<!--<span id="fairbutton" style="display:none;">--><input type="submit" name="uploadpatchfair" value="Upload & Update" /><!--</span>
                                <input type="submit" name="uploadpatch" value="Upload" /> &nbsp;
                                <input type="submit" name="expheader" value="Export Header" /> &nbsp;-->
                                <input type="submit" name="expdata" value="Export Data" /> &nbsp;
                                <input type="submit" name="search" value="Search" />
                              </td>
							</tr>
                            
                            <tr class="even"><td colspan="10">&nbsp;</td></tr>
                            
                                <tr>
									<th style="width:10%;text-align:center"><?=CommonFunction::OrderBy('Patch Code','PC.patchcode')?></th>
                                    <th style="width:20%;text-align:center"><?=CommonFunction::OrderBy('Patch Name','PC.patch_name')?></th>
									<th style="width:10%;text-align:center"><?=CommonFunction::OrderBy('City','CT.city_name')?></th>
                                    <th style="width:10%;text-align:center"><?=CommonFunction::OrderBy('Headquarter','HQ.headquater_name')?></th>
									<th style="width:10%;text-align:center"><?=CommonFunction::OrderBy('Area Name','AT.area_name')?></th>
									<th style="width:10%;text-align:center"><?=CommonFunction::OrderBy('Region Name','RT.region_name')?></th>
									<th style="width:10%;text-align:center"><?=CommonFunction::OrderBy('Zone','ZT.zone_name')?></th>
									<th style="width:15%;text-align:center"><?=CommonFunction::OrderBy('Business Unit','BU.bunit_name')?></th>
									<th style="width:10%;text-align:center"><?=CommonFunction::OrderBy('Fair','PC.fair')?></th>
									<th style="width:5%;text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if(count($this->patch['Records'])>0){
								foreach($this->patch['Records'] as $key=>$patch) {
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><?=$patch['patchcode']?></td>
								<td align="center"><?=$patch['patch_name']?></td>
								<td align="center"><?=$patch['city_name']?></td>
                                <td align="center"><?=$patch['headquater_name']?></td>
								<td align="center"><?=$patch['area_name']?></td>
								<td align="center"><?=$patch['region_name']?></td>
								<td align="center"><?=$patch['zone_name']?></td>
								<td align="center"><?=$patch['bunit_name']?></td>
								<td align="center"><?=$patch['fair']?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'edit','level'=>9,'patch_id'=>$patch['patch_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
									</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="5">No Record Found!...</td>
								</tr>
								<?php }?>
								
								<!-- Paging Style : 1 -->
								<tr>
								<th colspan="13" style="text-align:left"><?=CommonFunction::PageCounter($this->patch['Total'], $this->patch['Offset'], $this->patch['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>
								</tr>
                            </tbody>
                        </table>
                        </form>
                        <!--<div class="pager" id="pager">
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
                        </div>-->
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->
			
<script type="text/javascript">
	$(function() {
		$( "#headtoken" ).change(function( event ) {
			var headt = $('#headtoken').val(); //alert(headt);return false;
			if(headt != '') {
				$('#fairbutton').show();
			}
			else {
				$('#fairbutton').hide();
			}
		});
	});
</script>