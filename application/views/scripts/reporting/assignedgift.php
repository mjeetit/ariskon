 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Gift Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr> 
								<td colspan="12">
                                	<a href="<?=$this->url(array('controller'=>'Reporting','action'=>'gift'),'default',true)?>" class="button back">
						  			<span>Back<img src="<?=IMAGE_LINK?>/plus-small.gif" width="12" height="9" alt="Back" /></span>
									</a>
								</td>
							</tr>
							
                                <tr>
									<th style="width:25%;text-align:center">Gift Name</th>
									<th style="width:25%;text-align:center">BE Name</th>
                                    <th style="width:25%;text-align:center">Assigned Quantity</th>
									<th style="width:25%;text-align:center">Validity</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php
							 if(count($this->tableDatas)>0){
								foreach($this->tableDatas as $i=>$rowData){
								  $class = ($i%2==0)?'even':'odd';
								?>
								<tr class="<?=$class?>">
								<td align="center"><?=$rowData['gift_name']?></td>
								<td align="center"><?=$rowData['empName']?></td>
                        		<td align="center"><?=$rowData['assigned_quantity']?></td>
                                <td align="center"><?=$rowData['valid_from'].' To '.$rowData['valid_to']?></td>
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
                     </div>
                </div>
			</div>