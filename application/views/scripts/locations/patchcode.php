 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Patch Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="" method="post" enctype="multipart/form-data">
                        <table id="myTable" class="tablesorter">
                        	<thead>
							<tr class="odd">
							  <td colspan="11" class="bold_text">
						       <a href="<?=$this->url(array('controller'=>'Locations','action'=>'add','level'=>10),'default',true)?>" class="button add">
                        	     <span>Add New<img src="<?=IMAGE_LINK?>/plus-small.gif"  width="12" height="9" alt="Add patch" /></span>
                               </a>
					          &nbsp; <b>Headquarter :</b> 
                                <select name="headtoken" id="headtoken" style="width:140px;">
                                  <option value="">--Select Headquarter--</option>
                                  <?php foreach($this->headquarters as $key=>$headquarter) { ?>
                                  <option value="<?=$headquarter['headquater_id']?>" <?php if($this->postData['headtoken']==$headquarter['headquater_id']) {?>selected="selected"<?php } ?>><?=$headquarter['headquater_name']?></option>
                                  <?php } ?>
                                </select>                                
                                &nbsp; <b>Type :</b> 
                                <select name="typetoken" id="typetoken" style="width:140px;">
                                  <option value="">--Select Location Type--</option>
								  <?php $ltypes = $this->ObjModel->getLocationType();
								  foreach($ltypes as $ltype){
								  $select = ($this->postData['typetoken']==$ltype['location_type_id']) ? 'selected="selected"' : '';
								  ?>
                                  <option value="<?=$ltype['location_type_id']?>" <?=$select?>><?=$ltype['location_type_name']?></option>
                                  <?php } ?>
                                </select>
                                &nbsp; <b>Excel File :</b> <input type="file" name="patchsheet" />
								  <input type="submit" name="uploadpatch" value="Upload" /> &nbsp;
								<!--<span id="fairbutton" style="display:none;">--><input type="submit" name="uploadpatchfair" value="Upload & Update" /><!--</span>
                                <input type="submit" name="uploadpatch" value="Upload" /> &nbsp;
                                <input type="submit" name="expheader" value="Export Header" /> &nbsp;-->
                                <input type="submit" name="expdata" value="Export Data" /> &nbsp;
                                <input type="submit" name="search" value="Search" />
                              </td>
							</tr>
                            
                            <tr class="even"><td colspan="11">&nbsp;</td></tr>
                            
                                <tr>
									<th style="width:8%;text-align:center">Patch Code</th>
                                    <th style="width:20%;text-align:center">Patch Name</th>
									<th style="width:8%;text-align:center">Type</th>
                                    <th style="width:8%;text-align:center">City</th>
                                    <th style="width:10%;text-align:center">Headquarter</th>
									<th style="width:8%;text-align:center">Area Name</th>
									<th style="width:10%;text-align:center">Region Name</th>
									<th style="width:8%;text-align:center">Zone</th>
									<th style="width:15%;text-align:center">Business Unit</th>
									<th style="width:10%;text-align:center">Fair</th>
									<th style="width:5%;text-align:center" id="noClass1">Action</th>
                                </tr>
                                <tr id="filterrow">
                                    <th style="width:8%;text-align:center">Patch Code</th>
                                    <th style="width:20%;text-align:center">Patch Name</th>
									<th style="width:8%;text-align:center">Type</th>
                                    <th style="width:8%;text-align:center">City</th>
                                    <th style="width:10%;text-align:center">Headquarter</th>
									<th style="width:8%;text-align:center">Area Name</th>
									<th style="width:10%;text-align:center">Region Name</th>
									<th style="width:8%;text-align:center">Zone</th>
									<th style="width:15%;text-align:center">Business Unit</th>
									<th style="width:10%;text-align:center">Fair</th>
                                    <td style="width:5%;background-color:#eee" ></td>
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
								<td align="center"><?=$patch['location_type_name']?></td>
                                <td align="center"><?=$patch['city_name']?></td>
                                <td align="center"><?=$patch['headquater_name']?></td>
								<td align="center"><?=$patch['area_name']?></td>
								<td align="center"><?=$patch['region_name']?></td>
								<td align="center"><?=$patch['zone_name']?></td>
								<td align="center"><?=$patch['bunit_name']?></td>
								<td align="center"><?=$patch['fair']?></td>
								<td align="center"><a href="<?php echo $this->url(array('controller'=>'Locations','action'=>'edit','level'=>10,'patch_id'=>$patch['patch_id']),'default',true)?>"><img src="<?php echo Bootstrap::$baseUrl;?>public/admin_images/pencil.gif" /></a>
									</td>
								</tr>
								<?php }} else{ ?>
								<tr>
								<td align="center" colspan="5">No Record Found!...</td>
								</tr>
								<?php }?>
								
								<!-- Paging Style : 1 -->
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
<script type="text/javascript">
 $(function(){
 var table = $('#myTable').DataTable({
   pageLength: 30,
   "order": [[ 0, "desc" ]],
   orderCellsTop: true
  });
     // Setup - add a text input to each footer cell
    $('#myTable thead tr#filterrow th').each( function () {
        var title = $('#myTable thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable

     
    // Apply the filter
    $("#myTable thead input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );
    $("#noClass1").removeClass();
    $("#noClass2").removeClass();
 });
  $(document).ready(function(){
    $("th").mouseout(function(){
        $("#noClass1").removeClass();
        $("#noClass2").removeClass();
    });
});
 </script>