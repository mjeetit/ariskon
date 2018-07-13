 <div class="full_w">

                <div class="h_title">Head Office</div>

				<div class="entry">

                    <div class="sep"></div>

			     <a id="view1" class="button" onclick="showtable('1');">View Branch Office</a>

			     <a id="view2" class="button" onclick="showtable('2');">View Corpoate Office</a>

                 <a id="view3" class="button" onclick="showtable('3');">View HeadQuater</a>

			  <div class="sep1">

				 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'add','level'=>7,'ID'=>1),'default',true)?>" id="add1" class="button add">Add Branch Office</a>

			     <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'add','level'=>7,'ID'=>2),'default',true)?>" id="add2" class="button add">Add Corpoate Office</a>

                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'add','level'=>7,'ID'=>3),'default',true)?>" id="add3" class="button add">Add HeadQuater</a>

				  </div>

                </div>

                

                <table width="100%" id="table1">

                    <thead>

                        <tr>

                           <td width="40px"></td>

                        

                        </tr>

						<tr>

						<th>#</td>

						<th>Office Name</th>

						<th>Office Address</th>

						<th>City</th>

						<th>Area</th>

						<th>Region</th>

						<th>Zone</th>

						<th>Business Unit</th>

						<th>Action</th>

						</tr>

						<?php 

						if($this->headoffice[0]){

						for($i=0;$i<count($this->headoffice[0]);$i++){?>

						<tr>

						<td><input type="checkbox" name="headoff_id" value="<?php echo $this->headoffice[0][$i]['headoff_id']?>" /></td>

						<td align="center"><?php echo $this->headoffice[0][$i]['office_name']?></td>

						<td align="center"><?php echo $this->headoffice[0][$i]['headoffice_address']?></td>

						<td align="center"><?php echo $this->headoffice[0][$i]['city_name']?></td>

						<td align="center"><?php echo $this->headoffice[0][$i]['area_name']?></td>

						<td align="center"><?php echo $this->headoffice[0][$i]['region_name']?></td>

						<td align="center"><?php echo $this->headoffice[0][$i]['zone_name']?></td>

						<td align="center"><?php echo $this->headoffice[0][$i]['bunit_name']?></td>

					<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>7,'headoff_id'=>$this->headoffice[0][$i]['headoff_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>

						</td>

						</tr>

						<?php } }else{ ?>

						<tr>

						<td colspan="5" align="center">No Record Found...</td>

						</tr>

						<?php }?>

                    </thead>

                </table>

				<table width="100%" id="table2">

                    <thead>

                        <tr>

                           <td width="40px"></td>

                        

                        </tr>

						<tr>

						<th>#</td>

						<th>Office Name</th>

						<th>Office Address</th>

						<th>City</th>

						<th>Area</th>

						<th>Region</th>

						<th>Zone</th>

						<th>Business Unit</th>

						<th>Action</th>

						</tr>

						<?php 

						if($this->headoffice[1]){

						for($i=0;$i<count($this->headoffice[1]);$i++){?>

						<tr>

						<td><input type="checkbox" name="headoff_id" value="<?php echo $this->headoffice[1][$i]['headoff_id']?>" /></td>

						<td align="center"><?php echo $this->headoffice[1][$i]['office_name']?></td>

						<td align="center"><?php echo $this->headoffice[1][$i]['headoffice_address']?></td>

						<td align="center"><?php echo $this->headoffice[1][$i]['city_name']?></td>

						<td align="center"><?php echo $this->headoffice[1][$i]['area_name']?></td>

						<td align="center"><?php echo $this->headoffice[1][$i]['region_name']?></td>

						<td align="center"><?php echo $this->headoffice[1][$i]['zone_name']?></td>

						<td align="center"><?php echo $this->headoffice[1][$i]['bunit_name']?></td>

					<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>7,'headoff_id'=>$this->headoffice[1][$i]['headoff_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>

						</td>

						</tr>

						<?php } }else{ ?>

						<tr>

						<td colspan="5" align="center">No Record Found...</td>

						</tr>

						<?php }?>

                    </thead>

                </table>

				<table width="100%" id="table3">

                    <thead>

                        <tr>

                           <td width="40px"></td>

                        

                        </tr>

						<tr>

						<th>#</td>

						<th>Office Name</th>

						<th>Office Address</th>

						<th>City</th>

						<th>Area</th>

						<th>Region</th>

						<th>Zone</th>

						<th>Business Unit</th>

						<th>Action</th>

						</tr>

						<?php 

						if($this->headoffice[2]){

						for($i=0;$i<count($this->headoffice[2]);$i++){?>

						<tr>

						<td><input type="checkbox" name="headoff_id" value="<?php echo $this->headoffice[2][$i]['headoff_id']?>" /></td>

						<td align="center"><?php echo $this->headoffice[2][$i]['office_name']?></td>

						<td align="center"><?php echo $this->headoffice[2][$i]['headoffice_address']?></td>

						<td align="center"><?php echo $this->headoffice[2][$i]['city_name']?></td>

						<td align="center"><?php echo $this->headoffice[2][$i]['area_name']?></td>

						<td align="center"><?php echo $this->headoffice[2][$i]['region_name']?></td>

						<td align="center"><?php echo $this->headoffice[2][$i]['zone_name']?></td>

						<td align="center"><?php echo $this->headoffice[2][$i]['bunit_name']?></td>

					<td align="center"><a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'edit','level'=>7,'headoff_id'=>$this->headoffice[2][$i]['headoff_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>

						</td>

						</tr>

						<?php } }else{ ?>

						<tr>

						<td colspan="5" align="center">No Record Found...</td>

						</tr>

						<?php }?>

                    </thead>

                </table>

            </div>

        </div>

        <div class="clear"></div>

    </div>

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