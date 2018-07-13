 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Office</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="5">
						    <a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'office'),'default',true)?>" class="button add">
                        	<span>Back<img src="<?php echo IMAGE_LINK;?>/plus-small.gif"  width="12" height="9" alt="Back" /></span>
                        </a>
					</td>
							</tr>
                                <tr>
                                  <td>Office Name</td><td><input type="text" name="office_name" class="input-short" /></td>
                                </tr>
                                <tr>
                                    <td>Office Address</td><td><textarea name="office_address" cols="10" rows="6"></textarea></td>
                                </tr>
                                <tr>
                                    <td>Office Type</td><td>
									   <select name="office_type" class="input-medium">
									   <option value="1">Corporate Office</option>
									    <option value="2">Branch Office</option>
										 <option value="3">HeadQuater</option>
									   </select>                                    
                                    </td>
									<tr>
                                    <td><input type="submit" name="submit" id="submit" value="Save" class="submit-green" /></td>
                                </tr>
                                </tr>
                            </thead>
                          
                        </table>
                        </form>
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 -->