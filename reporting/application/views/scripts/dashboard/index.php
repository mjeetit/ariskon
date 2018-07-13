	
	   <!-- Account overview -->
            <div class="grid_5">
                <div class="module">
                        <h2><span>Account overview</span></h2>
                        
                        <div class="module-body">
                        
                        	<p>
                                <strong>User: </strong><?php echo ucfirst($_SESSION['AdminName']);?><br />
                                <strong>Your last visit was on: </strong><?php echo ucfirst($_SESSION['LastLogin']);?><br />
                                <strong>From IP: </strong><?php echo ucfirst($_SESSION['LastLoginIP']);?>
                            </p>
                        	<p>
							<?php //if($_SESSION['AdminLoginID']==1){?>
                                <a href="/reporting/public/DocumentDirectory/FieldForce1.1.apk">Field Force 1.1</a><br />
								
								<a href="/reporting/public/DocumentDirectory/MobileReporrting5.1.apk">Download app 5.1</a><br />
								
								<a href="/reporting/public/DocumentDirectory/MobileReporting_manual.pdf">Download Manual</a><br />
							     <!--<a href="<?php echo $this->url(array('controller'=>'Appsettings','action'=>'appdownload'),'default',true)?>">Download app</a><br />-->
                            <?php //} ?>
							</p>

                        </div>
                </div>
                <div style="clear:both;"></div>
            </div> <!-- End .grid_5 -->
			   <!-- Categories list -->
		     
            		 
		
		
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
</script>	