	 <div class="grid_12">  
	            <!-- Example table -->
                <div class="module">  
                	<h2><span>Stockist Vist</span></h2>
                    
                    <div class="module-table-body"> 
                    	<form action="">
					
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr>
							<td colspan="11">
							<a href="<?php echo $this->url(array('controller'=>'Reporting','action'=>'doctorvisit'),'default',true)?>" class="button add">
                        	   <span>Doctor Visit</span>
                           </a>&nbsp;
						   <a href="<?php echo $this->url(array('controller'=>'Reporting','action'=>'chemistvisit'),'default',true)?>" class="button add">
                        	   <span>Chemist Visit</span>
                           </a>
						   
							</td>
							</tr>
						<tr>
						<th>Name</th>
						<th>Stockist Name</th>
                        <th>Order Detail</th>
						<th>Issues</th>
						 <th>Work With</th>
						<th>Date Added</th>
						<th>Action</th>
						</tr>
						<?php if(!empty($this->vistdetail)){ 
						foreach($this->vistdetail as $i=>$stockistvisit){
						 $class = ($i%2==0)?'even':'odd'; ?>
						 <tr class="<?php echo  $class;?>">
						  <td><?php echo $stockistvisit['first_name']?></td>
						 <td><?php echo $stockistvisit['stockist_name']?></td>
						 <td><?php echo $stockistvisit['orderdetail']?></td>
						 <td><?php echo $stockistvisit['issues']?></td>
                         <td><?php echo $this->ObjModel->visitWithdetail($stockistvisit);?></td>
						 <td><?php echo date('d-M-Y',strtotime($stockistvisit['date_added']))?></td>
						 <td align="center">
						 <a href="#"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
						 </td>
						 </tr>
						 <?php } }?>
                            </thead>
                        </table>
                       
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div> <!-- End .grid_12 --> 
