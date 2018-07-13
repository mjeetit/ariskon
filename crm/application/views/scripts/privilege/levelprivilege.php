 <div class="grid_12">
	            <!-- Example table -->
                <div class="module">
                	<h2><span>Designation Wise Default Privilege Lists</span></h2>
                    
                    <div class="module-table-body">
                    	<form action="">
                        <table id="myTable" class="tablesorter">
                        	<thead>							
                                <tr>
									<th style="width:2%; text-align:left">SlNo.</td>
									<th style="width:18%; text-align:left"><?=CommonFunction::OrderBy('Designation Name','DT.designation_name')?></th>
                                    <th style="width:80%; text-align:left">Default Privilege</th>
                                </tr>
                            </thead>
							
                            <tbody>
                             <?php if(count($this->viewData)>0) { foreach($this->viewData as $key=>$priv) {?>
							 <tr>
							 	<td><?=($key+1)?>.</td>
								<td><a href="javascript:void(0);" onclick="fancyboxopenfor('<?=$this->url(array('controller'=>'Privilege','action'=>'editlevelprivilege','token'=>Class_Encryption::encode($priv['designation_id'])),'default',true)?>')" title="See All Modules" /><?=$priv['designation_name']?></a></td>
								<td><?=$priv['module']?> <a href="javascript:void(0);" onclick="fancyboxopenfor('<?=$this->url(array('controller'=>'Privilege','action'=>'editlevelprivilege','token'=>Class_Encryption::encode($priv['designation_id'])),'default',true)?>')" title="See All Modules" />[See All Modules]</a></td>
							 </tr>
							 <?php } } else { ?>
							 <tr><td colspan="3" align="center">No record found!!</td></tr>
							 <?php }?>
                            </tbody>
                        </table>
                        </form>
                        
                        <div style="clear: both"></div>
                     </div> <!-- End .module-table-body -->
                </div> 
				<!-- End .module -->
			</div>
			
<script type="text/javascript">
function fancyboxopenfor(url){
 $.fancybox({
        "width": "70%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}

function fancyboxopenforpriv(url){
 $.fancybox({
        "width": "40%",
        "height": "100%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
function fancyboxopenfordelete(url){
 $.fancybox({
        "width": "40%",
        "height": "40%",
        "autoScale": true,
        "transitionIn": "fade",
        "transitionOut": "fade",
        "type": "iframe",
        "href": url
    }); 
}
</script>	