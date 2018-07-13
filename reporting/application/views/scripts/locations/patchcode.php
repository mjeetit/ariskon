 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Patch Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post" enctype="multipart/form-data">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr class="odd">
							  <td>
						       <a href="<?=$this->url(array('controller'=>'Locations','action'=>'add','level'=>9),'default',true)?>" class="button add">
                        	     <span>Patchcode<img src="<?=IMAGE_LINK?>/plus-small.gif"  width="12" height="9" alt="Add patch" /></span>
                               </a>
					          </td>
                              <td><b>Headquarter</b>
                                <select name="headtoken" id="headtoken" style="width:140px;" required>
                                  <option value="">--Select Headquarter--</option>
                                  <?php foreach($this->headquarters as $key=>$headquarter) { ?>
                                  <option value="<?=$headquarter['headquater_id']?>" <?php if($this->postData['headtoken']==$headquarter['headquater_id']) {?>selected="selected"<?php } ?>><?=$headquarter['location_code'].' : '.$headquarter['headquater_name']?></option>
                                  <?php } ?>
                                </select>
                              </td>
                              <td colspan="7">
                              	<b>Excel File :</b> <input type="file" name="patchsheet" />
                                <input type="submit" name="expheader" value="Export Header" /> &nbsp;
                                <input type="submit" name="uploadpatch" value="Upload" /> &nbsp;
                                <input type="submit" name="expdata" value="Export Data" /> &nbsp;
                                <input type="submit" name="search" value="Search" />
                              </td>
							</tr>
                            
                            <tr class="even"><td colspan="9">&nbsp;</td></tr>
                            
                                <tr>
									<th style="width:10%;; text-align:center">Patch Code</th>
                                    <th style="width:20%;; text-align:center">Patch Name</th>
									<th style="width:15%;; text-align:center">City</th>
                                    <th style="width:15%;; text-align:center">Headquarter</th>
									<th style="width:10%;; text-align:center">Area Name</th>
									<th style="width:15%;; text-align:center">Region Name</th>
									<th style="width:10%;; text-align:center">Zone</th>
									<th style="width:15%;; text-align:center">Business Unit</th>
									<th style="width:5%;; text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if($this->patch){
								for($i=0;$i<count($this->patch);$i++){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><?php echo $this->patch[$i]['patchcode']?></td>
								<td align="center"><?php echo $this->patch[$i]['patch_name']?></td>
								<td align="center"><?php echo $this->patch[$i]['city_name']?></td>
                                <td align="center"><?php echo $this->patch[$i]['headquater_name']?></td>
								<td align="center"><?php echo $this->patch[$i]['area_name']?></td>
								<td align="center"><?php echo $this->patch[$i]['region_name']?></td>
								<td align="center"><?php echo $this->patch[$i]['zone_name']?></td>
								<td align="center"><?php echo $this->patch[$i]['bunit_name']?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'edit','level'=>9,'patch_id'=>$this->patch[$i]['patch_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
									</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="5">No Record Found!...</td>
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