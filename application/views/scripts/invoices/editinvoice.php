 <div class="full_w">
                <div class="h_title">Add Invoice</div>
				 <form name="invoiceform" action="" method="post" id="invoiceform">  
				 <input type="hidden" name="invoice_id" value="<?php echo $this->Invoices[0]['invoice_id']?>" />
                <table width="100%">
                    <thead>
                     <tr>
					 <td>Invoice Number</td><td><input type="text" name="invoice_number" value="<?php echo $this->Invoices[0]['invoice_number']?>" /></td>
					 </tr>
					   <tr>
					 <td>Article Number</td><td><input type="text" name="article_number"  value="<?php echo $this->Invoices[0]['article_number']?>"/></td>
					 </tr>
					   <tr>
					 <td>Quantity</td><td><input type="text" name="quantity"  value="<?php echo $this->Invoices[0]['quantity']?>"/></td>
					 </tr>
					   <tr>
					 <td>Unit Price</td><td><input type="text" name="price" value="<?php echo $this->Invoices[0]['price']?>" /></td>
					 </tr>
					
					   <tr>
					 <td colspan="2" align="center"><input type="submit" name="addinvoice"  value="Update Invoice" />
					 </tr>
                    </thead>
                </table>
				</form>
         </div> 
		</div>
        <div class="clear"></div>
    </div>