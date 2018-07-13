<div class="grid_12">
  <!-- Example table -->
  <div class="module">
    <h2><span>Stockist Vist</span></h2>
    <div class="module-table-body"> 
      <form action="" method="post" enctype="multipart/form-data">
        <table width="96%" border="0" cellspacing="1" cellpadding="2">
          <!--Zonal Business Manager (ZBM) Pull Down List for Filter Option-->
          <?php if($_SESSION['AdminDesignation']!=5) { ?>
          <tr class="even">
            <td align="left" class="bold_text">ZBM : </td>
            <td align="left"><select name="data[zbm_visit]">
                <option value="">-- Select ZBM --</option>
                <?php foreach($this->zbmDetails as $zbmDetail){
                    $select = '';
                    if($this->postData['zbm_visit']==$zbmDetail['user_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$zbmDetail['user_id']?>" <?=$select?>><?=$zbmDetail['first_name'].' '.$zbmDetail['last_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <?php } ?>
          
          <!--Regional Business Manager (RBM) Pull Down List for Filter Option-->
          <?php if($_SESSION['AdminDesignation']!=6) { ?>
          <tr class="odd">
            <td align="left" class="bold_text">RBM : </td>
            <td align="left"><select name="data[rbm_visit]">
                <option value="">-- Select RBM --</option>
                <?php foreach($this->rbmDetails as $rbmDetail){
                    $select = '';
                    if($this->postData['rbm_visit']==$rbmDetail['user_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$rbmDetail['user_id']?>" <?=$select?>><?=$rbmDetail['first_name'].' '.$rbmDetail['last_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <?php } ?>
          
          <!--Area Business Manager (ABM) Pull Down List for Filter Option-->
          <?php if($_SESSION['AdminDesignation']!=7) { ?>
          <tr class="even">
            <td align="left" class="bold_text">ABM : </td>
            <td align="left"><select name="data[abm_visit]">
                <option value="">-- Select ABM --</option>
                <?php foreach($this->abmDetails as $abmDetail){
                    $select = '';
                    if($this->postData['abm_visit']==$abmDetail['user_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$abmDetail['user_id']?>" <?=$select?>><?=$abmDetail['first_name'].' '.$abmDetail['last_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <?php } ?>
          
          <!--Business Executive (BE) Pull Down List for Filter Option-->
          <?php if($_SESSION['AdminDesignation']!=8) { ?>
          <tr class="odd">
            <td align="left" class="bold_text">BE : </td>
            <td align="left"><select name="data[be_visit]">
                <option value="">-- Select BE --</option>
                <?php foreach($this->beDetails as $beDetail){
                    $select = '';
                    if($this->postData['be_visit']==$beDetail['user_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$beDetail['user_id']?>" <?=$select?>><?=$beDetail['first_name'].' '.$beDetail['last_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <?php } ?>
          
          <!--Patchcode Pull Down List for Filter Option-->
          <tr class="even">
            <td align="left" class="bold_text">Patchcode : </td>
            <td align="left"><select name="data[patch_id]" required>
                <option value="">-- Select Patch --</option>
                <?php foreach($this->patches as $patch){
                    $select = '';
                    if($this->postData['patch_id']==$patch['patch_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$patch['patch_id']?>" <?=$select?>><?=$patch['patch_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          
          <!--Chemist Pull Down List for Filter Option-->
          <tr class="odd">
            <td align="left" class="bold_text">Stockist : </td>
            <td align="left"><select name="data[stockist_id]">
                <option value="">-- Select Stockist --</option>
                <?php foreach($this->stockists as $stockist){
                    $select = '';
                    if($this->postData['stockist_id']==$stockist['stockist_id']){
                        $select = 'selected="selected"';
                    }
                ?>
                <option value="<?=$stockist['stockist_id']?>" <?=$select?>><?=$stockist['stockist_name']?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          
          <!--Order Detail-->
          <tr class="even">
            <td align="left" class="bold_text">Call Detail : </td>
            <td align="left"><textarea name="data[orderdetail]" rows="5" class="20" required><?=$this->postData['orderdetail']?></textarea></td>
          </tr>
          
          <!--Issues Detail-->
          <tr class="odd">
            <td align="left" class="bold_text">Issues : </td>
            <td align="left"><textarea name="data[issues]" rows="5" class="20"><?=$this->postData['issues']?></textarea></td>
          </tr>
          
          <tr class="even">
            <td width="15%"></td>
            <td width="85%" align="left" class="bold_text"><input type="submit" name="addnewdata" class="inputbutton" value="Submit" /></td>			
          </tr>
        </table>
      </form>
      <div style="clear: both"></div>
    </div> <!-- End .module-table-body -->
  </div><!-- End .module -->
</div> <!-- End .grid_12 -->

<script type="text/ecmascript" language="javascript">
	$(document).ready(function() {
		if("<?=$_SESSION['AdminLoginID']?>"=='1') {
			$("input").attr('disabled','disabled');
			$("select").attr('disabled','disabled');
			$("textarea").attr('disabled','disabled');
		}		
	});
</script>