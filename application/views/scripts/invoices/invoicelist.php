 <div class="full_w">
                <div class="h_title">Invoices</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Invoices','action'=>'addinvoice'),'default',true)?>" class="button add">Add New Invoice</a>
				  <div class="sep1"></div>
                </div>
                
                <table width="100%">
                    <thead>
                        <tr>
                           <td width="40px"></td>
                        
                        </tr>
						<tr>
						<th>#</td>
						<th>Invoice Number</th>
						<th>Article Number</th>
						<th>Quantity</th>
						<th>Unit Price</th>
						<th>Total Price</th>
						<th>Action</th>
						</tr>
						<?php 
						 if($this->Invoices){
						for($i=0;$i<count($this->Invoices);$i++){?>
						<tr>
						<td align="center"><input type="checkbox" name="invoice_number" value="<?php echo $this->Invoices[$i]['invoice_number']?>" /></td>
						<td align="center"><?php echo $this->Invoices[$i]['invoice_number']?></td>
						<td align="center"><?php echo $this->Invoices[$i]['article_number']?></td>
						<td align="center"><?php echo $this->Invoices[$i]['quantity'];?></td>
						<td align="center"><?php echo $this->Invoices[$i]['price'];?></td>
						<td align="center"><?php echo ($this->Invoices[$i]['price'] * $this->Invoices[$i]['quantity']);?></td>
						<td align="center">
						<a href="<?php echo $this->url(array('controller'=>'Invoices','action'=>'editinvoice','invoice_number'=>$this->Invoices[$i]['invoice_number']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" title="Edit Invoice" />&nbsp;
						</td>
						</tr>
						<?php }} else{ ?>
						<tr>
						<td align="center" colspan="7">No Record Found!...</td>
						</tr>
						<?php }?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>