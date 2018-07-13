 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Stockist Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post" enctype="multipart/form-data">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr class="odd">
							  <td>
						       <a href="<?=$this->url(array('controller'=>'Stockist','action'=>'add'),'default',true)?>" class="button add">
                        	     <span>Add New<img src="<?=IMAGE_LINK?>/plus-small.gif"  width="12" height="9" alt="Add Stockist" /></span>
                               </a>
                              </td>
                              <td><b>Headquarter</b>
                                <select name="headtoken" id="headtoken" style="width:140px;">
                                  <option value="">--Select Headquarter--</option>
                                  <?php foreach($this->headquarters as $key=>$headquarter) { ?>
                                  <option value="<?=$headquarter['headquater_id']?>" <?php if($this->postData['headtoken']==$headquarter['headquater_id']) {?>selected="selected"<?php } ?>><?=$headquarter['headquater_name']?></option>
                                  <?php } ?>
                                </select>
                              </td>
                              <td colspan="2">
                              	<b>Excel File :</b> <input type="file" name="stockistsheet" />
								<input type="submit" name="uploadfile" value="Upload New" />
                                <input type="submit" name="expheader" value="Export Header" />
                                <input type="submit" name="expdata" value="Export Data" />
                                <input type="submit" name="search" value="Search" />
                              </td>
							</tr>
                            
                            <tr class="even"><td colspan="4">&nbsp;</td></tr>
                            
                                <tr>
									<th style="width:15%;text-align:center"><?=CommonFunction::OrderBy('Headquarter','HQ.headquater_name')?></th>
									<th style="width:25%;text-align:left"><?=CommonFunction::OrderBy('Stockist Name','ST.stockist_name')?></th>
									<th style="width:50%;text-align:left"><?=CommonFunction::OrderBy('Address','ST.address')?></th>
									<th style="width:10%;text-align:center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php 
							 if(count($this->stockists['Records'])>0){
								foreach($this->stockists['Records'] as $key=>$stockist) {
								?>
								<tr class="<?php echo  $class;?>">
								<td align="center"><?=$stockist['hq']?></td>
								<td align="left"><?=$stockist['stockist_name']?></td>
								<td align="left"><?=$stockist['address']?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Stockist','action'=>'edit','level'=>9,'stockist_id'=>$stockist['stockist_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
									</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="5">No Record Found!...</td>
								</tr>
								<?php }?>
								
								<!-- Paging Style : 1 -->
								<tr>
								<th colspan="13" style="text-align:left"><?=CommonFunction::PageCounter($this->stockists['Total'], $this->stockists['Offset'], $this->stockists['Toshow'], "headertext", $groupby=10, $showcounter=1, $linkStyle='view_link', $redText='headertext')?></th>
								</tr>
                            </tbody>
                        </table>
                        </form>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->