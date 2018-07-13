 <div class="full_w">
                <div class="h_title">Company And Branches</div>
				<div class="entry">
                    <div class="sep"></div>
                 <a href="<?php echo $this->url(array('controller'=>'Setting','action'=>'providentsetting'),'default',true)?>" class="button back">Back</a>
				  <div class="sep1"></div>
                </div>
           
			  <form name="form_company" action="" method="post"> 
                <table width="100%">
                    <thead>
					<?php echo $this->form;?>
                    </thead>
                </table>
				</form>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<?php if($this->formdata['Mode']=='Update'){?>
<script type="text/javascript">
	changeStatusBusiness('<?php echo $this->formdata['bunit_id'];?>','<?php echo $this->formdata['department_id'];?>');
	changeStatusDepartment('<?php echo $this->formdata['department_id'];?>','<?php echo $this->formdata['designation_id'];?>');
</script>
<?php } ?>